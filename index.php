<?php 
session_start();
ob_start();
include("config.php");

//$user_id = $_SESSION['user_id'];
if(isset($_POST['login'])) {

$user_name = $_POST['user_name'];
$user_password = $_POST['user_password'];

$query_people = select_query("SELECT * FROM $db_name.login_details WHERE username='".$user_name."' and password='".$user_password."' and active_status=1 ");
//echo "<pre>";print_r($query_people);die;

if(count($query_people)>0){
		
	$_SESSION['id']=$query_people[0]['id'];
	$_SESSION['user_id']=$query_people[0]['login_id'];
	$_SESSION['id_roles']=$query_people[0]['id_roles'];
	$_SESSION['company']=$query_people[0]['username'];
	$_SESSION['active_status']=$query_people[0]['active_status'];
	if($_SESSION['id_roles']=="2"){
		$_SESSION['admin_user_id']="";
		//get admin userid
		$getAdminDetails= select_query("SELECT id FROM 	login_details WHERE id_roles = 1 ORDER BY id ASC LIMIT 1");
		if(count($getAdminDetails)>0){
			$_SESSION['admin_user_id']= $getAdminDetails[0]['id'];
		}
	}
	if($query_people[0]['id_roles']==1){
	
		header('Location: amc_reminder.php');
	
	} else {
		header('Location: amc_reminder.php');
	}
	
	
	/*echo "<script>alert('Welcome');</script>";*/



}else {

    echo "<script>alert('Not Valid User');</script>";

 }   

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
        <title>Uni-ref</title><meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/matrix-login.css" />
        <link href="<?php echo __SITE_URL;?>/font-awesome/css/font-awesome.css" rel="stylesheet" />
		<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>-->

    </head>
    <body>
        <div id="loginbox">            
            <form id="loginform" class="form-vertical" action="#" method="POST">
				 <div class="control-group normal_text"> <h3><img src="<?php echo __SITE_URL;?>/img/unireflogo.png" alt="Uniref" /></h3></div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_ls"><i class="icon-user"> </i></span><input type="text" name="user_name" placeholder="Username"  required="required" />
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_ls"><i class="icon-lock"></i></span><input type="password" name="user_password" placeholder="Password" required />
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <!-- <span class="pull-left"><a href="#" class="flip-link btn btn-info" id="to-recover">Lost password?</a></span> -->
                    <span class="pull-right"><button type="submit" class="btn btn-success login" name="login">Login</button>

                        <!--<a type="submit" href="#" name="login" class="btn btn-success" /> Login</a>--></span>
                </div>
            </form>
            <form id="recoverform" action="#" class="form-vertical">
				<p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p>
				
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on bg_lo"><i class="icon-envelope"></i></span><input type="text" placeholder="E-mail address" />
                        </div>
                    </div>
               
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-success" id="to-login">&laquo; Back to login</a></span>
                    <span class="pull-right"><a class="btn btn-info"/>Reecover</a></span>
                </div>
            </form>
        </div>
        
        <script src="<?php echo __SITE_URL;?>/js/jquery.min.js"></script>  
        <script src="<?php echo __SITE_URL;?>/js/matrix.login.js"></script> 
    </body>

</html>
