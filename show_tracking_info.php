<?php
session_start();
include("config.php");

$id_roles=$_SESSION['id_roles'];
//echo "<pre>";print_r($_REQUEST);die;


if(isset($_GET['action']) && $_GET['action']=='GetEmpStartKm')
{

	$Phoneno = $_GET["Phoneno"];
	$emp_id = $_GET["rid"];
	$branch = $_GET["branch"];
	$startdate = $_GET["daterange"];
	
	if($id_roles==1)
	{
		$emp_start_km = select_query("SELECT * FROM $db_name.installer_attendence_tbl WHERE inst_id='".$emp_id."' and mobile_no='".$Phoneno."' and 
						req_date='".$startdate."' and is_active=1 ");
	} else {
		
		$emp_start_km = select_query("SELECT * FROM $db_name.installer_attendence_tbl WHERE inst_id='".$emp_id."' and mobile_no='".$Phoneno."' and 
						req_date='".$startdate."' and is_active=1 and login_id='".$branch."' ");
	}
	//echo "<pre>";print_r($emp_track_data);die;
	
	$get_emp_data = select_query("SELECT emp_name FROM $db_name.login_emp_details WHERE id='".$emp_id."' ");
?>
<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>  
        <th>Request Date</th> 
        <th>Phone No/ Name</th>
        <th>Odometer Image</th>
        <th>Start KM</th> 
    </tr>     
    </thead>    
    <tbody>
	<?
		 for($i=0;$i<count($emp_start_km);$i++) {
	?>
 	<tr>
    	<td><?=$emp_start_km[$i]['req_date'];?></td> 
        <td><?=$emp_start_km[$i]['mobile_no'].'/ '.$get_emp_data[0]['emp_name'];?></td>
        <td><? if (isset($emp_start_km[$i]['starttime_odometer_image']) && !empty($emp_start_km[$i]['starttime_odometer_image']))?><img src="http://203.115.101.54/uniRefSystemAPIV1/uploads/services/odometer_image/<?=$emp_start_km[$i]['starttime_odometer_image']?>" width="50" height="50"></td>
        
        <td><?=$emp_start_km[$i]['odometer_start_km'];?></td>
    </tr>

    <? }

}

if(isset($_GET['action']) && $_GET['action']=='GetEmpEndKm')
{

	$Phoneno = $_GET["Phoneno"];
	$emp_id = $_GET["rid"];
	$branch = $_GET["branch"];
	$startdate = $_GET["daterange"];
	
	if($id_roles==1)
	{
		$emp_start_km = select_query("SELECT * FROM $db_name.installer_attendence_tbl WHERE inst_id='".$emp_id."' and mobile_no='".$Phoneno."' and 
						req_date='".$startdate."' and is_active=1 ");
	} else {
		
		$emp_start_km = select_query("SELECT * FROM $db_name.installer_attendence_tbl WHERE inst_id='".$emp_id."' and mobile_no='".$Phoneno."' and 
						req_date='".$startdate."' and is_active=1 and login_id='".$branch."' ");
	}
	//echo "<pre>";print_r($emp_track_data);die;
	
	$get_emp_data = select_query("SELECT emp_name FROM $db_name.login_emp_details WHERE id='".$emp_id."' ");
?>
<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>  
        <th>Request Date</th> 
        <th>Phone No/ Name</th>
        <th>Odometer Image</th>
        <th>End KM</th> 
    </tr>     
    </thead>    
    <tbody>
	<?
		 for($i=0;$i<count($emp_start_km);$i++) {
	?>
 	<tr>
    	<td><?=$emp_start_km[$i]['req_date'];?></td> 
        <td><?=$emp_start_km[$i]['mobile_no'].'/ '.$get_emp_data[0]['emp_name'];?></td>
        <td><? if (isset($emp_start_km[$i]['endtime_odometer_image']) && !empty($emp_start_km[$i]['endtime_odometer_image']))?><img src="http://203.115.101.54/uniRefSystemAPIV1/uploads/services/odometer_image/<?=$emp_start_km[$i]['endtime_odometer_image']?>" width="50" height="50"></td>
        
        <td><?=$emp_start_km[$i]['odometer_end_km'];?></td>
    </tr>

    <? }

}

if(isset($_GET['action']) && $_GET['action']=='GetAllLocationWithDate')
{
	$Phoneno = $_GET["Phoneno"];
	$emp_id = $_GET["rid"];
	$branch = $_GET["branch"];
	$startdate = $_GET["daterange"];
	
	
	$emp_track_data = select_query("select phone_no, job_location, gps_latitude, gps_longitude, battery_level, Date_of_journey, 
		location_time from $db_name.technicians_tracking where  phone_no='".$Phoneno."' and tech_id='".$emp_id."' and is_active='1' 
		and login_id='".$branch."' and Date_of_journey='".$startdate."'  order by location_time desc");
		
	
	//echo "<pre>";print_r($emp_track_data);die;
	
	$get_emp_data = select_query("SELECT emp_name FROM $db_name.technicians_login_details WHERE id='".$emp_id."' ");
?>
<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>  
        <th>Employee</th> 
        <th>Phone No</th>
        <th>Date</th>
        <th>Time</th> 
        <th>Location</th>
        <th>Battery</th>

    </tr>     
    </thead>    
    <tbody>
	<?
		 for($i=0;$i<count($emp_track_data);$i++) {
	?>
 	<tr>
    	<td><?=$get_emp_data[0]['emp_name'];?></td> 
        <td><?=$emp_track_data[$i]['phone_no'];?></td>
        <td><?=date("d/m/Y",strtotime($emp_track_data[$i]['Date_of_journey']))?></td>
        <td><?=date("d/m/Y H:i:s",strtotime($emp_track_data[$i]['location_time']))?></td>
        <td><?=$emp_track_data[$i]['job_location']."<br/><b>".$emp_track_data[$i]['gps_latitude'].','.$emp_track_data[$i]['gps_longitude'].'</b>';?></td>
        <td><?=$emp_track_data[$i]['battery_level'];?></td>     
         
    </tr>

    <? }

}
?>