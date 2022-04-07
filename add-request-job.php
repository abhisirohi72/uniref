<?php 
include('inc/header.php');

$user_id = $_SESSION['user_id'];

include("C:/xampp/htdocs/send_alert/class.phpmailer.php");
include("C:/xampp/htdocs/send_alert/class.smtp.php");

function googlatlang($address)
{
	$url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=AIzaSyCMj-azrdqWrM3CypbBoVobpSg7XkUMKHA";
	
	//AIzaSyCl3Ipc_2M0wZ6kTP4Z7Z0Q1xa2rukzt6E
		
	// Make the HTTP request
	$data = @file_get_contents($url);

	// Parse the json response
	$jsondata = json_decode($data,true);

	$lat = $jsondata["results"][0]["geometry"]["location"]["lat"];
	$lng = $jsondata["results"][0]["geometry"]["location"]["lng"];
	/*echo "<script type='text/javascript'>alert('".$lat."');</script>";*/
  return $lat."@".$lng;
  
}

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

function array_msort($array, $cols)
{
	$colarr = array();
	foreach ($cols as $col => $order) {
		$colarr[$col] = array();
		foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
	}

	$eval = 'array_multisort(';
	foreach ($cols as $col => $order) {
		$eval .= '$colarr[\''.$col.'\'],'.$order.',';
	}

	$eval = substr($eval,0,-1).');';
	eval($eval);
	$ret = array();
	 
	foreach ($colarr as $col => $arr) {
	  foreach ($arr as $k => $v) {
		   $k = substr($k,1);
		   if (!isset($ret[$k])) $ret[$k] = $array[$k];
		   $ret[$k][$col] = $array[$k][$col];
		  }
	}
	
	return $ret;
}

function sendMail($email,$msg,$TicketNo) {
   // $email="ankur@g-trac.in,priya@g-trac.in,harish@g-trac.in";

	if($msg!="")
	{
		$mail=new PHPMailer();
		$Subject=" Uni-Ref Ticket Request -".$TicketNo;
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
		//$mail->AddCC("service.unirefindia@gmail.com","Uniref");
		
		$textTosend.= $msg;
		
		/*$textTosend .="Dear Recipients,<br/><br/><br/>The current temperature of following chambers are out of range and require attention:<br/>".$msg;*/
		$mail->IsHTML(true);
		//$mail->AddAttachment(__DOCUMENT_ROOT . '/reports/excel_reports/IdleMahaveera' . date("Y-m-d") . ".xls", 'IdleDaily_Report.xls');
		$mail->Body = $textTosend . " <br/><br/>UNI-REF <br/>Jaipur (India)";
		$mail->Send();
		$mail->ClearAddresses();
		$mail->ClearAttachments();
	
	}
	
}
	
//$get_sup_no = select_query("SELECT support_number FROM $db_name.login_details WHERE id='".$_SESSION['user_id']."' ");

