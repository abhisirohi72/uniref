<?php 
include("config.php");
include("C:/xampp/htdocs/send_alert/phpmailer.lang-en.php");
include("C:/xampp/htdocs/send_alert/class.phpmailer.php");
include("C:/xampp/htdocs/send_alert/class.smtp.php");
include("send_email.php");
$sendMail= new SendEmail();
//get all today birthday data
$getDetails= select_query("SELECT id, name, email_id, MONTH(dob) AS month, DATE_FORMAT(dob, '%d') AS date FROM customer_details HAVING month= '".date("n")."' and date= '".date("d")."'");

foreach($getDetails as $getDetail){
	$msg= 'Hi '.$getDetail['name'].', Good day!<br><br>
			Happy birthday! Here’s to wishing you another prosperous year and mutual growth.<br><br>

			Please contact us on our 24*7 Toll Free No.  +91 7222001000   Or  Email us at Service.unirefindia@gmail.com<br><br>

			Assuring Our Best Service<br>
			Uniref India.';
	$sendMail->sendMail($getDetail['email_id'], $msg, "birthday");		
}


?>