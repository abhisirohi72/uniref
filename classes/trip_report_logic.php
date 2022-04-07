<?php
				// Starting div //		

class tripReoprtLogic extends DbManager{

var $obj;
var $obj_GeoChecker;
var $distanceBetweenPoints;
var $isInsidePOI;
var $tempArray=array();
var $countTempArray;




function __construct(){
	$this->isInsidePOI=1; // 0 means inside POI // 1 means outside
	$this->inJourney="no";
	$this->countTempArray=0;
	$this->obj_GeoChecker=new GeoChecker();
}


	function getJourneyRowByRow($vehicleId,$dateStart,$dateEnd,$dateFormat='%d/%m/%Y %H:%i'){
                                      

							// Manage Database Years
								$dbArray=$this->returnDbArray($dateStart,$dateEnd);
							// Manage Database

								for($countYear=0;$countYear<count($dbArray);$countYear++){

										$newQry="select sys_msg_type,
										gps_time,
										gps_latitude,
										gps_longitude,
										round(gps_speed*1.609,2) as gps_speed1, round(rand()*100,2) as gps_speed,geo_street,geo_town,geo_country,
										geo_postcode,
										round(jny_distance*1.609,2) as jny_distance,
										jny_duration,tel_odometer,
										jny_idle_time
										 from ".$dbArray[$countYear].".telemetry where 
										(sys_msg_type=3 or sys_msg_type=4) 
										and sys_service_id=".$vehicleId."  and
										gps_time >= '".convertToGMT($dateStart,$_SESSION['TimeZoneDiff'])."' and gps_time <= '".convertToGMT($dateEnd,$_SESSION['TimeZoneDiff'])."' 
										ORDER BY gps_time asc ";
										
										$navData[]=select_query($newQry);
								}



											$data=array();
											for($countNavData=0;$countNavData<count($navData);$countNavData++){
													if(is_array($navData[$countNavData])){
													$data=array_merge($data,$navData[$countNavData]);
													}
											}

									//printarray($data);

									$ArrayCount=0;
									$this->countTempArray=0;
									while($ArrayCount<count($data)){


										$data[$ArrayCount]['gps_local_time']=$data[$ArrayCount]['gps_time'];
										$data[$ArrayCount]['gps_time']=	convertToLocal($data[$ArrayCount]['gps_time'],$_SESSION['TimeZoneDiff']);

											$this->distanceBetweenPoints = $this->obj_GeoChecker->calculateDistanceBetweenLatLong(29.204820633,78.915908813,$data[$ArrayCount]['gps_latitude'], $data[$ArrayCount]['gps_longitude']);
											$this->distanceBetweenPoints=($this->distanceBetweenPoints*1.609)*1000; 

											$data[$ArrayCount]['distance_between_points']=$this->distanceBetweenPoints;;



											if($this->distanceBetweenPoints<=300)
											{
															

													 $data[$ArrayCount]['poi']="Prakash";
													 $data[$ArrayCount]['poiCheck']="in";
											}else{
													 $data[$ArrayCount]['poiCheck']="out";
											}




											if($this->isInsidePOI==1){


													if($data[$ArrayCount]['poiCheck']=="in"){

															if($this->inJourney=="yes"){
																	
																	$this->tempArray[$this->countTempArray]['end']="End";
																	$this->tempArray[$this->countTempArray]['endTime']=$data[$ArrayCount]['gps_time'];
																	$this->tempArray[$this->countTempArray]['jny_duration']=round((($data[$ArrayCount]['tel_odometer']-$this->tempArray[$this->countTempArray]['start_time_odometer'])*1.609),2);
																	$this->countTempArray=$this->countTempArray+1;
																	$this->inJourney=="no";
																	$this->isInsidePOI=0;
																	$ArrayCount=$ArrayCount+1;
																	continue;

															}
															$this->isInsidePOI=0;

													}
													else{


													}


											}
											else{

												if($data[$ArrayCount]['poiCheck']=="in"){

												}
												else{
															$this->isInsidePOI=1;
															
															$this->inJourney="yes";
															$this->tempArray[$this->countTempArray]['start']="Start";
															
															// Updtaed these two lines During survey. As I found journey time as coming of next data
															//$this->tempArray[$this->countTempArray]['startTime']=$data[$ArrayCount]['gps_time'];
															//$this->tempArray[$this->countTempArray]['start_time_odometer']=$data[$ArrayCount]['tel_odometer'];

															$this->tempArray[$this->countTempArray]['startTime']=$data[$ArrayCount]['gps_time'];
															
															$this->tempArray[$this->countTempArray]['start_time_odometer']=$data[$ArrayCount]['tel_odometer'];

												}

											}

											

											$ArrayCount=$ArrayCount+1;



									} // End Of While Loop


/*echo "<pre>";
print_r($data);
echo "</pre>";

echo "<pre>";
print_r($this->tempArray);
echo "</pre>";*/
					return $this->tempArray;
					//return $data;

	} // Function End here


}// Class End Here

















?>