if (isset($_POST['save_people'])) {
	
	//echo "<pre>";print_r($_POST);die;
	
	$cust_nameData = $_POST['cust_name'];
	$cust_nameSplit = explode("##",$cust_nameData);
		
	$cust_name = $cust_nameSplit[0];
	$cust_id = $cust_nameSplit[1];
	
	$tech_id = $_POST['tech_name'];	
	//$req_date = $_POST['fromtime'];	
	$location = $_POST['location'];
	
	$pin_code = $_POST['pin_code'];
	$service_type = $_POST['service_type'];
	$work_type = $_POST['work_type'];
	$call_type = $_POST['call_type'];
	$product_group = $_POST['product_group'];
	
	$model_no = $_POST['model_no'];
	$serial_no = $_POST['serial_no'];
	
	if($service_type == "Installation") {
		
		$symptom = "Installation";
		$priority_type = "High";
		
	} else {
	
		$symptom = $_POST['symptom'];
		$priority_type = $_POST['priority_type'];
	
	}
	//$defect = $_POST['defect'];
	
	
	//$cubic_ft = $_POST['cubic_ft'];
	$cust_email_id = $_POST['cust_email_id'];
	
	$cust_phone_no = $_POST['cust_phone_no'];
	$amount_to_be_collected = $_POST['amount_to_be_collected'];
	$total_working_hrs = $_POST['total_working_hrs'];
	
	if($_POST['fromtime']!='')
	{
		$req_date = $_POST['fromtime'];
	}
	else if($_POST['fromtime']=='')
	{
		$req_date = date("Y-m-d");
	}
	
	$todayDate = date("Y-m-d");
	
	
	$getCustId = select_query("SELECT id,cust_id,name,amc_service_done FROM $db_name.customer_details where cust_id='".$cust_id."' and 
	is_active='1' and loginid='".$_SESSION['user_id']."' ");
	//echo "<pre>";print_r($getCustId);die;
	$jobId = "M".date("dmy")."0".$getCustId[0]['id']."FR".($getCustId[0]['amc_service_done']+1);
		
	$locationcheck = select_query("SELECT * from $db_name.location WHERE location like '".$location."%' LIMIT 1");
	//print_r($locationcheck);die;
	//echo $locationcheck[0]->latitude;die;
	
	if(count($locationcheck) > 0){
		
		$lat = $locationcheck[0]['latitude'];
		$lng = $locationcheck[0]['longitude'];
	
	} else{
		
		$address=str_replace(' ', '%20',$location);
		
		$latlng = googlatlang($address);
		//echo $latlng;die;
		$splitlatlng = explode("@", $latlng);

		$lat = (float)$splitlatlng[0];
		$lng = (float)$splitlatlng[1];
		
		$insert_lat_long = array('latitude' => $lat , 'longitude' => $lng, 'location' => $location, 'phone_no' => $cust_phone_no);
		$insert_loc = insert_query($db_name.'.location', $insert_lat_long);
		
		/*$lat = '00.0000000';
		$lng = '00.0000000';*/
		
	}
		
	$cur_date= date('Y-m-d 00:00:00');
	
	$duplicate_chk = select_query("SELECT * FROM $db_name.all_job_details WHERE customer_id='".$cust_id."' and ticket_no='".$jobId."' and 
		request_date='".$req_date."' and to_technician='".$tech_id."' and loginid='".$_SESSION['user_id']."' and is_active='1' ");
	//echo "<pre>";print_r($duplicate_chk);die;
		
		
	if(count($duplicate_chk)>0)
	{
		$_SESSION['unsuccess_msg'] = 'set';
		
	} else {			
			//'cubic_ft' => $cubic_ft, 
			
			$insert_array = array('ticket_no' => $jobId , 'customer_id' => $cust_id, 'customer_name' => $cust_name, 'request_date' => $req_date,
			'to_technician' => $tech_id, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'pin_code' => $pin_code,
			'service_type' => $service_type, 'call_type' => $call_type, 'product_group' => $product_group, 'model_no' => $model_no, 
			'serial_no' => $serial_no, 'symptom' => $symptom,  'work_type' => $work_type, 'priority_type' => $priority_type, 
			'customer_email_id' => $cust_email_id, 'customer_phone_no' => $cust_phone_no,
			'amount_to_be_collected' => $amount_to_be_collected, 'total_working_hrs_req' => $total_working_hrs,
			'job_assign_time' => date("Y-m-d H:i:s"),  'job_status' => 0,  'loginid' => $_SESSION['user_id']);
			
			$insert_query = insert_query($db_name.'.all_job_details', $insert_array);
			
			
			$update_warranty = array('date_of_installation' => $req_date);
			
			$condition = array('id' => $getCustId[0]['id'], 'loginid' => $_SESSION['user_id'], 'is_active' => 1);            
			update_query($db_name.'.customer_details', $update_warranty, $condition);
		
			
			$techData = select_query("SELECT * FROM $db_name.technicians_login_details WHERE id='".$tech_id."' and 
									  loginid='".$_SESSION['user_id']."' and is_active='1' ");
			
			$textTosend.='Hi '.$cust_name.',<br>';
			$textTosend.='<br>Your '.$service_type.' Request has been Received for '.date("dS F Y",strtotime($req_date)).'.<br>';
			$textTosend.= $techData[0]['emp_name'].'(+91'.$techData[0]['mobile_no'].') has been assigned to your Service Booking.<br> For Any Help, Please go to your Application and view your status for better experience.<br>';
			/*$textTosend.='UNI-REF<br>';
			$textTosend.='Jaipur(India)';*/
			
			sendMail($cust_email_id,$textTosend,$jobId);
			
			$msgtxt = "Hi ".$cust_name.", Your ".$service_type." request has been received ".date("dS F Y",strtotime($req_date)).". ".$techData[0]['emp_name']."(+91".$techData[0]['mobile_no'].") has been assigned to your Service Booking";
			
			$msgtxt_tech = "Hi ".$techData[0]['emp_name'].", One Service request has been assigned to you on ".date("dS F Y",strtotime($req_date)).".";
			
			$insert_notification_array = array('person_id' => $cust_id , 'phone_no' => $cust_phone_no, 'message' => $msgtxt, 
			'from_date' => $req_date, 'to_date' => $req_date, 'loginid' => $_SESSION['user_id']);
			
			insert_query($db_name.'.cust_push_notification', $insert_notification_array);
			
			$insert_tech_notification_array = array('person_id' => $tech_id , 'phone_no' => $techData[0]['mobile_no'], 'message' => $msgtxt_tech, 
			'from_date' => $req_date, 'to_date' => $req_date, 'loginid' => $_SESSION['user_id']);
			
			insert_query($db_name.'.push_notification', $insert_tech_notification_array);
			
			$ch = curl_init();
			$user = "gary@itglobalconsulting.com:it%40%23%242498";
			$PhoneNumber = $cust_phone_no;
			//$PhoneNumber="8527050111";
			$senderID = "GTRACK";
			curl_setopt($ch, CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$PhoneNumber&msgtxt=$msgtxt");
			$buffer = curl_exec($ch);
			
			$ch1 = curl_init();
			$user1 = "gary@itglobalconsulting.com:it%40%23%242498";
			$TechPhoneNumber=$techData[0]['mobile_no'];
			$senderID1 = "GTRACK";
						
			curl_setopt($ch1, CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch1, CURLOPT_POST, 1);
			curl_setopt($ch1, CURLOPT_POSTFIELDS, "user=$user1&senderID=$senderID1&receipientno=$TechPhoneNumber&msgtxt=$msgtxt_tech");
			$buffer1 = curl_exec($ch1);
						
			
			$Technicin_tokenResult = select_query("SELECT device_key as token FROM $db_name.installer_app_verify where phone_no='".$techData[0]['mobile_no']."' and is_active='1' order by id desc limit 0,1");
			//echo "<pre>";print_r($tokenResult);die;
			
			if(count($Technicin_tokenResult)>0)
			{
				$tokens[] = $Technicin_tokenResult[0]['token'];
				
				if($Technicin_tokenResult[0]['device_name'] == "IOS")
				{
					 $Notificato_msg = array('body'    => 'New Job Allocated. Please Refresh Application',
											  'title'   => 'Technician New Job',
											);
					  
					 $API_ACCESS_KEY = "AAAA7NXaEQw:APA91bFDi-bgj-sxloGpd1hUhxscejG2KonWaWa1_gZSK5arSV8hwGJNXcg96lXZiwfAFKeQOkY2QVePjxgVoh6YWbnnDNfc7jRIOzPFnKh3Z0AVow6VTwXA5vkvNN0ob7EFPj_GKSqH";
					 $message_status = send_ios_notification($tokens,$Notificato_msg,$API_ACCESS_KEY);
					
				} else {
					
					$Notificato_msg = array("data" => "New Job Allocated. Please Refresh Application");
			 
					$androidkey = "AAAAityYBUo:APA91bHqlslQqmabKf60tA5oag-k8AmZ4HYezea4P3utHDsZZEeDe9hLL1nenM_MAJdIZPY1Ou8oeOKGK47KwpP7KuUm7KPNCMPmKlQZSa-jcIx0uD9Cu-b3lpBXwPJK_nEOjEc1NrNc";			
					$message_status = send_notification_android2($tokens,$Notificato_msg,$androidkey);
				
				}
				
			}
			
			$Customer_tokenResult = select_query("SELECT device_key as token FROM $db_name.customer_app_verify where phone_no='".$cust_phone_no."' and is_active='1' order by id desc limit 0,1");
			
			if(count($Customer_tokenResult)>0)
			{
				$cust_tokens[] = $Customer_tokenResult[0]['token'];
				
				if($Customer_tokenResult[0]['device_name'] == "IOS")
				{
					 $cust_Notificato_msg = array('body'    => 'We have Received Your Service Request',
											  'title'   => 'Customer Service Request',
											);
					  
					 $CUST_API_ACCESS_KEY = "AAAA87f8--I:APA91bGG1Ymiu8R4HSJoa25ot1NBltzxgMs2BcUzY2zye-UFNlW98ak1Pf5_4cqBsgQYuyYCaLh1pmTdTJGngrk5LO2rbj7t7RNZLS-tcQdisJaGku75bRCue2_dbAZEIYTyLm6Q9d6r";
					 $message_status = send_ios_notification($cust_tokens,$cust_Notificato_msg,$CUST_API_ACCESS_KEY);
					
				} else {
					
					$cust_Notificato_msg = array("data" => "We have Received Your Service Request");
			 
					$cust_androidkey = "AAAApKEFvR0:APA91bGiQdxPMnsX6gAi2-jZL8Du-AakkLwRTU_bx-_nCHaTncKf85hSuti8-LkAWs62pyJjulPs3URy69-vm2UoLBztqzrjYS38nCLtTf7ASJ8T1_7WH4xkSXEWWQDSDkRWeBPLOXSg";			
					$message_status = send_notification_android2($cust_tokens,$cust_Notificato_msg,$cust_androidkey);
				
				}
				
			}
			
			if($insert_query) {
	
				echo "<script>window.location.href='view-request-job.php'</script>";
		
			
				$_SESSION['success_msg'] = 'set';
			
			}
						
		
		}

	/*}*/
	
}
?>

<link rel="stylesheet" href="<? echo __SITE_URL?>/css/bootstrap-select.css">

 
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view-request-job.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Assign New Ticket</a> </div>
    
  </div>
  <div class="container-fluid">
    
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Assign New Ticket</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="myForm" id="myForm" action="" method="post" class="form-horizontal" autocomplete="off" >
              <div class="alert alert-error error_display" style="display:none">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span id="print_err"></span>
			  </div>
			  
			  <?php if(isset($_SESSION['success_msg'])) {  ?>
				<div class="alert alert-success success_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Success!</strong><span> Succesfully added. Click <a href="view-request-job.php">here</a> to View</span>
			  </div>
			  <?php } else if(isset($_SESSION['unsuccess_msg'])) {  ?>
				<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> This Job Already Exist. </span>
			  </div>
              <?php /*} else if(isset($_SESSION['jobnotcreate_msg'])) { */ ?>
				<!--<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> Can't Create Today Job Due to Day Not Start by Employee. </span>
			  </div>-->
			  <?php } 
			  unset($_SESSION['success_msg']);
			  unset($_SESSION['unsuccess_msg']);
			  unset($_SESSION['jobnotcreate_msg']);
			  
			  $get_cust_recd = select_query("SELECT id,concat(name,'##',cust_id) as cust_id,concat(company_name,' / ',cust_id) as cust_name,
			  company_name, phone_no FROM $db_name.customer_details WHERE is_active='1' and loginid='".$_SESSION['user_id']."'");
			  
			  $get_tech_recd = select_query("SELECT id,concat(emp_name,' / ',mobile_no) as tech_name,mobile_no  FROM 
			  $db_name.technicians_login_details WHERE is_active='1' and loginid='".$_SESSION['user_id']."'");
			  
			  $get_Symptom = select_query("SELECT * FROM $db_name.symptoms_tbl WHERE is_active='1' ");
			  
			  ?>
			  
				<div class="control-group">
                <label class="control-label">Company Name/ ID:<font color="red">* </font></label>
                <div class="controls"> 
                    <div class="col-sm-3">
                        <select class="selectpicker pull-left sepratesize" data-live-search="true" title="Select Company Name/ ID" name="cust_name" id="cust_name" onchange="userDetailsShow(this.value)">
                          <!--<option value="">Select Phone Number</option>-->
                        <?php for($rq=0;$rq<count($get_cust_recd);$rq++) { ?>
                          <option value="<?=$get_cust_recd[$rq]['cust_id'];?>"><?=$get_cust_recd[$rq]['cust_name'];?></option>
                          <!--<input type="text" name="number" maxlength="10" id="number"  class="mandatory" placeholder="Number *" />-->
                        <? } ?>
                        </select>
                        <span id="branch_error"></span> </div>
                  </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">To Technician:<font color="red">* </font></label>
                <div class="controls"> 
                    <div class="col-sm-3">
                        <select class="selectpicker pull-left sepratesize" data-live-search="true" title="Select To Technician" name="tech_name" id="tech_name">
                          <!--<option value="">Select Phone Number</option>-->
                        <?php for($tr=0;$tr<count($get_tech_recd);$tr++) { ?>
                          <option value="<?=$get_tech_recd[$tr]['id'];?>"><?=$get_tech_recd[$tr]['tech_name'];?></option>
                          <!--<input type="text" name="number" maxlength="10" id="number"  class="mandatory" placeholder="Number *" />-->
                        <? } ?>
                        </select>
                        <span id="branch_error"></span> </div>
                  </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Request Date:</label>
                <div class="controls date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="mandatory date-picker" name="fromtime" id="dateStart" type="text" value="" placeholder="Request Date" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Location:<font color="red">* </font></label>
                <div class="controls">
                      <input type="text" name="location" id="autocomplete" class="mandatory" placeholder="Location *" />
                      <span id="branch_error"></span> 
                 </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Pin Code:</label>
                <div class="controls">
                  <input type="text" name="pin_code" id="pin_code"  class="mandatory" placeholder="Pin Code" />
                  <span id="branch_error"></span> </div>
              </div>
              
               <div class="control-group">
                <label class="control-label">Service Type:<font color="red">* </font></label>
                <div class="controls">
                  <select name="service_type" id="service_type" class="form-control" onchange="ShowDropDownVal(this.value)">
                      <option value="">Select Service Type</option>
                      <option value="PMS" <?php if($get_cust_recd[0]['service_type'] == 'PMS' ) { echo "selected='selected'";}?>>PMS</option>
                      <option value="CMS" <?php if($get_cust_recd[0]['service_type'] == 'CMS' ) { echo "selected='selected'";}?>>CMS</option>
                      <option value="Installation" <?php if($get_cust_recd[0]['service_type'] == 'Installation' ) { echo "selected='selected'";}?>>Installation</option>
                      <option value="Only Service" <?php if($get_cust_recd[0]['service_type'] == 'Only Service' ) { echo "selected='selected'";}?>>Only Service</option>
                  </select>
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Work Type:<font color="red">* </font></label>
                <div class="controls">
                  <!--<input type="text" name="work_type" id="work_type"  class="mandatory" placeholder="Work Type" />-->
                  <select name="work_type" id="work_type" class="form-control">
                      <option value="">Select Work Type</option>
                      <option value="Under AMC">Under AMC</option>
                      <option value="Chargeable">Chargeable</option> 
                      <option value="Warranty">Warranty</option> 
                      <option value="Other">Other</option>                      
                  </select>
                  
                  <input type="hidden" name="call_type" id="call_type" value="Call Type" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <!--<div class="control-group">
                <label class="control-label">Call Type:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="call_type" id="call_type"  class="mandatory" placeholder="Call Type" value="Call Type" />
                  <span id="branch_error"></span> </div>
              </div>-->
              
              <div class="control-group">
                <label class="control-label">Product Type:</label>
                <div class="controls">
                  <select name="product_group" id="product_group" class="form-control">
                      <option value="">Select Product Type</option>
                      <option value="High Temperature">High Temperature</option>
                      <option value="Low Temperature">Low Temperature</option>                      
                  </select>
                  <!--<input type="text" name="product_group" id="product_group"  class="mandatory" placeholder="Product Group" />-->
                  <span id="branch_error"></span> </div>
              </div>
              
              <!--<div class="control-group">
                <label class="control-label">Model Number:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="model_no" id="model_no"  class="mandatory" placeholder="Model Number" />
                  <span id="branch_error"></span> </div>
              </div>-->
              
              <div class="control-group">
                <label class="control-label">Model Number:<font color="red">* </font></label>
                <div class="controls" id="textModel"> </div>
              </div>
              
              
              <div class="control-group">
                <label class="control-label">Serial Number:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="serial_no" id="serial_no"  class="mandatory" placeholder="Serial Number" readonly="readonly" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group" id="Symptom_show" style="display:none">
                <label class="control-label">Symptom:<font color="red">* </font></label>
                <div class="controls">
                  <select name="symptom" id="symptom" class="form-control">
                      <option value="">Select Symptom</option>
                      <? for($sym=0;$sym<count($get_Symptom);$sym++) { ?>
                      <option value="<?=$get_Symptom[$sym]['sym_name'];?>"><?=$get_Symptom[$sym]['sym_name'];?></option>
                      <? } ?>
                  </select>
                  <span id="branch_error"></span> </div>
                <!--<div class="controls">
                  <input type="text" name="symptom" id="symptom"  class="mandatory" placeholder="Symptom" />
                  <span id="branch_error"></span> </div>-->
              </div>
              
              <!--<div class="control-group">
                <label class="control-label">Defect:</label>
                <div class="controls">
                  <input type="text" name="defect" id="defect"  class="mandatory" placeholder="Defect" />
                  <span id="branch_error"></span> </div>
              </div>-->
              
              
              
              <div class="control-group" id="Priority_show" style="display:none">
                <label class="control-label">Priority Type:<font color="red">* </font></label>
                <div class="controls">
                  <select name="priority_type" id="priority_type" class="form-control">
                      <option value="">Select Priority Type</option>
                      <option value="Low">Low</option>
                      <option value="High">High</option>
                      <option value="Very Urgent">Very Urgent</option>
                      
                  </select>
                  <span id="branch_error"></span> </div>
                <!--<div class="controls">
                  <input type="text" name="priority_type" id="priority_type"  class="mandatory" placeholder="Priority Type" />
                  <span id="branch_error"></span> </div>-->
              </div>
              
              <!--<div class="control-group">
                <label class="control-label">Cubic ft:</label>
                <div class="controls">
                  <input type="text" name="cubic_ft" id="cubic_ft"  class="mandatory" placeholder="Cubic ft" />
                  <span id="branch_error"></span> </div>
              </div>-->
                            
              <div class="control-group">
                <label class="control-label">Customer Email Id:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="cust_email_id" id="cust_email_id"  class="mandatory" placeholder="Customer Email Id" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Customer Mobile Number:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="cust_phone_no" maxlength="10" id="cust_phone_no"  class="mandatory" placeholder="Customer Mobile Number" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Amount To be Collected (if any):</label>
                <div class="controls">
                  <input type="text" name="amount_to_be_collected" id="amount_to_be_collected"  class="mandatory" placeholder="Amount To be Collected " />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Total Working Hours Req:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="total_working_hrs" id="total_working_hrs"  class="mandatory" placeholder="Total Working Hours Req" />
                  <span id="branch_error"></span> </div>
              </div>
                            
              <!--<div class="control-group">
                <label class="control-label">To time:</label>
                <div class="controls date form_datetime" data-date="" data-date-format="yyyy-mm-dd hh:ii" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii">
                   <input class="mandatory date-picker" name="totime" id="dateEnd" type="text" value=""  placeholder="To Time" readonly>
                   <span class="add-on"><i class="icon-th"></i></span>
               </div>
              </div>-->
			  
              <div class="form-actions">
                <button type="submit" class="btn-harish btn-info-harish save_step_1" name="save_people">Save</button>
                <a  class="btn-harish btn-info-harish" href="view-request-job.php" style="color: #fff;">Cancel</a>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<!--<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/libs/jquery/jquery.min.js"></script> -->
<script src="<? echo __SITE_URL?>/js/bootstrap-select.js"></script>   
<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/libs/bootstrap/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script>

function getDateTime() {
	var now     = new Date(); 
	var year    = now.getFullYear();
	var month   = now.getMonth()+1; 
	var day     = now.getDate();
	/*var hour    = now.getHours();
	var minute  = now.getMinutes();
	var second  = now.getSeconds();*/ 
	
	if(month.toString().length == 1) {
		 month = '0'+month;
	}
	if(day.toString().length == 1) {
		 day = '0'+day;
	}   
	/*if(hour.toString().length == 1) {
		 hour = '0'+hour;
	}
	if(minute.toString().length == 1) {
		 minute = '0'+minute;
	}
	if(second.toString().length == 1) {
		 second = '0'+second;
	}   
	var dateTime = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second; */  
	
	var dateTime = year+'-'+month+'-'+day; 
	
	return dateTime;
}
	  
$( document ).ready(function(){

////////////////////////// Validation ////////////////////////
	
    $('.save_step_1').click(function(e) {
	
		var cust_name = $("#cust_name").val();
		//alert(phone_number);
		if( cust_name == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select Customer Name/ ID.");
			return false;
		}
		
		var tech_name = $("#tech_name").val();
		//alert(phone_number);
		if( tech_name == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select To Technician.");
			return false;
		}
		
		var location = $("#autocomplete").val();
		if( location == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Location.");
			return false;
		}
		
		var service_type = $("#service_type").val();
		if( service_type == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select Service Type.");
			return false;
		}
		
		var work_type = $("#work_type").val();
		if( work_type == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select Work Type.");
			return false;
		}
		
		/*var call_type = $("#call_type").val();
		if( call_type == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select Call Type.");
			return false;
		}*/
		
		/*var model_no = $("#model_no").val();
		if( model_no == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Model Number.");
			return false;
		}*/
		
		var serial_no = $("#serial_no").val();
		if( serial_no == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select Model Number.");
			return false;
		}
		
		if( service_type != 'Installation' ) {
		
			var symptom = $("#symptom").val();
			if( symptom == '' ) {
				$(".error_display").css("display","block");
				$("#print_err").html(" Please Select Symptom.");
				return false;
			}
					
			var priority_type = $("#priority_type").val();
			if( priority_type == '' ) {
				$(".error_display").css("display","block");
				$("#print_err").html(" Please Select Priority Type.");
				return false;
			}
		
		}
		
		/*var cubic_ft = $("#cubic_ft").val();
		if( cubic_ft == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Cubic ft.");
			return false;
		}*/
		
		
		var cust_email_id = $("#cust_email_id").val();
		if( cust_email_id == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Customer Email Id.");
			return false;
		}
		
		var p_name = $("#cust_phone_no").val();
		if( p_name == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Customer Mobile Number.");
			return false;
		}
		if(p_name!="")
		{
			var lenth=p_name.length;
			
			if(lenth < 10 || lenth > 12 || p_name.search(/[^0-9\-()+]/g) != -1 )
			{
				$(".error_display").css("display","block");
				$("#print_err").html(" Please enter valid Mobile Number.");
				return false;
			}
		}
		
		var total_working_hrs = $("#total_working_hrs").val();
		if( total_working_hrs == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Total Working Hours.");
			return false;
		}
		
		/*var fields = phone_number.split('##');
		var day_status = fields[2];
		//alert(day_status);
		var currentTime = getDateTime();
		//alert(currentTime);
		
		var fromTime = $("#dateStart").val();
		var toTime = $("#dateEnd").val();
		
		var breaktime = fromTime.split(' ');
		var getDateVal = breaktime[0];*/
		//alert(getDateVal);
		
		/*if(fromTime == '' && toTime == '' && day_status != 1) {
			
			    $(".error_display").css("display","block");
				$("#print_err").html(" Can't Create Today Job Due to Day Not Start by Employee.");
				return false;
			
		}
		else if(getDateVal == currentTime && day_status != 1) {
			
			    $(".error_display").css("display","block");
				$("#print_err").html(" Can't Create Today Date Job Due to Day Not Start by Employee.");
				return false;
			
		}*/
		
		/*if((fromTime != '' && toTime != '') && (fromTime != null && toTime != null)) {
			
			if(fromTime>=toTime)
			{
			    $(".error_display").css("display","block");
				$("#print_err").html(" To Time always greater then From Time.");
				return false;
			}
			
		}
				
		if((fromTime != '' && toTime == '' && fromTime != null)) {
			
			    $(".error_display").css("display","block");
				$("#print_err").html(" Place Select To Time.");
				return false;
			
		}
		
		if((fromTime == '' && toTime != '' && toTime != null)) {
			
			    $(".error_display").css("display","block");
				$("#print_err").html(" Place Select From Time.");
				return false;
			
		}*/
		
		/*
		var dateStart = $("#dateStart").val();
		if( dateStart == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter From Time.");
			return false;
		}
		
		var dateEnd = $("#dateEnd").val();
		if( dateEnd == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter To Time.");
			return false;
		}
		
		var client_name = $("#client_name").val();
		if( client_name == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please enter Client Name.");
			return false;
		}
		
		var contact_person = $("#contact_person").val();
		if( contact_person == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Contact Person.");
			return false;
		}
		
		var p_name = $("#contact_person_mobile").val();
		if( p_name == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Contact Person No.");
			return false;
		}
		if(p_name!="")
		{
			var lenth=p_name.length;
			
			if(lenth < 10 || lenth > 12 || p_name.search(/[^0-9\-()+]/g) != -1 )
			{
				$(".error_display").css("display","block");
				$("#print_err").html(" Please enter valid Phone number.");
				return false;
			}
		}
		
		*/
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}	   
	   
    });
	
	 $('#myForm').on('keyup keypress', function(e) {
	  var keyCode = e.keyCode || e.which;
	  if (keyCode === 13) { 
		e.preventDefault();
		return false;
	  }
	});
	
	 $("#cust_phone_no").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

////////////////////////// Validation ////////////////////////
	
});

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMj-azrdqWrM3CypbBoVobpSg7XkUMKHA&signed_in=true&libraries=places&callback=initAutocomplete" async defer></script>
      
<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMj-azrdqWrM3CypbBoVobpSg7XkUMKHA&libraries=places&callback=initAutocomplete" async defer></script> -->
 <!--AIzaSyCl3Ipc_2M0wZ6kTP4Z7Z0Q1xa2rukzt6E -->      
<script>
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

var placeSearch, autocomplete;
var componentForm = {
street_number: 'short_name',
route: 'long_name',
locality: 'long_name',
administrative_area_level_1: 'short_name',
country: 'long_name',
postal_code: 'short_name'
};

function initAutocomplete() {
// Create the autocomplete object, restricting the search to geographical
// location types.
autocomplete = new google.maps.places.SearchBox(
	/** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
	{types: ['geocode']});

// When the user selects an address from the dropdown, populate the address
// fields in the form.
autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
// Get the place details from the autocomplete object.
var place = autocomplete.getPlace();
}
</script>
<link href="<?php echo __SITE_URL;?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

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
		minuteStep: 1,
        todayHighlight: true,
		startDate: new Date(),
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
		startDate: new Date(),
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
        forceParse: 0
    });

</script>
<SCRIPT LANGUAGE="JavaScript">

<!--
function ShowDropDownVal(val)
{
	//alert(val);
	if(val == "Installation")
	{
	   document.getElementById("Symptom_show").style.display = 'none';
		document.getElementById("Priority_show").style.display = 'none';
	}
	else
	{
	   document.getElementById("Symptom_show").style.display = 'block';
		document.getElementById("Priority_show").style.display = 'block';
	}

}

function userDetailsShow(cid)
{
	$.ajax({
	
		type:"POST",
		url:"get_user_info.php?action=userDetails",
		data:"customer_id="+cid,
		success:function(data){                 
			//alert(data);
			var tmparr = data.split("##") ;

			var phonenodata = tmparr['0'] ;
			var emaildata = tmparr['1'] ;
			
			document.getElementById("cust_phone_no").value = phonenodata;
			document.getElementById("cust_email_id").value = emaildata;
				
		}
	
	});
	
	$.ajax({
		type:"POST",
		url:"get_user_info.php?action=getModelname",
		data:"customer_id="+cid,
		
		success:function(msg){
			
			$("#textModel").html('');
			//alert(msg);
			var modelDetails = JSON.parse(msg)
			var model = [];
			//alert(modelDetails.length)
			
			for(var i=0;i<modelDetails.length;i++){
			
				model.push(modelDetails[i].model)
			
			}
			var option3;
			var num3 = String(model).split(',')
			
			var option3 = "<option value='0'>Select Model</option>"
			
			for(var u = 0; u < num3.length; u++) {
			
				option3 += "<option value='"+num3[u]+"'>"+num3[u]+"</option>"
			
			}
							  
			age3 ='<select name="model_no" id="model_no" class="form-control" onchange="ShowSerialno(this.value)">'+option3+'</select><span id="branch_error"></span>';
			
			//alert(age3);
			$("#textModel").append(age3);				
			//$('.selectpicker').selectpicker('refresh');
		  
	 }  
	
	  
   });
}

function ShowSerialno(model)
{
	var cust_name = $("#cust_name").val();
	//alert(cust_name);
	
	$.ajax({
		type:"POST",
		url:"get_user_info.php?action=getSerialno",
		data:"customer_id="+cust_name+"&model="+model,
		success:function(data){                 
			//alert(data);
			
			document.getElementById("serial_no").value = data;
				
		}
	
	});
	
}

//-->

</SCRIPT>
<?php include('inc/footer.php');?>
