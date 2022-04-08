<?php 
include("config.php");
include("C:/xampp/htdocs/send_alert/phpmailer.lang-en.php");
include("C:/xampp/htdocs/send_alert/class.phpmailer.php");
include("C:/xampp/htdocs/send_alert/class.smtp.php");
include("send_email.php");
$sendMail= new SendEmail();
//get mail before 15 days
$currentDate= date("Y-m-d");
//add 15 days in current date
$addFifteenDay= date('Y-m-d', strtotime('+15 day', strtotime($currentDate)));
// exit;
$getDetails= select_query("SELECT id, name, email_id, warranty_month FROM customer_details WHERE amc_tenure_to= '".$addFifteenDay."'");
if(count($getDetails)>0){
	foreach($getDetails as $getDetail){
		$msg= 'Hi '.$getDetail['name'].', Good day!<br>
				Thank you for your continued support and belief in our product. Although it has already been '.$getDetail['warranty_month'].' months that we have been honored to serve you, it is time to renew your AMC contract with our company.<br>
				please contact us on our 24*7 Toll Free No.  +91 7222001000   Or  Email us at  Service.unirefindia@gmail.com<br><br>
				Assuring Our Best Service<br>
				Uniref India.';
		$sendMail->sendMail($getDetail['email_id'], $msg);		
	}
}

//add 10 days in current date
$addTenDay= date('Y-m-d', strtotime('+10 day', strtotime($currentDate)));

// exit;
$getDetails= select_query("SELECT id, name, email_id, warranty_month FROM customer_details WHERE amc_tenure_to= '".$addTenDay."'");
if(count($getDetails)>0){
	foreach($getDetails as $getDetail){
		$msg= 'Hi '.$getDetail['name'].', Good day!<br>
				Thank you for your continued support and belief in our product. Although it has already been '.$getDetail['warranty_month'].' months that we have been honored to serve you, it is time to renew your AMC contract with our company.<br>
				please contact us on our 24*7 Toll Free No.  +91 7222001000   Or  Email us at  Service.unirefindia@gmail.com<br><br>
				Assuring Our Best Service<br>
				Uniref India.';
		$sendMail->sendMail($getDetail['email_id'], $msg);		
	}
}

//add 5 days in current date
$addFiveDay= date('Y-m-d', strtotime('+5 day', strtotime($currentDate)));

// exit;
$getDetails= select_query("SELECT id, name, email_id, warranty_month FROM customer_details WHERE amc_tenure_to= '".$addFiveDay."'");
if(count($getDetails)>0){
	foreach($getDetails as $getDetail){
		$msg= 'Hi '.$getDetail['name'].', Good day!<br>
				Thank you for your continued support and belief in our product. Although it has already been '.$getDetail['warranty_month'].' months that we have been honored to serve you, it is time to renew your AMC contract with our company.<br>
				please contact us on our 24*7 Toll Free No.  +91 7222001000   Or  Email us at  Service.unirefindia@gmail.com<br><br>
				Assuring Our Best Service<br>
				Uniref India.';
		$sendMail->sendMail($getDetail['email_id'], $msg);		
	}
}


?>