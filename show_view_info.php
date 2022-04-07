<?php
session_start();
include_once('config.php');

//echo "<pre>";print_r($_REQUEST);die;

function dateDifference($date1, $date2)
{ 

    $days1 = date('d', strtotime($date1));        

    $ts1 = strtotime($date1);        
    $ts2 = strtotime($date2);
    
    $year1 = date('Y', $ts1);
    $year2 = date('Y', $ts2);
    
    $month1 = date('m', $ts1);
    $month2 = date('m', $ts2);
    
    if($days1 > 15)        
    {
        $months = (($year2 - $year1) * 12) + ($month2 - $month1);
    }
    else if($days1 < 16)        
    {
        $months = ((($year2 - $year1) * 12) + ($month2 - $month1))+1;
    }
        
   return $months;

}
if(isset($_GET['action']) && $_GET['action']=='GetTechnicianDetails')
{

	$tech_id = $_GET["rid"];
	
	$getEmpTripData = select_query("select * from $db_name.technicians_login_details where id='".$tech_id."' and is_active='1' order by id");
						
	//echo "<pre>";print_r($getEmpTripData);die;
	
?>
<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>  
        <th>Name/ID</th>
        <th>Mobile No</th>
        <th>Aadhar No</th>
        <th>Home Address</th>
        <th>Office Address</th>
        <th>Gender</th>
        <th>DOB</th>
        <th>Document Submitted</th>
        <th>Office Timing</th>
        <th>Specialization</th>
        <th>Date of Joining</th> 
        <th>Monthly Salary</th> 
    </tr>     
    </thead>    
    <tbody>
	<?
		 for($i=0;$i<count($getEmpTripData);$i++) {
	?>
 	<tr>
         <td><?=$getEmpTripData[$i]['emp_name'].'/'.$getEmpTripData[$i]['technician_id'];?></td>
         <td><?=$getEmpTripData[$i]['mobile_no'];?></td>
         <td><?=$getEmpTripData[$i]['aadhar_no'];?></td>
         <td><?=$getEmpTripData[$i]['home_address']."<br/><b> LatLong:-".$getEmpTripData[$i]['home_latitude'].','.$getEmpTripData[$i]['home_longitude'].'</b>';?></td>
         <td><?=$getEmpTripData[$i]['ofy_address']."<br/><b> LatLong:-".$getEmpTripData[$i]['ofy_latitude'].','.$getEmpTripData[$i]['ofy_longtitude'].'</b>';?></td>
         <td><?=$getEmpTripData[$i]['gender'];?></td>
         <td><?=$getEmpTripData[$i]['dob'];?></td>
         <td><?=$getEmpTripData[$i]['document_submit'];?></td>
         
         <td><?=date("h:i A",strtotime($getEmpTripData[$i]['ofy_from_time'])).' - '.date("h:i A",strtotime($getEmpTripData[$i]['ofy_to_time']));?></td>
         <td><?=$getEmpTripData[$i]['specialization'];?></td>
         <td><?=$getEmpTripData[$i]['date_of_joining'];?></td>
         <td><?=$getEmpTripData[$i]['monthly_salary'];?></td>
    </tr>

    <? }

}

if(isset($_GET['action']) && $_GET['action']=='GetTechnicianAllJobDetails')
{

	$tech_id = $_GET["rid"];
	
	$getJobData = select_query("select * from $db_name.all_job_details where to_technician='".$tech_id."' and job_status='5' order by id");
	
	
	$getEmpDetails = select_query("select * from $db_name.technicians_login_details where id='".$getJobData[0]['to_technician']."' and is_active='1' ");					
	//echo "<pre>";print_r($getEmpTripData);die;
	
?>
<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>  
        <th>Ticket No</th>
        <th>Service Date</th>
        <th>Service Type</th>
        <th>Model No</th>
        <th>Serial No</th>
        <th>By Technician</th>
        <th>Ticket Status</th>
    </tr>     
    </thead>    
    <tbody>
	<?
		 for($i=0;$i<count($getJobData);$i++) {
		
	?>
 	<tr>
         <td><?=$getJobData[$i]['ticket_no'];?></td>
         <td><?=$getJobData[$i]['request_date'];?></td>
         <td><?=$getJobData[$i]['service_type'];?></td>
         
         <td><?=$getJobData[$i]['model_no'];?></td>
         <td><?=$getJobData[$i]['serial_no'];?></td>
         <td><?=$getEmpDetails[0]['emp_name'];?></td>
         <td><?php if( $getJobData[$i]['job_status'] == '1' ) { echo "On the Way";} else if( $getJobData[$i]['job_status'] == '2' ) { echo "Currently Working";} else if( $getJobData[$i]['job_status'] == '5' ) { echo "Completed";} else {echo "No Action";} ?></td> 
    </tr>

    <? }

}

