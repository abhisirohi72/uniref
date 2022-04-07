<?php
				// Starting div //		

class journeyReoprtLogic extends DbManager{

var $obj;
var $distanceBetweenPoints;


function getLastRecordForJourney($vehicleId,$dateStart,$dateEnd){


	return	ReturnAnyValue("select concat(geo_street,' ',geo_town,' ',geo_country)  from telemetry where telemetry.sys_service_id='".$vehicleId."' and gps_time < '".convertToGMT($dateEnd,$_SESSION['TimeZoneDiff'])."' and gps_time > '".convertToGMT($dateStart,$_SESSION['TimeZoneDiff'])."' order by gps_time asc limit 1");
}

function getJourneyDuration($vehicleId,$dateStart,$dateEnd,$dateFormat='%d/%m/%Y %H:%i'){
                                      							
							// Manage Database Years
								$dbArray=$this->returnDbArray($dateStart,$dateEnd);
							// Manage Database


						
						## condition when date > Mid date







							################ condition one starts when date is < mid date

											for($countYear=0;$countYear<count($dbArray);$countYear++){

															    	$newQry="select  
																sum(jny_duration) as jny_duration
																 
																 from ".$dbArray[$countYear].".telemetry where 
																(sys_msg_type=3 or sys_msg_type=4) 
																and sys_service_id=".$vehicleId." and
																gps_time >= '".convertToGMT($dateStart,$_SESSION['TimeZoneDiff'])."' and gps_time <= '".convertToGMT($dateEnd,$_SESSION['TimeZoneDiff'])."' 
																ORDER BY gps_time asc ";
																$navData[]=select_query($newQry);
																//echo "<br>";
																
																
											}


									$data=array();
									for($countNavData=0;$countNavData<count($navData);$countNavData++){
											if(is_array($navData[$countNavData])){
											$data=array_merge($data,$navData[$countNavData]);
											}
									}
									$journeyDuration=0;
 
									for($i=0;$i<count($data);$i++){

 
  $journeyDuration=$journeyDuration + $data[$i]['jny_duration'];
									}

									 return $journeyDuration;
}


