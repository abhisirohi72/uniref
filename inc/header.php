<?php
ob_start();
session_start();
error_reporting(0);
include("config.php");

if (isset($_SESSION['user_id'])) {

  $user_id=$_SESSION['user_id'];
  $comapny=$_SESSION['company'];
  $id_roles=$_SESSION['id_roles'];
  $active_status=$_SESSION['active_status'];

    
} else {
      
      header('Location:index.php');

}

$currentdate = date('Y-m-d');

$get_emp_data = select_query("SELECT count(id) as Total_notification FROM $db_name.all_job_details WHERE loginid='".$_SESSION['user_id']."' and to_technician is null  and job_status!=5 and is_active='1' and request_date<='".$currentdate."' ");
//echo __SITE_URL;die;
//$count_contacts = mysql_query("SELECT * FROM contacts_info WHERE user_id = '".$_SESSION['user_id']."'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Uni-ref</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/fullcalendar.css" />
<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/matrix-style.css" />
<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/select2.css" />
<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/matrix-media.css" />
<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/matrix-style.css" />
<link href="<?php echo __SITE_URL;?>/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/jquery.gritter.css" />

<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>-->
<style>
.dropdown-menu > li > a {
padding: 3px 16px!important;
}
.muli-level{
  border-radius: 0 6px 6px 6px;
}

.container{
	display:inline-block;
	width: 100px;
	height:100px;
	overflow:hidden;
}

.container img {
    display: block;
    width: 68%;
    height: auto;
}
</style>
</head>
<body>

<!--Header-part-->
<div id="header">
  <a href="index.php"><img id="goto_home" src="img/unireflogo12.png" width="170px"></a>
</div>


<!--<div id="header">
	<div class="container">
      <a href="<?php echo __SITE_URL;?>/index.php">
      	<img id="goto_home" src="img/uniledlogo.png">
      </a>
    </div>
  
</div>-->
<!--close-Header-part-->