if(isset($_GET['action']) && $_GET['action']=='GetCustomerDetails')
{

	$cust_id = $_GET["rid"];
	
	$getCustomerData = select_query("select * from $db_name.customer_details where id='".$cust_id."' and is_active='1' order by id");
	
	$getCustomerModel = select_query("select * from $db_name.customer_model_master where cust_id='".$cust_id."' and is_active='1' order by id");					
	//echo "<pre>";print_r($getCustomerModel);die;
	
?>
<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>  
        <th>Name/ID</th>
        <th>Model Purchased</th>
        <th>Serial No</th>
        <th>Date of Installation</th>
        <th>Next AMC Month</th> 
    </tr>     
    </thead>    
    <tbody>
	<?	 $todaydate = date('Y-m-d');
	
		 for($i=0;$i<count($getCustomerModel);$i++) {
			 $nextAMCDate="";
			 if($getCustomerData[0]['date_of_installation'] != '0000-00-00' && $getCustomerData[0]['date_of_installation'] != '')
				{
					$installationDate = date("d/m/Y",strtotime($getCustomerData[0]['date_of_installation'])); 
				
				} else {
					$installationDate = '';
				}
				
				$no_of_month = 12;
				
				$amc_no_of_service = $getCustomerData[0]['amc_no_of_service'];
				
				$amc_month = $no_of_month/$amc_no_of_service;
				
				if($installationDate != "")
				{
					
					$monthdiff = dateDifference($getCustomerData[0]['date_of_installation'], $todaydate);
					
					if($monthdiff >= 0 && $monthdiff < $amc_month){ $addmonth = $amc_month;}
					else if($monthdiff >= $amc_month && $monthdiff < ($amc_month*2)){ $addmonth = ($amc_month*2);}
					else if($monthdiff >= ($amc_month*2) && $monthdiff < ($amc_month*3)){ $addmonth = ($amc_month*3);}
					else if($monthdiff >= ($amc_month*3) && $monthdiff < ($amc_month*4)){ $addmonth = ($amc_month*4);}
					else if($monthdiff >= ($amc_month*4) && $monthdiff < ($amc_month*5)){ $addmonth = ($amc_month*5);}
					else if($monthdiff >= ($amc_month*5) && $monthdiff < ($amc_month*6)){ $addmonth = ($amc_month*6);}
					
					$effectiveDate = date('Y-m-d', strtotime("+".$addmonth." months", strtotime($getCustomerData[0]['date_of_installation'])));
					
					$nextAMCDate = date('F Y', strtotime("-1 days", strtotime($effectiveDate)));
				}
	?>
 	<tr>
         <td><?=$getCustomerData[0]['name'].'/'.$getCustomerData[0]['cust_id'];?></td>
         
         <td><?=$getCustomerModel[$i]['model_purchased'];?></td>
         <td><?=$getCustomerModel[$i]['serial_no'];?></td>
         <td><?=$getCustomerData[0]['date_of_installation'];?></td>
         
         
         <td><?=$nextAMCDate;?></td>

    </tr>

    <? }

}

if(isset($_GET['action']) && $_GET['action']=='GetJobRequestDetails')
{

	$tech_id = $_GET["rid"];
	
	$get_job_data = select_query("select * from $db_name.all_job_details where id='".$tech_id."' $subUserCond order by id");
						
	//echo "<pre>";print_r($get_job_data);die;
	
?>
<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>  
        <th>Ticket ID</th>
        <th>Customer ID</th>
        <th>Customer Name</th>
        <th>Created On</th>
        <th>Service Type</th>
        <th>Call Type</th>
        <th>Product Group</th>
        <th>Model No</th>
        <th>Serial No</th>
        <th>In Voltage</th>
        <th>AMPS</th>
        <th>Room Temp</th>
        <th>Symptom</th>
       <!-- <th>Defect</th>-->
        <th>Action</th>
        <th>Work Type</th>
        <th>Cust Email ID</th>
        <th>Cust Phone No</th>
        <th>Cust Remark</th>
        <th>Purchase Date</th>
        <th>Tech Remark</th>
        <th>Amount To be Collect</th>
        <th>Priority</th>
        <th>Machine Capacity</th>
        <th>Working Hrs</th>
        <th>Job Assign</th>
        <th>Location In</th>
        <th>Job Closed</th>
        <th>Location</th>
        <th>Pin No</th>
    </tr>     
    </thead>    
    <tbody>
	<?
		 for($emp=0;$emp<count($get_job_data);$emp++) {
	?>
 	<tr>
          <td><?php echo $get_job_data[$emp]['ticket_no']; ?></td>  
          <td><?php echo $get_job_data[$emp]['customer_id']; ?></td>                      
          <td><?php echo $get_job_data[$emp]['customer_name']; ?></td>
          <td><?php echo $get_job_data[$emp]['request_date']; ?></td> 
          <td><?php echo $get_job_data[$emp]['service_type']; ?></td> 
          <td><?php echo $get_job_data[$emp]['call_type']; ?></td> 
          
          <td><?php echo $get_job_data[$emp]['product_group']; ?></td> 
          <td><?php echo $get_job_data[$emp]['model_no']; ?></td> 
          <td><?php echo $get_job_data[$emp]['serial_no']; ?></td> 
          <td><?php echo $get_job_data[$emp]['incoming_voltage']; ?></td>  
          <td><?php echo $get_job_data[$emp]['system_amps']; ?></td> 
          <td><?php echo $get_job_data[$emp]['room_temp']; ?></td>           
          <td><?php echo str_replace("~||~", ", ", $get_job_data[$emp]['symptom']); ?></td>
          <!--<td><?php echo $get_job_data[$emp]['defect']; ?></td>-->
          <td><?php echo $get_job_data[$emp]['action']; ?></td>
          <td><?php echo $get_job_data[$emp]['work_type']; ?></td> 
          <td><?php echo $get_job_data[$emp]['customer_email_id']; ?></td> 
          <td><?php echo $get_job_data[$emp]['customer_phone_no']; ?></td> 
          <td><?php echo $get_job_data[$emp]['customer_remark']; ?></td> 
          <td><?php echo $get_job_data[$emp]['purchase_date']; ?></td> 
          <td><?php echo $get_job_data[$emp]['technician_remark']; ?></td> 
          <td><?php echo $get_job_data[$emp]['amount_to_be_collected']; ?></td> 
          <td><?php echo $get_job_data[$emp]['priority_type']; ?></td> 
          <td><?php echo $get_job_data[$emp]['cubic_ft']; ?></td> 
          <td><?php echo $get_job_data[$emp]['total_working_hrs_req']; ?></td> 
          <td><?php echo $get_job_data[$emp]['job_assign_time']; ?></td>                   
          <td><?php echo $get_job_data[$emp]['location_in_time']; ?></td>
          <td><?php echo $get_job_data[$emp]['job_close_time']; ?></td>
          <td><?php echo $get_job_data[$emp]['job_location']; ?></td>
          <td><?php echo $get_job_data[$emp]['pin_code']; ?></td> 
    </tr>

    <? }

}

