<?php 
class SendEmail{
	function sendMail($email,$msg, $service_type="") {
		// $email="ankur@g-trac.in,priya@g-trac.in,harish@g-trac.in";
		$email = "abhisirohi72@gmail.com";//abhishek
		if($msg!="")
		{
			$mail=new PHPMailer();
			$Subject=" Uni-Ref Customer Notification";
			$mail->IsSMTP();
			$mail->SMTPAuth   = true;     // enable SMTP authentication
			$mail->SMTPSecure = "ssl";    // sets the prefix to the servier
			$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
			$mail->Port       = 465;      // set the SMTP port
			//$mail->Username   = "priya@g-trac.in";  // GMAIL username
			//$mail->Password   = "manash4u2";   // GMAIL password
			$mail->Username   = "unirefservices@gmail.com";  // GMAIL username
			$mail->Password   = "U12345678!";   // GMAIL password

			//$mail->Username   = "anoop@g-trac.in";  // GMAIL username
			// $mail->Password   = "omsairam";   // GMAIL password

			$mail->From       = "info@g-trac.in";
			$mail->FromName   = "G-trac";
			//$mail->Body       = $message;//HTML Body
			$mail->AltBody    = ""; //Text Body
			$mail->WordWrap   = 50; // set word wrap

			$mail->AddReplyTo("sarvottma@gtrac.in","G-trac");
			$mail->IsHTML(true);


			$mail->Subject    = $Subject;


			//echo "sdfsdf";
			$arremail1=explode(",",$email);
			//print_r($arremail1);die();

			for($ec=0;$ec<count($arremail1);$ec++)
			{
				$mail->AddAddress($arremail1[$ec],$arremail1[$ec]);
			}

			//$mail->AddAddress($email,"email");
			//$mail->AddCC("harish@g-trac.in","G-Trac");
			$mail->AddCC("unirefindia@gmail.com","Uniref");

			//$textTosend.= $msg;

			/*$textTosend .="Dear Recipients,<br/><br/><br/>The current temperature of following chambers are out of range and require attention:<br/>".$msg;*/
			$mail->IsHTML(true);
			//$mail->AddAttachment(__DOCUMENT_ROOT . '/reports/excel_reports/IdleMahaveera' . date("Y-m-d") . ".xls", 'IdleDaily_Report.xls');
			if($service_type=="birthday"){
				$mail->addAttachment("mail_attachments/bday.gif");
			}
			$mail->Body = $msg . " <br/><br/>UNI-REF <br/>Jaipur (India)";
			if(!$mail->send()) 
			{
				echo "Mailer Error: " . $mail->ErrorInfo;
			} 
			else 
			{
				$mail->ClearAddresses();
				$mail->ClearAttachments();
				echo "Message has been sent successfully";
			}

		}
	 
	}
}
?>