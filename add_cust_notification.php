<?php 
include('inc/header.php');

$user_id = $_SESSION['user_id'];

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

if (isset($_POST['save_people'])) {
	
	//echo "<pre>";print_r($_POST);die;
	
	$cust_nameData = $_POST['cust_name'];
	$cust_nameSplit = explode("##",$cust_nameData);
	
	$cust_id = $cust_nameSplit[0];
	$phone_no = $cust_nameSplit[1];
	
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
	
		
	$insert_query = insert_query($db_name.'.cust_push_notification', array('person_id' => $cust_id, 'phone_no' => $phone_no , 
		'message' => $message , 'from_date' => $from_date, 'to_date' => $to_date, 'loginid' =>$_SESSION['user_id']));
	
	if($phone_no == "All")
	{
		$custData = select_query("SELECT phone_no FROM $db_name.customer_details where is_active='1' and loginid='".$_SESSION['user_id']."' ");
		
		for($ecl=0;$ecl<count($custData);$ecl++)
		{
			$cust_PhoneNo .= $custData[$ecl]['phone_no'].",";
		}
		$cust_PhoneNo = substr($cust_PhoneNo,0,strlen($cust_PhoneNo)-1);
		
		$tokenResult = select_query("SELECT device_key as token FROM $db_name.customer_app_verify where phone_no IN (".$cust_PhoneNo.") and is_active='1'");
		//echo "<pre>";print_r($tokenResult);die;
		if(count($tokenResult)>0)
		{
			for($npl=0;$npl<count($tokenResult);$npl++)
			{
				$tokens[] = $tokenResult[$npl]['token'];
				
				/*$Notificato_msg = array("data" => "You have got New Notification. Please Check in Application");
		 
				$androidkey = "AAAApKEFvR0:APA91bGiQdxPMnsX6gAi2-jZL8Du-AakkLwRTU_bx-_nCHaTncKf85hSuti8-LkAWs62pyJjulPs3URy69-vm2UoLBztqzrjYS38nCLtTf7ASJ8T1_7WH4xkSXEWWQDSDkRWeBPLOXSg";
		
				$message_status = send_notification_android2($tokens,$Notificato_msg,$androidkey);*/
				
				if($tokenResult[$npl]['device_name'] == "IOS")
				{
					 $Notificato_msg = array('body'    => 'You have got New Notification. Please Check in Application',
											  'title'   => 'Customer New Notification',
											);
					  
					 $API_ACCESS_KEY = "AAAA87f8--I:APA91bGG1Ymiu8R4HSJoa25ot1NBltzxgMs2BcUzY2zye-UFNlW98ak1Pf5_4cqBsgQYuyYCaLh1pmTdTJGngrk5LO2rbj7t7RNZLS-tcQdisJaGku75bRCue2_dbAZEIYTyLm6Q9d6r";
					 $message_status = send_ios_notification($tokens,$Notificato_msg,$API_ACCESS_KEY);
					
				} else {
					
					$Notificato_msg = array("data" => "You have got New Notification. Please Check in Application");
			 
					$androidkey = "AAAApKEFvR0:APA91bGiQdxPMnsX6gAi2-jZL8Du-AakkLwRTU_bx-_nCHaTncKf85hSuti8-LkAWs62pyJjulPs3URy69-vm2UoLBztqzrjYS38nCLtTf7ASJ8T1_7WH4xkSXEWWQDSDkRWeBPLOXSg";			
					$message_status = send_notification_android2($tokens,$Notificato_msg,$androidkey);
				
				}
				
			}
		}
		
			
	} else {	
	
		$tokenResult = select_query("SELECT device_key as token FROM $db_name.customer_app_verify where phone_no='".$phone_no."' and is_active='1' order by id desc limit 0,1");
		//echo "<pre>";print_r($tokenResult);die;
			
		if(count($tokenResult)>0)
		{
			$tokens[] = $tokenResult[0]['token'];
			
			/*$Notificato_msg = array("data" => "You have got New Notification. Please Check in Application");
	 
			$androidkey = "AAAApKEFvR0:APA91bGiQdxPMnsX6gAi2-jZL8Du-AakkLwRTU_bx-_nCHaTncKf85hSuti8-LkAWs62pyJjulPs3URy69-vm2UoLBztqzrjYS38nCLtTf7ASJ8T1_7WH4xkSXEWWQDSDkRWeBPLOXSg";
	
			$message_status = send_notification_android2($tokens,$Notificato_msg,$androidkey);*/
			
			if($tokenResult[0]['device_name'] == "IOS")
			{
				 $Notificato_msg = array('body'    => 'You have got New Notification. Please Check in Application',
										  'title'   => 'Customer New Notification',
										);
				  
				 $API_ACCESS_KEY = "AAAA87f8--I:APA91bGG1Ymiu8R4HSJoa25ot1NBltzxgMs2BcUzY2zye-UFNlW98ak1Pf5_4cqBsgQYuyYCaLh1pmTdTJGngrk5LO2rbj7t7RNZLS-tcQdisJaGku75bRCue2_dbAZEIYTyLm6Q9d6r";
				 $message_status = send_ios_notification($tokens,$Notificato_msg,$API_ACCESS_KEY);
				
			} else {
				
				$Notificato_msg = array("data" => "You have got New Notification. Please Check in Application");
		 
				$androidkey = "AAAApKEFvR0:APA91bGiQdxPMnsX6gAi2-jZL8Du-AakkLwRTU_bx-_nCHaTncKf85hSuti8-LkAWs62pyJjulPs3URy69-vm2UoLBztqzrjYS38nCLtTf7ASJ8T1_7WH4xkSXEWWQDSDkRWeBPLOXSg";			
				$message_status = send_notification_android2($tokens,$Notificato_msg,$androidkey);
			
			}
				
		}
			
			
	}
	
	//echo $message_status;die;
	
	if($insert_query) {

		echo "<script>window.location.href='customer_notification.php'</script>";

		$_SESSION['success_msg'] = 'set';
	
	}
	
	

}
?>

