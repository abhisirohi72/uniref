<?php
ob_start();
ini_set('max_execution_time', 50000);
include("C:/xampp/htdocs/employee-Track/config.php");
//include("D:/xampp/htdocs/employee-Track/config.php");
include_once(__DOCUMENT_ROOT.'/classes/cls_db_manager.php');
include_once(__DOCUMENT_ROOT.'/classes/cls_calculate_distance.php');

function getEmpTravelDistance($phoneNo,$installerid,$branchid,$journeyDate)
{
	$condition = "";
	$employee_track="employee_track";
	
	$calcObj = new calculate_distance();
	
	$todayTravelData = select_query("select * from $employee_track.emp_today_tracking where phone_no='".$phoneNo."' and 
	emp_id='".$installerid."' and Date_of_journey='".$journeyDate."' and login_id='".$branchid."' and is_active='1' order by id");
	
	$startRecd = $todayTravelData[0]['gps_latitude'].'##'.$todayTravelData[0]['gps_longitude'].'##'.$todayTravelData[0]['job_location'].'##'.$todayTravelData[0]['created_datetime'];
	
	$endRecd = $todayTravelData[count($todayTravelData)-1]['gps_latitude'].'##'.$todayTravelData[count($todayTravelData)-1]['gps_longitude'].'##'.$todayTravelData[count($todayTravelData)-1]['job_location'].'##'.$todayTravelData[count($todayTravelData)-1]['created_datetime'];
	
	$distance = 0;
	$totalDistance = 0;
	for($ep=1;$ep<count($todayTravelData);$ep++)
	{
		$PointArray[0]['lat']  = $todayTravelData[$ep]['gps_latitude'];
		$PointArray[0]['long'] = $todayTravelData[$ep]['gps_longitude'];

		$PointArray[1]['lat']  = $todayTravelData[$ep - 1]['gps_latitude'];
		$PointArray[1]['long'] = $todayTravelData[$ep - 1]['gps_longitude'];
		
		if ($PointArray[1]['lat'] != "") {

			$distance = $calcObj->calc_distance($PointArray);
			
			###### Sum of distance############
			$totalDistance = $totalDistance + $distance;
		}
	}
	
	return $totalDistance.'##'.$startRecd.'##'.$endRecd;
}


function dateDifferenceSecond($date1, $date2)
{ 
	$datetime1 = strtotime($date1);
	$datetime2 = strtotime( $date2);
	$interval  = abs($datetime2 - $datetime1);
	$minutes   = $interval / 60;
	return $minutes*(60);

}

function minDifferenceForJourney($Seconds)
{ 
	$mins=$Seconds/60;
	$diff = $mins;
	$hour = $diff/60; // in day

	 $hourFix = floor($hour);
	 $hourPen = $hour - $hourFix;
	 if($hourPen > 0)
	 {
		  $min = $hourPen*(60); // in hour (1 hour = 60 min)
		  $minFix = floor($min);
		  $minPen = $min - $minFix;
		  if($minPen > 0)
		  {
			  $sec = $minPen*(60); // in sec (1 min = 60 sec)
			  $secFix = floor($sec);
		  }
	 }

	 if($hourFix > 0)
	 {
		 $str.= $hourFix.":";
	 }
	 else
	 {
		 $str.= "0:";
	 }
	
	 if($minFix > 0)
	 {
		 $str.= $minFix.":";
	 }
	 else
	 {
		 $str.= "0:";
	 }
	
	 if($secFix > 0)
	 {
		 $str.= $secFix;
	 }
	 else
	 {
		 $str.= "0";
	 }
	 return $str;

}



/*$from_date = date('Y-m-d', strtotime('-1 month'));
$to_date = date("Y-m-d", strtotime('-1 day'));*/
/*$startdate = date('Y-m-22');
$Enddate = date('Y-m-d');*/
//$startdate = date("Y-04-06");
$startdate = date("Y-m-d", strtotime('-1 day'));
$Enddate = date("Y-m-d", strtotime('-1 day'));

$start_ts = strtotime($startdate);
$end_ts = strtotime($Enddate);

$diff = $end_ts - $start_ts;

$Dayrange=round($diff / 86400)+1;

if($Dayrange>31)
{
    $Dayrange=31;
}
	
$get_employee = select_query("SELECT * FROM $employee_track.login_emp_details WHERE is_active=1 order by id ");
//echo "<pre>";print_r($get_employee);die;

//for($emp=0;$emp<1;$emp++)
for($emp=0;$emp<count($get_employee);$emp++)
{
	$phoneNo = $get_employee[$emp]['mobile_no'];
	$installerid = $get_employee[$emp]['id'];
	$branchid = $get_employee[$emp]['loginid'];
	
	for($i=0;$i<$Dayrange;$i++){
		
		$journeyDate = date("Y-m-d",strtotime(date("Y-m-d", strtotime($startdate))." +".$i." days"));
		
		$getdistance = getEmpTravelDistance($phoneNo,$installerid,$branchid,$journeyDate);
		
		//echo $getdistance;die;
		
		$split_array = explode("##",$getdistance);
		
		$Total_KM = $split_array[0];
		$start_latitude = $split_array[1];
		$start_longitude = $split_array[2];
		$Start_location = $split_array[3];
		$Start_time = $split_array[4];
		$End_latitude = $split_array[5];
		$End_longitude = $split_array[6];
		$End_location = $split_array[7];
		$End_time = $split_array[8];
		
		$journeyHrSec = dateDifferenceSecond($Start_time, $End_time);
		$journeyHr = minDifferenceForJourney($journeyHrSec);
		
		$emp_own_job = select_query("SELECT count(*) as Total_job FROM $employee_track.request WHERE emp_id='".$installerid."' and 
					phone_no='".$phoneNo."' and (date(fromtime)='".$journeyDate."' or date(totime)='".$journeyDate."') and latitude is null 
					and longtitude is null and current_record=1 and login_id='".$branchid."' ");
		
		$emp_assign_job = select_query("SELECT count(*) as Total_job FROM $employee_track.request WHERE emp_id='".$installerid."' and 
					phone_no='".$phoneNo."' and (date(fromtime)='".$journeyDate."' or date(totime)='".$journeyDate."') and latitude is not null 
					and longtitude is not null and current_record IN (0,1,3) and login_id='".$branchid."' ");
		
		
		 $insert_convance = array('emp_id' => $installerid, 'Start_time' => $Start_time, 'Start_location' => $Start_location, 
							'start_latitude' => $start_latitude, 'start_longitude' => $start_longitude, 'End_time' => $End_time, 
							'End_location' => $End_location, 'End_latitude' => $End_latitude, 'End_longitude' => $End_longitude, 
							'Total_KM' => $Total_KM, 'Total_journey_hour' => $journeyHr, 'created_own_job' => $emp_own_job[0]['Total_job'], 
							'task_assigned' => $emp_assign_job[0]['Total_job'], 'DateOf_journey' => $journeyDate, 'login_id' => $branchid);
                
         $Insert_msg = insert_query($employee_track.'.employee_consolidate', $insert_convance);
		
	}
	
	
	
	
}

echo "Data Insert Successfully.";

    
?>