	function getJourneyRowByRow($vehicleId,$dateStart,$dateEnd,$dateFormat='%d/%m/%Y %H:%i'){
                                      							
							// Manage Database Years
								$dbArray=$this->returnDbArray($dateStart,$dateEnd);
							// Manage Database


						
						## condition when date > Mid date







							################ condition one starts when date is < mid date

											for($countYear=0;$countYear<count($dbArray);$countYear++){

															  	$newQry="select sys_msg_type,
																gps_time,
																gps_latitude,
																gps_longitude,
																round(gps_speed*1.609,2) as gps_speed1, round(rand()*100,2) as gps_speed,geo_street,geo_town,geo_country,
																geo_postcode,
																round(jny_distance*1.609,2) as jny_distance,
																jny_duration,
																jny_idle_time
																 from ".$dbArray[$countYear].".telemetry where 
																(sys_msg_type=3 or sys_msg_type=4) 
																and sys_service_id=".$vehicleId." and
																gps_time >= '".convertToGMT($dateStart,$_SESSION['TimeZoneDiff'])."' and gps_time <= '".convertToGMT($dateEnd,$_SESSION['TimeZoneDiff'])."' 
																ORDER BY gps_time asc ";
																$navData[]=select_query($newQry);
																//echo "<br>";
																
																
											}


									$data=array();
									for($countNavData=0;$countNavData<count($navData);$countNavData++){
											if(is_array($navData[$countNavData])){
											$data=array_merge($data,$navData[$countNavData]);
											}
									}




								//echo count($data);
							//	printarray($data);
												$j=0;

				unset($this->obj);
// Start Creating Object for each record

//									$this->obj[$j] =new VehicleJourneySummary();
									$currentJourney==new VehicleJourneySummary();
									$prevJourney==new VehicleJourneySummary();

																		/************updates only for "Praind" for POI check **************/


												for($i=0;$i<count($data);$i++){
																		/************updates only for "Praind" "DCMODELS" for POI check **************/
																					$data[$i]['poiCheck']="";

																				
																				 	$UserPoiData=select_query("SELECT name,gps_radius,(3959 * acos( cos( radians(".$data[$i]['gps_latitude'].") ) * cos( radians( gps_latitude ) ) * cos( radians( gps_longitude ) - radians(".$data[$i]['gps_longitude'].") )
												 + sin( radians(".$data[$i]['gps_latitude'].") ) * sin( radians( gps_latitude ) ) ) )*1000
												 AS distance FROM pois where sys_user_id='".$_SESSION['ParentId']."' or id in (select distinct sys_poi_id from group_pois where active=true and sys_group_id in (select sys_group_id from group_users where sys_user_id='".$_SESSION['UserId']."'))
												  ORDER BY distance asc  LIMIT  1  ");
																					
																					if(count($UserPoiData)>0 and $UserPoiData[0]['gps_radius'] > $UserPoiData[0]['distance']){
																												$data[$i]['geo_street']=$UserPoiData[0]['name'];
																												$data[$i]['poiCheck']="in";
																												$data[$i]['town']="";
																					}
																					else{
																															 $data[$i]['poiCheck']="";
																															 $poiName ='';
																					}
																	/***************************************************/


										$data[$i]['gps_local_time']=$data[$i]['gps_time'];
										$data[$i]['gps_time']=	convertToLocal($data[$i]['gps_time'],$_SESSION['TimeZoneDiff']);
										
											/*if($data[0]['sys_msg_type']==4 && $i==0){
												$i=$i+1;
											}*/

													if($data[$i]['sys_msg_type']==3){
														//echo "in sys Message 3";
/*******************************************************Check Is this the Las Entry*****/
														if($i==(count($data)-1)){
															break;
														}
														$this->obj[$j] =new VehicleJourneySummary();
														if($currentJourney->start_date=="" or $data[$i-1]['sys_msg_type']==3){
																		$currentJourney->start_date	= $data[$i]['gps_time'];
																		$currentJourney->start_local_time	= $data[$i]['gps_local_time'];
																		
																		$currentJourney->start_lat		=	$data[$i]['gps_latitude'];
																		$currentJourney->start_long	=	$data[$i]['gps_longitude'];
																		$currentJourney->start_street	=	$data[$i]['geo_street'];
																		$currentJourney->start_town	=	$data[$i]['geo_town'];
																		$currentJourney->start_country	=	$data[$i]['geo_country'];
																		$currentJourney->start_poi		=	$data[$i]['poiCheck'];							
																		$currentJourney->start_postcode=	"";
																		$currentJourney->end_postcode	=	"";            

	
																		$currentJourney->start_date	=	$data[$i]['gps_time'];
																		$currentJourney->start_lat		=	$data[$i]['gps_latitude'];
																		$currentJourney->start_long	=	$data[$i]['gps_longitude'];
																		$currentJourney->start_street	=	$data[$i]['geo_street'];
																		$currentJourney->start_town	=	$data[$i]['geo_town'];
																		$currentJourney->start_country	=	$data[$i]['geo_country'];		
																		$currentJourney->start_postcode=	"";
																		$currentJourney->end_postcode	=	"";
														}

													}// if MSG TYPE=3
													elseif($data[$i]['sys_msg_type']==4){
														
														if($currentJourney->start_date==""){
															//echo "IF 4 Repeat<br>";
															unset($currentJourney);
															$currentJourney==new VehicleJourneySummary();
														}
														else{

																	////////// Jouyney END
																	//echo "Journey End";
																		$currentJourney->end_date		=	$data[$i]['gps_time'];  
																		$currentJourney->end_local_time	= $data[$i]['gps_local_time'];

																		$currentJourney->end_lat		=	$data[$i]['gps_latitude'];  
																		$currentJourney->end_long		=	$data[$i]['gps_longitude']; 
																		$currentJourney->end_street	=	$data[$i]['geo_street'];
																		$currentJourney->end_town		=	$data[$i]['geo_town'];      
																		$currentJourney->end_country	=	$data[$i]['geo_country'];   
																		$currentJourney->end_poi		=	$data[$i]['poiCheck'];

																		$dbArray=$this->returnDbArray($currentJourney->start_local_time,$currentJourney->end_local_time);

																		$speedQry="select  round(max(gps_speed)* 1.609,2) from ".$dbArray[0].".telemetry where sys_service_id=".$vehicleId." and gps_time >= '".$currentJourney->start_local_time."' and gps_time <= '".$currentJourney->end_local_time."'";


																		$currentJourney->reported_max_speed	=ReturnAnyValue($speedQry);;
																	//	$currentJourney->reported_max_speed	=10;

																					// Added Hack on 1 May to implement speed
																					if($currentJourney->reported_max_speed==""){
																						$dbArray=$this->returnDbArray($currentJourney->start_local_time,$currentJourney->end_local_time);
																						$speedQry="select  round(max(gps_speed)* 1.609,2) from ".$dbArray[1].".telemetry where sys_service_id=".$vehicleId." and gps_time >= '".$currentJourney->start_local_time."' and gps_time <= '".$currentJourney->end_local_time."'";
																						$currentJourney->reported_max_speed	=ReturnAnyValue($speedQry);;
																						//$currentJourney->reported_max_speed	=10;
																					}


																		$currentJourney->distance		=$data[$i]['jny_distance'];
																		$currentJourney->idle_time		=$data[$i]['jny_idle_time'];
																		$currentJourney->duration		=$data[$i]['jny_duration'];
	
																						
																						/*if($currentJourney->distance==0){
																								unset($currentJourney);
																								$currentJourney==new VehicleJourneySummary();
																						}
																						else{
																							$this->obj[$j] = $currentJourney;
																							$j=$j+1;
																							//echo "Journey End<br>";
																							unset($currentJourney);
																							$currentJourney==new VehicleJourneySummary();
																						}*/
																							$this->obj[$j] = $currentJourney;
																							$j=$j+1;
																							//echo "Journey End<br>";
																							unset($currentJourney);
																							$currentJourney==new VehicleJourneySummary();
									


														}

													}// End of elsef 4



													// Getting Data From anothe Row


													// Increase Counter for Object count

									}// End of object 