if(isset($_GET['action']) && $_GET['action']=='GetEmpOwnJob')
{

	$Phoneno = $_GET["Phoneno"];
	$emp_id = $_GET["rid"];
	$branch = $_GET["branch"];
	$startdate = $_GET["daterange"];
	
	$emp_own_job = select_query("SELECT * FROM $employee_track.request WHERE  emp_id='".$emp_id."' and phone_no='".$Phoneno."' and 
						(date(fromtime)='".$startdate."' or date(totime)='".$startdate."') and latitude is null 
						and longtitude is null and current_record=1 and login_id='".$branch."' ");
						
	//echo "<pre>";print_r($emp_track_data);die;
	
	$get_emp_data = select_query("SELECT emp_name FROM $employee_track.login_emp_details WHERE id='".$emp_id."' ");
?>
<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>  	
        <th>Job ID</th> 
        <th>Image</th>
        <th>Phone No/ Name</th>
        <th>Location</th> 
    </tr>     
    </thead>    
    <tbody>
	<?
		 for($i=0;$i<count($emp_own_job);$i++) {
	?>
 	<tr>
    	<td><?=$emp_own_job[$i]['job_id'];?></td> 
        <td><? if (isset($emp_own_job[$i]['image']) && !empty($emp_own_job[$i]['image']))?><img src="http://203.115.101.54/employeeTrackAPIV3/uploads/services/challan_image/<?=$emp_own_job[$i]['image']?>" width="50" height="50"></td>
        <td><?=$emp_own_job[$i]['phone_no'].'/ '.$get_emp_data[0]['emp_name'];?></td>
        <td><?=$emp_own_job[$i]['job_location'];?></td>
    </tr>

    <? }

}

if(isset($_GET['action']) && $_GET['action']=='GetEmpAssignJob')
{

	$Phoneno = $_GET["Phoneno"];
	$emp_id = $_GET["rid"];
	$branch = $_GET["branch"];
	$startdate = $_GET["daterange"];
	
		
	$emp_assign_job = select_query("SELECT * FROM $employee_track.request WHERE emp_id='".$emp_id."' and phone_no='".$Phoneno."' and 
						(date(fromtime)='".$startdate."' or date(totime)='".$startdate."') and latitude is not null 
						and longtitude is not null and current_record IN (0,1,3) and login_id='".$branch."' ");
											
	//echo "<pre>";print_r($emp_track_data);die;
	
	$get_emp_data = select_query("SELECT emp_name FROM $employee_track.login_emp_details WHERE id='".$emp_id."' ");
?>
<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>  
        <th>Job ID</th> 
        <th>Image</th>
        <th>Phone No/ Name</th>
        <th>Location</th> 
    </tr>     
    </thead>    
    <tbody>
	<?
		 for($i=0;$i<count($emp_assign_job);$i++) {
	?>
 	<tr>
    	<td><?=$emp_assign_job[$i]['job_id'];?></td> 
        <td><? if (isset($emp_assign_job[$i]['image']) && !empty($emp_assign_job[$i]['image']))?><img src="http://203.115.101.54/employeeTrackAPIV3/uploads/services/challan_image/<?=$emp_assign_job[$i]['image']?>" width="50" height="50"></td>
        <td><?=$emp_assign_job[$i]['phone_no'].'/ '.$get_emp_data[0]['emp_name'];?></td>
        <td><?=$emp_assign_job[$i]['job_location'];?></td>
    </tr>

    <? }

}

?>