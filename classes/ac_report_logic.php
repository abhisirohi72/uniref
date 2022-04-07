<?php
				// Starting div //		

class acReoprtLogic extends DbManager{

	var $obj;
	
   
function array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
  $colarr[$col] = array();
  foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
 }

 $eval = 'array_multisort(';
 
 foreach ($cols as $col => $order) {
  $eval .= '$colarr[\''.$col.'\'],'.$order.',';
 }

 $eval = substr($eval,0,-1).');';
 eval($eval);
 $ret = array();
 foreach ($colarr as $col => $arr) {
  foreach ($arr as $k => $v) {
   $k = substr($k,1);
   if (!isset($ret[$k])) $ret[$k] = $array[$k];
   $ret[$k][$col] = $array[$k][$col];
  }
 }
 return $ret;

} 
	

	function getJourneyRowByRow($vehicleId,$dateStart,$dateEnd,$dateFormat='%d/%m/%Y %H:%i'){
                                      
							// Manage Database Years
								$dbArray=$this->returnDbArray($dateStart,$dateEnd);
							// Manage Database
							

							################ condition one starts when date is < mid date

											for($countYear=0;$countYear<count($dbArray);$countYear++){
//round(rand()*100,2) as gps_speed
// round(jny_distance*1.609,2) as jny_distance,,
										//round(gps_speed*1.609,2) as gps_speed1,
$navData[]=select_query("select id,sys_msg_type,
										gps_time,
										gps_latitude,
										gps_longitude,geo_street,geo_town,geo_country,
										geo_postcode,
										round(jny_distance*1.609,2) as jny_distance,
										jny_duration,
										jny_idle_time from ".$dbArray[$countYear].".telemetry_speed where sys_service_id=".$vehicleId." and gps_time>='".convertToGMT($dateStart,$_SESSION['TimeZoneDiff'])."' and gps_time <= '".convertToGMT($dateEnd,$_SESSION['TimeZoneDiff'])."' and tel_ignition=true  order by gps_time");
$navData[]=select_query("select id,sys_msg_type,
										gps_time,
										gps_latitude,
										gps_longitude, geo_street,geo_town,geo_country,
										geo_postcode,
										jny_duration,
										jny_idle_time from ".$dbArray[$countYear].".telemetry_ideal where sys_service_id=".$vehicleId." and gps_time>='".convertToGMT($dateStart,$_SESSION['TimeZoneDiff'])."' and gps_time <= '".convertToGMT($dateEnd,$_SESSION['TimeZoneDiff'])." and tel_ignition=true  
order by gps_time");
$navData[]=select_query("select id,sys_msg_type,
										gps_time,
										gps_latitude,
										gps_longitude, geo_street,geo_town,geo_country,
										geo_postcode,
										jny_duration,
										jny_idle_time from ".$dbArray[$countYear].".today_ideal where sys_service_id=".$vehicleId." and gps_time>='".convertToGMT($dateStart,$_SESSION['TimeZoneDiff'])."' and gps_time <= '".convertToGMT($dateEnd,$_SESSION['TimeZoneDiff'])." and tel_ignition=true  
order by gps_time");
$navData[]=select_query("select id,sys_msg_type,
										gps_time,
										gps_latitude,
										gps_longitude, geo_street,geo_town,geo_country,
										geo_postcode,
										jny_duration,
										jny_idle_time from ".$dbArray[$countYear].".today_speed where sys_service_id=".$vehicleId." and gps_time>='".convertToGMT($dateStart,$_SESSION['TimeZoneDiff'])."' and gps_time <= '".convertToGMT($dateEnd,$_SESSION['TimeZoneDiff'])." and tel_ignition=true  
order by gps_time");

									}
 
									$data1=array();
									
									for($countNavData=0;$countNavData<count($navData);$countNavData++){
											if(is_array($navData[$countNavData])){
												$data1=array_merge($data1,$navData[$countNavData]);
											}
									}
                                        
								//echo count($data);
								
								//exit;
								 


								
						$data2 = $this->array_msort($data1, array('gps_time'=>SORT_ASC));


						foreach($data2 as $val)
							{
						$data[] = $val;
							}
							 

				unset($this->obj);
$j=0;
								
								if(count($data)>1){

										for($i=0;$i<count($data);$i++){

												if($i==0){
														$this->obj[$j] =new VehicleAcSummary();
														$this->obj[$j]->start_date	= $data[$i]['gps_time'];
														//$this->obj[$j]->start_date = convertToLocal($data[$i]['gps_time'],$_SESSION['TimeZoneDiff']);

														$this->obj[$j]->start_id	= $data[$i]['id'];
														$this->obj[$j]->start_street	= $data[$i]['geo_street'];
														$this->obj[$j]->start_town	=	$data[$i]['geo_town'];
														$this->obj[$j]->start_country	=	$data[$i]['geo_country'];
														$this->obj[$j]->start_lat	=	$data[$i]['gps_latitude'];
														$this->obj[$j]->start_long	=	$data[$i]['gps_longitude'];
														continue;
												}
												
											$datediffrence=((DateMysqlToTimestamp($data[$i]['gps_time'])-DateMysqlToTimestamp($data[$i-1]['gps_time']))/60);
											if($datediffrence>10){
												$this->obj[$j]->end_date= $data[$i-1]['gps_time'];
												$this->obj[$j]->end_id	= $data[$i-1]['id'];
												$this->obj[$j]->end_street=$data[$i-1]['geo_street'];

												$this->obj[$j]->end_lat	=	$data[$i-1]['gps_latitude'];
												$this->obj[$j]->end_long	=	$data[$i-1]['gps_longitude'];

												$this->obj[$j]->time_difference=DateMysqlToTimestamp($this->obj[$j]->end_date)-DateMysqlToTimestamp($this->obj[$j]->start_date);
													$j=$j+1;
													$this->obj[$j] =new VehicleAcSummary();
													$this->obj[$j]->start_date	= $data[$i]['gps_time'];
													$this->obj[$j]->start_id	= $data[$i]['id'];
													$this->obj[$j]->start_street	= $data[$i]['geo_street'];
													$this->obj[$j]->start_town	=	$data[$i]['geo_town'];
													$this->obj[$j]->start_country	=	$data[$i]['geo_country'];
													$this->obj[$j]->start_lat	=	$data[$i]['gps_latitude'];
													$this->obj[$j]->start_long	=	$data[$i]['gps_longitude'];


											}

										}// End of object for for Loop

										$this->obj[$j]->end_date=$data[count($data)-1]['gps_time'];
										//$this->obj[$j]->end_date=convertToLocal($data[count($data)-1]['gps_time'],$_SESSION['TimeZoneDiff']);

										$this->obj[$j]->end_id	= $data[count($data)-1]['id'];
										$this->obj[$j]->end_street=$data[count($data)-1]['geo_street'];
										$this->obj[$j]->time_difference=DateMysqlToTimestamp($this->obj[$j]->end_date)-DateMysqlToTimestamp($this->obj[$j]->start_date);

										$this->obj[$j]->end_lat	=	$data[count($data)-1]['gps_latitude'];
										$this->obj[$j]->end_long	=	$data[count($data)-1]['gps_longitude'];

									} // End of If



				return $this->obj;

	} // Function End here

}// Class End Here

?>