<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
	
    <?php if($id_roles==1 || $id_roles==6 || $id_roles==2){?>
    
    <li class="dropdown" id="menu-main"><a href="#" data-toggle="dropdown" data-target="#menu-main" class="dropdown-toggle"> <i class="icon icon-envelope"></i> <span class="text">Uni-ref Start</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">

        <li><a class="sInbox" title="" href="amc_reminder.php"><i class="icon-search"></i> AMC Reminders</a></li>
        <!--<li class="divider"></li>-->

      </ul>
    </li>
	
	<li class="dropdown" id="menu-support1"><a href="#" data-toggle="dropdown" data-target="#menu-support1" class="dropdown-toggle"><i class="icon icon-bell"></i>&nbsp;New Notifications <span class="badge badge-success"><?=$get_emp_data[0]['Total_notification'];?></span><b class="caret"></b></a>
    
    <ul class="dropdown-menu">

        <li><a class="sInbox" title="" href="view-fsr-request-job.php"><i class="icon-arrow-up"></i> Customer Service/Complaint</a></li>

      </ul>
     
    </li>
    
	<li class="dropdown" id="menu-job"><a href="#" data-toggle="dropdown" data-target="#menu-job" class="dropdown-toggle"> <i class="icon icon-envelope"></i> <span class="text">Tickets</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">

        <li><a class="sInbox" title="" href="view-request-job.php"><i class="icon-arrow-up"></i> View Assign Ticket</a></li>
        <!--<li class="divider"></li>
        <li><a class="sInbox" title="" href="view-fsr-request-job.php"><i class="icon-arrow-up"></i> Customer Service/Complaint</a></li>-->

      </ul>
    </li>
    
    <li class="dropdown" id="menu-customers"><a href="#" data-toggle="dropdown" data-target="#menu-customers" class="dropdown-toggle"> <i class="icon icon-envelope"></i> <span class="text">Customers</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">

        <li><a class="sInbox" title="" href="view_customer.php"><i class="icon-arrow-up"></i> Add/ Modify/ Delete Customers</a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="customer_history.php"><i class="icon-arrow-up"></i> Customer History Report</a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="customer_notification.php"><i class="icon-arrow-up"></i> Customer Notification</a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="customer_rating.php"><i class="icon-arrow-up"></i> Customer Rating</a></li>

      </ul>
    </li>
	 
    <li class="dropdown" id="menu-technicians"><a href="#" data-toggle="dropdown" data-target="#menu-technicians" class="dropdown-toggle"> <i class="icon icon-envelope"></i> <span class="text">Technicians</span> <b class="caret"></b></a>
          <ul class="dropdown-menu">
    
            <li><a class="sInbox" title="" href="view_technicians.php"><i class="icon-arrow-up"></i> Add/ Modify/ Delete Technicians</a></li>
            <li class="divider"></li>
            <li><a class="sInbox" title="" href="technicians_leave_request.php"><i class="icon-arrow-up"></i> Technicians Leave Request</a></li>
            <li class="divider"></li>
            <li><a class="sInbox" title="" href="technicians_notification.php"><i class="icon-arrow-up"></i> Technicians Notification</a></li>
            <li class="divider"></li>
            <li><a class="sInbox" title="" href="technicians_job_history.php"><i class="icon-arrow-up"></i> Technicians Service History</a></li>
            <li class="divider"></li>
            <li><a class="sInbox" title="" href="technicians_attendance.php"><i class="icon-arrow-up"></i> Technicians Attendance</a></li>
            <li class="divider"></li>
            <li><a class="sInbox" title="" href="technicians_extra_expense.php"><i class="icon-arrow-up"></i> Technicians Extra Expense</a></li>
            <li class="divider"></li>
            <li><a class="sInbox" title="" href="technicians_tracking_view.php"><i class="icon-arrow-up"></i> Technicians Tracking</a></li>
    
          </ul>
        </li>
    
         
    <!--<li class="dropdown" id="menu-support"><a href="#" data-toggle="dropdown" data-target="#menu-support" class="dropdown-toggle"><i class="icon icon-user"></i> <span class="text"> <?php echo $comapny ?> </span><b class="caret"></b></a>
          
    	<ul class="dropdown-menu">

        <li><a class="sInbox" title="" href="view-support-record.php"><i class="icon-arrow-up"></i> Manage Support</a></li>
        <li class="divider"></li>
                
        <li><a class="sInbox" title="" href="view-ta-enquiry-record.php"><i class="icon-arrow-up"></i> Manage Extra Expense</a></li>

      </ul>
     
    </li>-->
    
	
    <!-- data-toggle="dropdown" data-target="#menu-support" -->
    <li class="dropdown" id="menu-support"><a href="https://gtrac.in/newtracking/index.php" target="_blank"  class="dropdown-toggle"><i class="icon icon-user"></i> <span class="text"> <?php echo $comapny ?> </span></a>
    </li>
    
    
    <?php } ?>
	<li class="dropdown" id="menu-subUsers">
		<a href="#" data-toggle="dropdown" data-target="#menu-subUsers" class="dropdown-toggle"> 
			<i class="icon icon-envelope"></i> 
			<span class="text">Sub Users</span> 
			<b class="caret"></b>
		</a>
         <ul class="dropdown-menu">
			<li>
				<a class="sInbox" title="" href="view_sub_users.php">
					<i class="icon-arrow-up"></i> 
					Add/ Modify/ Delete Sub Users
				</a>
			</li>
		</ul>
	</li>
    
    <li class=""><a title="" href="logout.php"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>    
    
    
    <!--<li class=""><a title="" href="login.html"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>-->
  </ul>
</div>
<!--close-top-Header-menu-->
<!--start-top-serch-->
<!--<div id="search">
  <input type="text" placeholder="Search here..."/>
  <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
</div>-->
<!--close-top-serch-->
<!--sidebar-menu-->

<!--sidebar-menu-->