									return $this->obj;

	} // Function End here


function getJourneyRowByRow_new($vehicleId,$dateStart,$dateEnd){
 

$newQry="select * from journey where     sys_service_id=".$vehicleId." and trip_start_date >= '".$dateStart."' and trip_start_date <= '".$dateEnd."' 
ORDER BY trip_start_date asc ";
$data=select_query($newQry);
 
  
$j=0;
 


$currentJourney==new VehicleJourneySummary(); 

for($i=0;$i<count($data);$i++)
	
{
	 
 


 $data[$i]['gps_local_time']=$data[$i]['trip_start_date'];
 $data[$i]['gps_local_time_end']=$data[$i]['trip_end_date'];
 //$data[$i]['trip_start_date']=	convertToLocal($data[$i]['trip_start_date'],$_SESSION['TimeZoneDiff']);
 //$data[$i]['trip_end_date']=	convertToLocal($data[$i]['trip_end_date'],$_SESSION['TimeZoneDiff']);

$arrstartlatlong=explode(",",$data[$i]['trip_start_date']);
$Startlat=$arrstartlatlong[0];
$Startlong=$arrstartlatlong[1];

$arrendlatlong=explode(",",$data[$i]['trip_end_date']);
$Endlat=$arrstartlatlong[0];
$Endlong=$arrstartlatlong[1];


 
			$currentJourney->start_date	= $data[$i]['trip_start_date'];
			$currentJourney->start_local_time	= $data[$i]['gps_local_time'];

			$currentJourney->start_lat		=	$Startlat;
			$currentJourney->start_long	 =	$Startlong;
			$currentJourney->start_street	=	$data[$i]['trip_start_location'];
			$currentJourney->start_town	=	"";
			$currentJourney->start_country	=	"";
			$currentJourney->start_poi		=	"";							
			$currentJourney->start_postcode=	""; 
 
			$currentJourney->end_postcode	=	"";
			$currentJourney->end_date		=	$data[$i]['trip_end_date'];  
			$currentJourney->end_local_time	= $data[$i]['gps_local_time_end'];

			$currentJourney->end_lat		=	$Endlat;  
			$currentJourney->end_long		=	$Endlong;
			$currentJourney->end_street	    =	 $data[$i]['trip_end_location'];
			$currentJourney->end_town		=	"";      
			$currentJourney->end_country	=	"";   
			$currentJourney->end_poi		=	"";
			$currentJourney->end_poi		=	"";

			$dbArray=$this->returnDbArray($currentJourney->start_local_time,$currentJourney->end_local_time);

			$speedQry="select  round(max(gps_speed)* 1.609,2) from ".$dbArray[0].".telemetry where sys_service_id=".$vehicleId." and gps_time >= '".$currentJourney->start_local_time."' and gps_time <= '".$currentJourney->end_local_time."'";


			$currentJourney->reported_max_speed	=ReturnAnyValue($speedQry);
			 
			if($currentJourney->reported_max_speed==""){
			$dbArray=$this->returnDbArray($currentJourney->start_local_time,$currentJourney->end_local_time);
			$speedQry="select  round(max(gps_speed)* 1.609,2) from ".$dbArray[1].".telemetry where sys_service_id=".$vehicleId." and gps_time >= '".$currentJourney->start_local_time."' and gps_time <= '".$currentJourney->end_local_time."'";
			$currentJourney->reported_max_speed	=ReturnAnyValue($speedQry);
			//$currentJourney->reported_max_speed	=10;
			}


				$currentJourney->distance		=$data[$i]['trip_Km'];
				$currentJourney->idle_time		="";
				$currentJourney->duration		=$data[$i]['trip_duration'];



				$this->obj[$j] = $currentJourney;
				$j=$j+1;

				unset($currentJourney);
				$currentJourney==new VehicleJourneySummary();



}

 

return $this->obj;

}










