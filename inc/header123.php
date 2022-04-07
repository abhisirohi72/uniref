<?php
error_reporting(0);
require('conn/config.php');
$count_contacts = mysql_query("SELECT * FROM contact_info WHERE user_id = '".$_SESSION['user_id']."'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Matrix Admin</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="css/fullcalendar.css" />
<link rel="stylesheet" href="css/matrix-style.css" />
<link rel="stylesheet" href="css/select2.css" />
<link rel="stylesheet" href="css/matrix-media.css" />
<link rel="stylesheet" href="css/matrix-style.css" />
<link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="css/jquery.gritter.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
<style>
.dropdown-menu > li > a {
padding: 3px 16px!important;
}
.muli-level{
  border-radius: 0 6px 6px 6px;
}
</style>
</head>
<body>

<!--Header-part-->
<div id="header">
  <a href="index.php"><img id="goto_home" src="img/logo.png" width="150px"></a>
</div>
<!--close-Header-part-->


<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <!--<li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Welcome User</span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="#"><i class="icon-user"></i> My Profile</a></li>
        <li class="divider"></li>
        <li><a href="#"><i class="icon-check"></i> My Tasks</a></li>
        <li class="divider"></li>
        <li><a href="login.html"><i class="icon-key"></i> Log Out</a></li>
      </ul>
    </li>-->
    <li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">My Contacts</span> <span class="label label-important"><?php echo mysql_num_rows($count_contacts);?></span> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sAdd" title="" href="contact-info.php"><i class="icon-plus"></i> Add new Contact </a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="my-contacts.php"><i class="icon-envelope"></i> View All Contacts</a></li>
        
      </ul>
    </li>
    
    
    <!-------------------------------------Multi submenu--------------------->   
   <li class="dropdown open" id="menu-cat"><a href="#" data-toggle="dropdown" data-target="#menu-cat" class="dropdown-toggle"><span class="text">Manage Category</span> <b class="caret"></b></a>
      	<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
           
		    <li class="dropdown-submenu">
		     <a tabindex="-1" href="#">Manage Category</a>
			  <ul class="dropdown-menu">
			   <li><a class="sAdd" title="" href="add-category.php"><i class="icon-plus"></i> Add new Category </a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="view-category.php"><i class="icon-envelope"></i> View All Categories</a></li>
        
				</ul>
			 </li>
		   
		   <li class="dropdown-submenu">
		     <a tabindex="-1" href="#">Manage Branch</a>
			  <ul class="dropdown-menu">
			   <li><a class="sAdd" title="" href="add-branch.php"><i class="icon-plus"></i> Add new Branch </a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="view-branch.php"><i class="icon-envelope"></i> View All Branches</a></li>
        
				</ul>
			 </li>
			 
			 
		    <li class="dropdown-submenu">
		     <a tabindex="-1" href="#">Manage Business Type</a>
			  <ul class="dropdown-menu">
			  <li><a class="sAdd" title="" href="add-business-type.php"><i class="icon-plus"></i> Add new Business Type </a></li>
				<li class="divider"></li>
				<li><a class="sInbox" title="" href="view-business-type.php"><i class="icon-envelope"></i> View All Business Type</a></li>
				</ul>
			 </li>
			
			
			 <li class="dropdown-submenu">
		     <a tabindex="-1" href="#">Manage Material Type</a>
			  <ul class="dropdown-menu">
			 <li><a class="sAdd" title="" href="add-material-type.php"><i class="icon-plus"></i> Add new Material Type </a></li>
			<li class="divider"></li>
			<li><a class="sInbox" title="" href="view-material-type.php"><i class="icon-envelope"></i> View All Material Type</a></li>
				</ul>
			 </li>



			 <li class="dropdown-submenu">
		     <a tabindex="-1" href="#">Manage Exception Type</a>
			  <ul class="dropdown-menu">
			 <li><a class="sAdd" title="" href="add-exception-type.php"><i class="icon-plus"></i> Add new Exception Type </a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="view-exception-type.php"><i class="icon-envelope"></i> View All Exception Type</a></li>
				</ul>
			 </li>


		
			 <li class="dropdown-submenu">
		     <a tabindex="-1" href="#">Manage Vehicle Type</a>
			  <ul class="dropdown-menu">
			  <li><a class="sAdd" title="" href="add-vehicle-type.php"><i class="icon-plus"></i> Add new Vehicle Type </a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="view-vehicle-type.php"><i class="icon-envelope"></i> View All Vehicle Type</a></li>
        
				</ul>
			 </li>

			
			
			<li class="dropdown-submenu">
		     <a tabindex="-1" href="#">Manage Party</a>
			  <ul class="dropdown-menu">
			<li><a class="sAdd" title="" href="add-party.php"><i class="icon-plus"></i> Add new Party </a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="view-party.php"><i class="icon-envelope"></i> View All Party</a></li>
        
				</ul>
			 </li>
			 
			 
			 <li class="dropdown-submenu">
		     <a tabindex="-1" href="#">Manage Third Party</a>
			  <ul class="dropdown-menu">
			 <li><a class="sAdd" title="" href="add-third-party.php"><i class="icon-plus"></i> Add new Third Party </a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="view-third-party.php"><i class="icon-envelope"></i> View All Third Party</a></li>
        
				</ul>
			 </li>
			 
			 <li class="dropdown-submenu">
		     <a tabindex="-1" href="#">Manage Route</a>
			  <ul class="dropdown-menu">
			 <li><a class="sAdd" title="" href="add-route.php"><i class="icon-plus"></i> Add new Route </a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="view-route.php"><i class="icon-envelope"></i> View All Route</a></li>
        
				</ul>
			 </li>
			 
           <!-- <li><a href="#">Some other action</a></li>
            <li class="divider"></li>
            <li class="dropdown-submenu">
           <a tabindex="-1" href="#">Hover me for more options</a>
                <ul class="dropdown-menu">
                  <li><a tabindex="-1" href="#">Second level</a></li>
                  <li class="dropdown-submenu"><a href="#">Even More..</a>
                    <ul class="dropdown-menu">
                        <li><a href="#">3rd level</a></li>
                    	<li><a href="#">3rd level</a></li>
                    </ul>
                  </li>
                  <li><a href="#">Second level</a></li>
                  <li><a href="#">Second level</a></li>
                </ul>
              </li>-->
            </ul>
    </li>
   <!-------------------------------------Muli submenu--------------------->  
   
    
   
   
    
  
    
    <li class="dropdown" id="menu-trip"><a href="#" data-toggle="dropdown" data-target="#menu-trip" class="dropdown-toggle"><i class="icon-check"></i> <span class="text">Trip</span> <span class="label label-important"><?php echo mysql_num_rows($count_contacts);?></span> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a title="" href="upload-trip.php"><i class="icon-arrow-up"></i> <span class="text">Upload Trip</span></a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="view-trip.php"><i class="icon-envelope"></i> View Trip</a></li>
        
      </ul>
    </li>
    
    
    
    <!--<li class=""><a title="" href="login.html"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>-->
  </ul>
</div>
<!--close-top-Header-menu-->
<!--start-top-serch-->
<div id="search">
  <input type="text" placeholder="Search here..."/>
  <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
</div>
<!--close-top-serch-->
<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
  <ul>
    <li class="active"><a href="index.html"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
    <li> <a href="charts.html"><i class="icon icon-signal"></i> <span>Charts &amp; graphs</span></a> </li>
    <li> <a href="widgets.html"><i class="icon icon-inbox"></i> <span>Widgets</span></a> </li>
    <li><a href="tables.html"><i class="icon icon-th"></i> <span>Tables</span></a></li>
    <li><a href="grid.html"><i class="icon icon-fullscreen"></i> <span>Full width</span></a></li>
    <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Forms</span> <span class="label label-important">3</span></a>
      <ul>
        <li><a href="form-common.html">Basic Form</a></li>
        <li><a href="form-validation.html">Form with Validation</a></li>
        <li><a href="form-wizard.html">Form with Wizard</a></li>
      </ul>
    </li>
    <li><a href="buttons.html"><i class="icon icon-tint"></i> <span>Buttons &amp; icons</span></a></li>
    <li><a href="interface.html"><i class="icon icon-pencil"></i> <span>Eelements</span></a></li>
    <li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>Addons</span> <span class="label label-important">5</span></a>
      <ul>
        <li><a href="index2.html">Dashboard2</a></li>
        <li><a href="gallery.html">Gallery</a></li>
        <li><a href="calendar.html">Calendar</a></li>
        <li><a href="invoice.html">Invoice</a></li>
        <li><a href="chat.html">Chat option</a></li>
      </ul>
    </li>
    <li class="submenu"> <a href="#"><i class="icon icon-info-sign"></i> <span>Error</span> <span class="label label-important">4</span></a>
      <ul>
        <li><a href="error403.html">Error 403</a></li>
        <li><a href="error404.html">Error 404</a></li>
        <li><a href="error405.html">Error 405</a></li>
        <li><a href="error500.html">Error 500</a></li>
      </ul>
    </li>
    <li class="content"> <span>Monthly Bandwidth Transfer</span>
      <div class="progress progress-mini progress-danger active progress-striped">
        <div style="width: 77%;" class="bar"></div>
      </div>
      <span class="percent">77%</span>
      <div class="stat">21419.94 / 14000 MB</div>
    </li>
    <li class="content"> <span>Disk Space Usage</span>
      <div class="progress progress-mini active progress-striped">
        <div style="width: 87%;" class="bar"></div>
      </div>
      <span class="percent">87%</span>
      <div class="stat">604.44 / 4000 MB</div>
    </li>
  </ul>
</div>
<!--sidebar-menu-->