<link rel="stylesheet" href="<? echo __SITE_URL?>/css/bootstrap-select.css">

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="customer_notification.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Add Customer Notification</a> </div>
    
  </div>
  <div class="container-fluid">
   
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add Customer Notification</h5>
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
                <strong class="error_submission">Success!</strong><span> Succesfully added. Click <a href="customer_notification.php">here</a> to View</span>
			  </div>
			  <?php } else if(isset($_SESSION['unsuccess_msg'])) {  ?>
				<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> Notification Already Exist. </span>
			  </div>
			  <?php } 
			  unset($_SESSION['success_msg']);
			  unset($_SESSION['unsuccess_msg']);
			  
			  $get_cust_recd = select_query("SELECT id,concat(cust_id,'##',phone_no) as cust_id,concat(name,' / ',phone_no) as cust_name,
			  company_name, phone_no FROM $db_name.customer_details WHERE is_active='1' and loginid='".$_SESSION['user_id']."'");
			  
			  /*$get_tech_recd = select_query("SELECT id,concat(emp_name,' / ',mobile_no) as tech_name,mobile_no  FROM 
			  $db_name.technicians_login_details WHERE is_active='1' and loginid='".$_SESSION['user_id']."'");*/
			  
			  ?>
			  
			   <div class="control-group">
                <label class="control-label">To Customer:</label>
                <div class="controls"> 
                    <div class="col-sm-3">
                        <select class="selectpicker pull-left sepratesize" data-live-search="true" title="Select To Customer" name="cust_name" id="cust_name">
                          <option value="All##All">All</option>
                        <?php for($tr=0;$tr<count($get_cust_recd);$tr++) { ?>
                          <option value="<?=$get_cust_recd[$tr]['cust_id'];?>"><?=$get_cust_recd[$tr]['cust_name'];?></option>
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
                <a  class="btn-harish btn-info-harish" href="customer_notification.php" style="color: #fff;">Cancel</a>

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
		
		var cust_name = $("#cust_name").val();
		//alert(phone_number);
		if( cust_name == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select To Customer.");
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
