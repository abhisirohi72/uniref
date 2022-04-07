<?php

// example on using PHPMailer with GMAIL 

include("class.phpmailer.php");
include("class.smtp.php");
echo "Mail";
$mail=new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port 
$mail->Username   = "vineet@itglobalconsulting.com";  // GMAIL username
$mail->Password   = "info@123456";            // GMAIL password
$mail->From       = "replyto@yourdomain.com";
$mail->FromName   = "Vineet";
$mail->Subject    = "This is the subject";
$mail->Body       = "First Message From Vineet1 Test";                      //HTML Body
$mail->AltBody    = "First Message From Vineet2"; //Text Body
$mail->WordWrap   = 50; // set word wrap
$mail->AddAddress("vineet@itglobalconsulting.com","First Last");
$mail->AddReplyTo("support@g-trac.in","Webmaster");
 // attachment
$mail->IsHTML(true); // send as HTML
if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message has been sent";
}

?>