function GetActivedeviceHour($vehicleId,$dateStart,$date_end_val,$dateFormat='%d/%m/%Y %H:%i'){
 

  /*while (strtotime($dateStart) <= strtotime($dateEnd)) 
		 {
			 $date_start_val=$dateStart;
			
			 $date_end_val= date ("Y-m-d H:i:s", strtotime("+1 day", strtotime($dateStart)));
			*/ 
$dbArray=$this->returnDbArray($dateStart,$date_end_val);
  

################ condition one starts when date is < mid date

for($countYear=0;$countYear<count($dbArray);$countYear++){

$newQry="select  date(gps_time) as date_timestamp, DATE_FORMAT(gps_time,'%b %d %Y %k:') as hour_timestamp    from ".$dbArray[$countYear].".telemetry where sys_service_id='".$vehicleId."' and gps_time >= '".convertToGMT($dateStart,$_SESSION['TimeZoneDiff'])."' and gps_time <= '".convertToGMT($date_end_val,$_SESSION['TimeZoneDiff'])."' group by  DATE_FORMAT(gps_time,'%b %d %Y %k:') order by gps_time";
$navData[]=select_query($newQry);
 


}
		// }
 
$data=array();
for($countNavData=0;$countNavData<count($navData);$countNavData++){
if(is_array($navData[$countNavData])){
$data=array_merge($data,$navData[$countNavData]);
}
}
 

return  count($data);
}

	function getJourneyRowByRow_test($vehicleId,$dateStart,$dateEnd,$dateFormat='%d/%m/%Y %H:%i'){
                                  
																
															 
							// Manage Database Years
								$dbArray=$this->returnDbArray($dateStart,$dateEnd);
							// Manage Database


						
						## condition when date > Mid date







							################ condition one starts when date is < mid date

											for($countYear=0;$countYear<count($dbArray);$countYear++){

															  $newQry="select sys_msg_type,
																gps_time,
																gps_latitude,
																gps_longitude,
																round(gps_speed*1.609,2) as gps_speed1, round(rand()*100,2) as gps_speed,geo_street,geo_town,geo_country,
																geo_postcode,
																round(jny_distance*1.609,2) as jny_distance,
																jny_duration,
																jny_idle_time
																 from ".$dbArray[$countYear].".telemetry where 
																(sys_msg_type=3 or sys_msg_type=4) 
																and sys_service_id=".$vehicleId." and
																gps_time >= '".convertToGMT($dateStart,$_SESSION['TimeZoneDiff'])."' and gps_time <= '".convertToGMT($dateEnd,$_SESSION['TimeZoneDiff'])."' 
																ORDER BY gps_time asc ";
																$navData[]=select_query($newQry);
																//echo "<br>";
																
																
											}


									$data=array();
									for($countNavData=0;$countNavData<count($navData);$countNavData++){
											if(is_array($navData[$countNavData])){
											$data=array_merge($data,$navData[$countNavData]);
											}
									}




								//echo count($data);
							//	printarray($data);
												$j=0;

				unset($this->obj);
// Start Creating Object for each record

//									$this->obj[$j] =new VehicleJourneySummary();
									$currentJourney==new VehicleJourneySummary();
									$prevJourney==new VehicleJourneySummary();

																		/************updates only for "Praind" for POI check **************/


												for($i=0;$i<count($data);$i++){
																		/************updates only for "Praind" "DCMODELS" for POI check **************/
																					$data[$i]['poiCheck']="";

																				
																					$UserPoiData=select_query("SELECT name,gps_radius,(3959 * acos( cos( radians(".$data[$i]['gps_latitude'].") ) * cos( radians( gps_latitude ) ) * cos( radians( gps_longitude ) - radians(".$data[$i]['gps_longitude'].") )
												 + sin( radians(".$data[$i]['gps_latitude'].") ) * sin( radians( gps_latitude ) ) ) )*1000
												 AS distance FROM pois where sys_user_id='".$_SESSION['ParentId']."' or id in (select distinct sys_poi_id from group_pois where active=true and sys_group_id in (select sys_group_id from group_users where sys_user_id='".$_SESSION['UserId']."'))
												  ORDER BY distance asc  LIMIT  1  ");
																					
																					if(count($UserPoiData)>0 and $UserPoiData[0]['gps_radius'] > $UserPoiData[0]['distance']){
																												$data[$i]['geo_street']=$UserPoiData[0]['name'];
																												$data[$i]['poiCheck']="in";
																												$data[$i]['town']="";
																					}
																					else{
																															 $data[$i]['poiCheck']="";
																															 $poiName ='';
																					}
																	/***************************************************/


										$data[$i]['gps_local_time']=$data[$i]['gps_time'];
										$data[$i]['gps_time']=	convertToLocal($data[$i]['gps_time'],$_SESSION['TimeZoneDiff']);
										
											/*if($data[0]['sys_msg_type']==4 && $i==0){
												$i=$i+1;
											}*/

													if($data[$i]['sys_msg_type']==3){
														//echo "in sys Message 3";
/*******************************************************Check Is this the Las Entry*****/
														if($i==(count($data)-1)){
															break;
														}
														$this->obj[$j] =new VehicleJourneySummary();
														if($currentJourney->start_date=="" or $data[$i-1]['sys_msg_type']==3){
																		$currentJourney->start_date	= $data[$i]['gps_time'];
																		$currentJourney->start_local_time	= $data[$i]['gps_local_time'];
																		
																		$currentJourney->start_lat		=	$data[$i]['gps_latitude'];
																		$currentJourney->start_long	=	$data[$i]['gps_longitude'];
																		$currentJourney->start_street	=	$data[$i]['geo_street'];
																		$currentJourney->start_town	=	$data[$i]['geo_town'];
																		$currentJourney->start_country	=	$data[$i]['geo_country'];
																		$currentJourney->start_poi		=	$data[$i]['poiCheck'];							
																		$currentJourney->start_postcode=	"";
																		$currentJourney->end_postcode	=	"";            

	
																		$currentJourney->start_date	=	$data[$i]['gps_time'];
																		$currentJourney->start_lat		=	$data[$i]['gps_latitude'];
																		$currentJourney->start_long	=	$data[$i]['gps_longitude'];
																		$currentJourney->start_street	=	$data[$i]['geo_street'];
																		$currentJourney->start_town	=	$data[$i]['geo_town'];
																		$currentJourney->start_country	=	$data[$i]['geo_country'];		
																		$currentJourney->start_postcode=	"";
																		$currentJourney->end_postcode	=	"";
														}

													}// if MSG TYPE=3
													elseif($data[$i]['sys_msg_type']==4){
														
														if($currentJourney->start_date==""){
															//echo "IF 4 Repeat<br>";
															unset($currentJourney);
															$currentJourney==new VehicleJourneySummary();
														}
														else{

																	////////// Jouyney END
																	//echo "Journey End";
																		$currentJourney->end_date		=	$data[$i]['gps_time'];  
																		$currentJourney->end_local_time	= $data[$i]['gps_local_time'];

																		$currentJourney->end_lat		=	$data[$i]['gps_latitude'];  
																		$currentJourney->end_long		=	$data[$i]['gps_longitude']; 
																		$currentJourney->end_street	=	$data[$i]['geo_street'];
																		$currentJourney->end_town		=	$data[$i]['geo_town'];      
																		$currentJourney->end_country	=	$data[$i]['geo_country'];   
																		$currentJourney->end_poi		=	$data[$i]['poiCheck'];

																		$dbArray=$this->returnDbArray($currentJourney->start_local_time,$currentJourney->end_local_time);

																		$speedQry="select  round(max(gps_speed)* 1.609,2) from ".$dbArray[0].".telemetry where sys_service_id=".$vehicleId." and gps_time >= '".$currentJourney->start_local_time."' and gps_time <= '".$currentJourney->end_local_time."'";


																		$currentJourney->reported_max_speed	=ReturnAnyValue($speedQry);;
																	//	$currentJourney->reported_max_speed	=10;

																					// Added Hack on 1 May to implement speed
																					if($currentJourney->reported_max_speed==""){
																						$dbArray=$this->returnDbArray($currentJourney->start_local_time,$currentJourney->end_local_time);
																						$speedQry="select  round(max(gps_speed)* 1.609,2) from ".$dbArray[1].".telemetry where sys_service_id=".$vehicleId." and gps_time >= '".$currentJourney->start_local_time."' and gps_time <= '".$currentJourney->end_local_time."'";
																						$currentJourney->reported_max_speed	=ReturnAnyValue($speedQry);;
																						//$currentJourney->reported_max_speed	=10;
																					}


																		$currentJourney->distance		=$data[$i]['jny_distance'];
																		$currentJourney->idle_time		=$data[$i]['jny_idle_time'];
																		$currentJourney->duration		=$data[$i]['jny_duration'];
	
																						
																						/*if($currentJourney->distance==0){
																								unset($currentJourney);
																								$currentJourney==new VehicleJourneySummary();
																						}
																						else{
																							$this->obj[$j] = $currentJourney;
																							$j=$j+1;
																							//echo "Journey End<br>";
																							unset($currentJourney);
																							$currentJourney==new VehicleJourneySummary();
																						}*/
																							$this->obj[$j] = $currentJourney;
																							$j=$j+1;
																							//echo "Journey End<br>";
																							unset($currentJourney);
																							$currentJourney==new VehicleJourneySummary();
									


														}

													}// End of elsef 4



													// Getting Data From anothe Row


													// Increase Counter for Object count

									}// End of object 

									return $this->obj;

	} // Function End here



	function getManualJourney($vehicleId,$dateStart,$dateEnd,$dateFormat='%d/%m/%Y %H:%i'){

		$manualEntryJny=select_query("select  * from `manual_jny_entry` where service_id='".$vehicleId."'
 and start_date >= '".$dateStart."'  and  end_date <= '".$dateEnd."' 
 ORDER BY start_date asc");

// $manualEntryJny[0]['start_date']
					 return $manualEntryJny; 


}// Class End Here



}// Class End Here



?>