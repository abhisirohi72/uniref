<?php 
include('inc/header.php');

$user_id = $_SESSION['user_id'];
include("C:/xampp/htdocs/send_alert/phpmailer.lang-en.php");
include("C:/xampp/htdocs/send_alert/class.phpmailer.php");
include("C:/xampp/htdocs/send_alert/class.smtp.php");
function send_notification_android2($tokens,$message,$androidkey)
{
  $url = 'https://fcm.googleapis.com/fcm/send';
  $fields = array(
	'registration_ids' => $tokens,
	'data' => $message
   );
  $headers = array(
   'Authorization:key = '.$androidkey,
   'Content-Type: application/json'
   );   
   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_POST, true);
	   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	   $result = curl_exec($ch);           
	   if ($result === FALSE) {
		   die('Curl failed: ' . curl_error($ch));
	   }
	   curl_close($ch);
	   return $result;
}

function send_ios_notification ($tokens,$message,$API_ACCESS_KEY)
{
	  $url = 'https://fcm.googleapis.com/fcm/send';
	  
	  $fields = array(
		  'registration_ids'    => $tokens,
		  'data'                => $message,
		  'delay_while_idle'    => false,
		  'priority'            => 'high',
		  'content_available'   => true,
		  'notification'        => $message
		);
		
	  $headers = array(
	   'Authorization:key = '.$API_ACCESS_KEY,
	   'Content-Type: application/json'
	   );
	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_POST, true);
	   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	   $result = curl_exec($ch);           
	   if ($result === FALSE) {
		   die('Curl failed: ' . curl_error($ch));
	   }
	   curl_close($ch);
	   return $result;
	   //return $fields;
}
function sendMail($email,$msg) {
	// $email="ankur@g-trac.in,priya@g-trac.in,harish@g-trac.in";
	$email = "abhishek@gtrac.in";//abhishek
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
		 
		 $textTosend.= $msg;
		 
		 /*$textTosend .="Dear Recipients,<br/><br/><br/>The current temperature of following chambers are out of range and require attention:<br/>".$msg;*/
		 $mail->IsHTML(true);
		 //$mail->AddAttachment(__DOCUMENT_ROOT . '/reports/excel_reports/IdleMahaveera' . date("Y-m-d") . ".xls", 'IdleDaily_Report.xls');
		 $mail->Body = $textTosend . " <br/><br/>UNI-REF <br/>Jaipur (India)";
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
if (isset($_POST['save_people'])) {
	
	//echo "<pre>";print_r($_POST);die;
	
	$tech_nameData = $_POST['tech_name'];
	$tech_nameSplit = explode("##",$tech_nameData);
	
	$tech_id = $tech_nameSplit[0];
	$phone_no = $tech_nameSplit[1];
	
	$message = $_POST['message'];
	
	if($_POST['from_date']!='' && $_POST['to_date']!='')
	{
		$from_date = $_POST['from_date'];
		$to_date = $_POST['to_date'];
	}
	else if($_POST['from_date']!='' && $_POST['to_date']=='')
	{
		$from_date = $_POST['from_date'];
		$to_date = $_POST['from_date'];
	}
	else if($_POST['from_date']=='' && $_POST['to_date']!='')
	{
		$from_date = $_POST['to_date'];
		$to_date = $_POST['to_date'];
	}
	else if($_POST['from_date']=='' && $_POST['to_date']=='')
	{
		$from_date = date("Y-m-d");
		$to_date = date("Y-m-d");
	}
	
	
	/*if($_POST['from_date']==''){$from_date = date("Y-m-d");}else{$from_date = $_POST['from_date'];}	
	
	if($_POST['to_date']==''){$to_date = date("Y-m-d");}else{$to_date = $_POST['to_date'];}	*/
	
		
	$insert_query = insert_query($db_name.'.push_notification', array('person_id' => $tech_id, 'phone_no' => $phone_no , 
		'message' => $message , 'from_date' => $from_date, 'to_date' => $to_date));
		
	//get user email 
	$custData = select_query("SELECT email_id FROM $db_name.customer_details where is_active='1' and cust_id='".$cust_id."' ");
	if(count($custData)>0){
		foreach($custData as $custData);
		//mail send 
		sendMail($custData['email_id'],$message);	
	}	
	
	if($phone_no == "All")
	{
		$techData = select_query("SELECT mobile_no FROM $db_name.technicians_login_details where is_active='1'");
		for($ecl=0;$ecl<count($techData);$ecl++)
		{
			$tech_PhoneNo .= $techData[$ecl]['mobile_no'].",";
		}
		$tech_PhoneNo = substr($tech_PhoneNo,0,strlen($tech_PhoneNo)-1);
		
		$tokenResult = select_query("SELECT device_key as token FROM $db_name.installer_app_verify where phone_no IN (".$tech_PhoneNo.") and is_active='1'");
		//echo "<pre>";print_r($tokenResult);die;
		if(count($tokenResult)>0)
		{
			for($npl=0;$npl<count($tokenResult);$npl++)
			{
				$tokens[] = $tokenResult[$npl]['token'];
		
				/*$Notificato_msg = array("data" => "You have got New Notification. Please Check in Application");
		 
				$androidkey = "AAAAityYBUo:APA91bHqlslQqmabKf60tA5oag-k8AmZ4HYezea4P3utHDsZZEeDe9hLL1nenM_MAJdIZPY1Ou8oeOKGK47KwpP7KuUm7KPNCMPmKlQZSa-jcIx0uD9Cu-b3lpBXwPJK_nEOjEc1NrNc";
		
				$message_status = send_notification_android2($tokens,$Notificato_msg,$androidkey);*/
				
				if($tokenResult[$npl]['device_name'] == "IOS")
				{
					 $Notificato_msg = array('body'    => 'You have got New Notification. Please Check in Application',
											  'title'   => 'Technician New Notification',
											);
					  
					 $API_ACCESS_KEY = "AAAA7NXaEQw:APA91bFDi-bgj-sxloGpd1hUhxscejG2KonWaWa1_gZSK5arSV8hwGJNXcg96lXZiwfAFKeQOkY2QVePjxgVoh6YWbnnDNfc7jRIOzPFnKh3Z0AVow6VTwXA5vkvNN0ob7EFPj_GKSqH";
					 $message_status = send_ios_notification($tokens,$Notificato_msg,$API_ACCESS_KEY);
					
				} else {
					
					$Notificato_msg = array("data" => "You have got New Notification. Please Check in Application");
			 
					$androidkey = "AAAAityYBUo:APA91bHqlslQqmabKf60tA5oag-k8AmZ4HYezea4P3utHDsZZEeDe9hLL1nenM_MAJdIZPY1Ou8oeOKGK47KwpP7KuUm7KPNCMPmKlQZSa-jcIx0uD9Cu-b3lpBXwPJK_nEOjEc1NrNc";			
					$message_status = send_notification_android2($tokens,$Notificato_msg,$androidkey);
				
				}
			}
		}
		
			
	} else {
		
		$tokenResult = select_query("SELECT device_key as token FROM $db_name.installer_app_verify where phone_no='".$phone_no."' and is_active='1' order by id desc limit 0,1");
		
		if(count($tokenResult)>0)
		{
			$tokens[] = $tokenResult[0]['token'];
	
			/*$Notificato_msg = array("data" => "You have got New Notification. Please Check in Application");
	 
			$androidkey = "AAAAityYBUo:APA91bHqlslQqmabKf60tA5oag-k8AmZ4HYezea4P3utHDsZZEeDe9hLL1nenM_MAJdIZPY1Ou8oeOKGK47KwpP7KuUm7KPNCMPmKlQZSa-jcIx0uD9Cu-b3lpBXwPJK_nEOjEc1NrNc";
	
			$message_status = send_notification_android2($tokens,$Notificato_msg,$androidkey);*/
			
			if($tokenResult[0]['device_name'] == "IOS")
			{
				 $Notificato_msg = array('body'    => 'You have got New Notification. Please Check in Application',
										  'title'   => 'Technician New Notification',
										);
				  
				 $API_ACCESS_KEY = "AAAA7NXaEQw:APA91bFDi-bgj-sxloGpd1hUhxscejG2KonWaWa1_gZSK5arSV8hwGJNXcg96lXZiwfAFKeQOkY2QVePjxgVoh6YWbnnDNfc7jRIOzPFnKh3Z0AVow6VTwXA5vkvNN0ob7EFPj_GKSqH";
				 $message_status = send_ios_notification($tokens,$Notificato_msg,$API_ACCESS_KEY);
				
			} else {
				
				$Notificato_msg = array("data" => "You have got New Notification. Please Check in Application");
		 
				$androidkey = "AAAAityYBUo:APA91bHqlslQqmabKf60tA5oag-k8AmZ4HYezea4P3utHDsZZEeDe9hLL1nenM_MAJdIZPY1Ou8oeOKGK47KwpP7KuUm7KPNCMPmKlQZSa-jcIx0uD9Cu-b3lpBXwPJK_nEOjEc1NrNc";			
				$message_status = send_notification_android2($tokens,$Notificato_msg,$androidkey);
			
			}
				
		}
		
	}
		
	if($insert_query) {

		echo "<script>window.location.href='technicians_notification.php'</script>";

		$_SESSION['success_msg'] = 'set';
	
	}

	

}
?>

<link rel="stylesheet" href="<? echo __SITE_URL?>/css/bootstrap-select.css">

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="technicians_notification.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Add Notification</a> </div>
    
  </div>
  <div class="container-fluid">
   
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add Notification</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="myForm" id="myForm" action="" method="post" class="form-horizontal" autocomplete="off">
              <div class="alert alert-error error_display" style="display:none">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span id="print_err"></span>
			  </div>
			  
			  <?php if(isset($_SESSION['success_msg'])) {  ?>
				<div class="alert alert-success success_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Success!</strong><span> Succesfully added. Click <a href="technicians_notification.php">here</a> to View</span>
			  </div>
			  <?php } else if(isset($_SESSION['unsuccess_msg'])) {  ?>
				<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> Notification Already Exist. </span>
			  </div>
			  <?php } 
			  unset($_SESSION['success_msg']);
			  unset($_SESSION['unsuccess_msg']);
			  
			  $get_tech_recd = select_query("SELECT id,concat(emp_name,' / ',mobile_no) as tech_name,mobile_no  FROM 
			  $db_name.technicians_login_details WHERE is_active='1'");
			  
			  ?>
			  
			   <div class="control-group">
                <label class="control-label">To Technician:</label>
                <div class="controls"> 
                    <div class="col-sm-3">
                        <select class="selectpicker pull-left sepratesize" data-live-search="true" title="Select To Technician" name="tech_name" id="tech_name">
                          <option value="All##All">All</option>
                        <?php for($tr=0;$tr<count($get_tech_recd);$tr++) { ?>
                          <option value="<?=$get_tech_recd[$tr]['id']."##".$get_tech_recd[$tr]['mobile_no'];?>"><?=$get_tech_recd[$tr]['tech_name'];?></option>
                        <? } ?>
                        </select>
                        <span id="branch_error"></span> </div>
                  </div>
              </div>
 								
			 <div class="control-group">
                <label class="control-label">Message:</label>
                <div class="controls">
                  <textarea name="message" id="message" placeholder="Message *"></textarea>
                  <span id="branch_error"></span> </div>
              </div>
              
                            
              <div class="control-group">
                <label class="control-label">From Date:</label>
                <div class="controls date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="mandatory date-picker" name="from_date" id="from_date" type="text" value="" placeholder="From Date" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                
              </div>
             
             <div class="control-group">
                <label class="control-label">To date:</label>
                <div class="controls date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="mandatory date-picker" name="to_date" id="to_date" type="text" value="" placeholder="To date " readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                
                <!--<div class="controls">
                  <input type="text" name="joining_date" id="joining_date" class="mandatory" placeholder="Date of Joining *" />
                  <span id="branch_error"></span> </div>-->
              </div>
              			  
              <div class="form-actions">
                <button type="submit" class="btn-harish btn-info-harish save_step_1" name="save_people">Save</button>
                <a  class="btn-harish btn-info-harish" href="technicians_notification.php" style="color: #fff;">Cancel</a>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
  
<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/libs/bootstrap/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script>
	  
$( document ).ready(function(){

////////////////////////// Validation ////////////////////////
	
    $('.save_step_1').click(function(e) {
		//alert('Hii');
		
		var tech_name = $("#tech_name").val();
		//alert(phone_number);
		if( tech_name == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select To Technician.");
			return false;
		}
		
		var message = $("#message").val();
		if( message == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Notification Message.");
			return false;
		}
		
		var fromDate = $("#from_date").val();
		var toDate = $("#to_date").val();
		//alert(fromDate+ '--' +toDate);
		/*if(fromDate == '' && fromDate == null) {
			
			$(".error_display").css("display","block");
			$("#print_err").html(" Place Select From Date.");
			return false;
			
		}
		
		if(toDate == '' && toDate == null) {
			
			$(".error_display").css("display","block");
			$("#print_err").html(" Place Select To Date.");
			return false;
			
		}*/
				
		if((fromDate != '' && toDate != '') && (fromDate != null && toDate != null)) {
			
			if(fromDate>toDate)
			{
			    $(".error_display").css("display","block");
				$("#print_err").html(" To Date always greater then or same From Date.");
				return false;
			}
			
		}
				
		
	   
    });
	
	

////////////////////////// Validation ////////////////////////
	
});

</script>


<link href="<?php echo __SITE_URL;?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script src="<? echo __SITE_URL?>/js/bootstrap-select.js"></script>  
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
    });
    $('.form_date').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
    });
    $('.form_time').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0,
		
    });

</script>
<?php include('inc/footer.php');?>
