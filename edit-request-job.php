<?php 
include('inc/header.php');

$user_id = $_SESSION['user_id'];

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

$req_id = base64_decode($_REQUEST['id']);


$get_edit_recd = select_query("SELECT * FROM $db_name.all_job_details WHERE id='".$req_id."' and loginid='".$_SESSION['user_id']."' ");
//echo "<pre>";print_r($get_edit_recd);die;

$get_cust_recd_edit = select_query("SELECT id,concat(name,'##',cust_id) as cust_id,concat(company_name,' / ',cust_id) as cust_name,
			  company_name, phone_no FROM $db_name.customer_details WHERE is_active='1' and loginid='".$_SESSION['user_id']."' and cust_id='".$get_edit_recd[0]['customer_id']."' ");


$get_tech_recd_edit = select_query("SELECT id,concat(emp_name,' / ',mobile_no) as tech_name,mobile_no  FROM $db_name.technicians_login_details
 			WHERE is_active='1' and loginid='".$_SESSION['user_id']."' and id='".$get_edit_recd[0]['to_technician']."'");
	

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
	//$symptom = $_POST['symptom'];
	//$defect = $_POST['defect'];
	
	if($service_type == "Installation") {
		
		$symptom = "Installation";
		$priority_type = "High";
		
	} else {
	
		$symptom = $_POST['symptom'];
		$priority_type = $_POST['priority_type'];
	
	}
	
	
	//$priority_type = $_POST['priority_type'];
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
		
		$insert_lat_long = array('latitude' => $lat , 'longitude' => $lng, 'location' => $location, 'phone_no' => $phone);
		$insert_loc = insert_query($db_name.'.location', $insert_lat_long);
		
		/*$lat = '00.0000000';
		$lng = '00.0000000';*/
		
	}
		
		
	$cur_date= date('Y-m-d 00:00:00');
	
	if($cust_id != $get_edit_recd[0]['customer_id'])
	{
		$getCustId = select_query("SELECT id,cust_id,name,amc_service_done FROM $db_name.customer_details where cust_id='".$cust_id."' and 
		is_active='1' and loginid='".$_SESSION['user_id']."' ");
		//echo "<pre>";print_r($getCustId);die;
		$jobId = "M".date("dmy")."0".$getCustId[0]['id']."FR".($getCustId[0]['amc_service_done']+1);
		
		$update_warranty = array('date_of_installation' => $req_date);
			
		$condition = array('id' => $getCustId[0]['id'], 'loginid' => $_SESSION['user_id'], 'is_active' => 1);            
		update_query($db_name.'.customer_details', $update_warranty, $condition);
		
	} else {
		$jobId = $get_edit_recd[0]['ticket_no'];
	}
	
	//'cubic_ft' => $cubic_ft, 'ticket_no' => $jobId , 'customer_id' => $cust_id, 'customer_name' => $cust_name,
	
	$update_array = array('request_date' => $req_date,
	'to_technician' => $tech_id, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'pin_code' => $pin_code,
	'service_type' => $service_type, 'call_type' => $call_type, 'product_group' => $product_group, 'model_no' => $model_no, 
	'serial_no' => $serial_no, 'symptom' => $symptom, 'work_type' => $work_type, 'priority_type' => $priority_type, 
	'customer_email_id' => $cust_email_id, 'customer_phone_no' => $cust_phone_no,
	'amount_to_be_collected' => $amount_to_be_collected, 'total_working_hrs_req' => $total_working_hrs,
	'job_assign_time' => date("Y-m-d H:i:s"),  'job_status' => 0,  'loginid' => $_SESSION['user_id']);
			
	$condition = array('id' => $req_id, 'is_active' => 1, 'loginid' => $_SESSION['user_id']);            
	$result = update_query($db_name.'.all_job_details', $update_array, $condition);
	
	
		
	
	/*$tokenResult = select_query("SELECT device_key as token FROM $db_name.installer_app_verify where phone_no='".$phone."' and is_active='1' order by id desc limit 0,1");
	//echo "<pre>";print_r($tokenResult);die;
	
	if(count($tokenResult)>0)
	{
		$tokens[] = $tokenResult[0]['token'];

		$Notificato_msg = array("data" => "New Job Allocated. Please Refresh Application");
 
		$androidkey = "AAAAhDM80OU:APA91bFTrEQU9iC9_tuI11NFyFh87mjQGq2GvYEflDZJdQlbkXmylofJFuUhlux0R78ZParyGVrn9f41IawZa8hDAUUJZ59DmeSdJogUPilC4vrqYVVufFAs7bSSQ3LTrxvt_wgjCqnm";

		$message_status = send_notification_android2($tokens,$Notificato_msg,$androidkey);
			
	}*/
	
	if($result) {

		echo "<script>window.location.href='view-request-job.php'</script>";

	
		$_SESSION['success_msg'] = 'set';
	
	}
			
			
			
		
		

	
	
}
?>

<link rel="stylesheet" href="<? echo __SITE_URL?>/css/bootstrap-select.css">

 
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view-request-job.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Edit Assign New Ticket</a> </div>
    
  </div>
  <div class="container-fluid">
    
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Edit Assign New Ticket</h5>
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
			  
			 /* $get_cust_recd = select_query("SELECT id,concat(name,'##',cust_id) as cust_id,concat(company_name,' / ',cust_id) as cust_name,
			  company_name, phone_no FROM $db_name.customer_details WHERE is_active='1' and loginid='".$_SESSION['user_id']."'");*/
			  
			  $get_tech_recd = select_query("SELECT id,concat(emp_name,' / ',mobile_no) as tech_name,mobile_no  FROM 
			  $db_name.technicians_login_details WHERE is_active='1' and loginid='".$_SESSION['user_id']."'");
			  
			  $get_Symptom = select_query("SELECT * FROM $db_name.symptoms_tbl WHERE is_active='1' ");
			  
			  ?>
			    <input type="hidden" name="req_id" id="req_id" value="<?php echo $req_id;?>"/>
				
                <div class="control-group">
                <label class="control-label">Company Name/ ID:<font color="red">* </font></label>
                <div class="controls">
                  <input type="hidden" name="cust_name" id="cust_name"  class="mandatory" value="<?=$get_cust_recd_edit[0]['cust_id']?>" readonly="readonly"/>
                   <input type="text" name="company" id="company"  class="mandatory" value="<?=$get_cust_recd_edit[0]['cust_name']?>" readonly="readonly"/>
                  <span id="branch_error"></span> </div>
              </div>
                
                <!--<div class="control-group">
                <label class="control-label">Company Name/ ID:<font color="red">* </font></label>
                <div class="controls"> 
                    <div class="col-sm-3">
                        <select class="selectpicker pull-left sepratesize" data-live-search="true" title="Select Customer Name/ ID" name="cust_name" id="cust_name">
                        <?php for($rq=0;$rq<count($get_cust_recd);$rq++) { ?>
                          <option value="<?=$get_cust_recd[$rq]['cust_id'];?>" <? if($get_cust_recd_edit[0]['cust_id']==$get_cust_recd[$rq]['cust_id']) {?> selected="selected" <? } ?>><?=$get_cust_recd[$rq]['cust_name'];?></option>
                        <? } ?>
                        </select>
                        <span id="branch_error"></span> </div>
                  </div>
              </div>-->
              
              <div class="control-group">
                <label class="control-label">To Technician:<font color="red">* </font></label>
                <div class="controls"> 
                    <div class="col-sm-3">
                        <select class="selectpicker pull-left sepratesize" data-live-search="true" title="Select To Technician" name="tech_name" id="tech_name">
                          <!--<option value="">Select Phone Number</option>-->
                        <?php for($tr=0;$tr<count($get_tech_recd);$tr++) { ?>
                          <option value="<?=$get_tech_recd[$tr]['id'];?>" <? if($get_tech_recd_edit[0]['id']==$get_tech_recd[$tr]['id']) {?> selected="selected" <? } ?>><?=$get_tech_recd[$tr]['tech_name'];?></option>
                          <!--<input type="text" name="number" maxlength="10" id="number"  class="mandatory" placeholder="Number *" />-->
                        <? } ?>
                        </select>
                        <span id="branch_error"></span> </div>
                  </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Request Date:</label>
                <div class="controls date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="mandatory date-picker" name="fromtime" id="dateStart" type="text" value="<?=$get_edit_recd[0]['request_date']?>" placeholder="Request Date" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Location:<font color="red">* </font></label>
                <div class="controls">
                      <input type="text" name="location" id="autocomplete" value="<?=$get_edit_recd[0]['job_location']?>" class="mandatory" placeholder="Location" />
                      <span id="branch_error"></span> 
                 </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Pin Code:</label>
                <div class="controls">
                  <input type="text" name="pin_code" id="pin_code" value="<?=$get_edit_recd[0]['pin_code']?>"  class="mandatory" placeholder="Pin Code" />
                  <span id="branch_error"></span> </div>
              </div>
              
               <div class="control-group">
                <label class="control-label">Service Type:<font color="red">* </font></label>
                <div class="controls">
                  <select name="service_type" id="service_type" class="form-control" onchange="ShowDropDownVal(this.value)">
                      <option value="">Select Service Type</option>
                      <option value="PMS" <?php if($get_edit_recd[0]['service_type'] == 'PMS' ) { echo "selected='selected'";}?>>PMS</option>
                      <option value="CMS" <?php if($get_edit_recd[0]['service_type'] == 'CMS' ) { echo "selected='selected'";}?>>CMS</option>
                      <option value="Installation" <?php if($get_edit_recd[0]['service_type'] == 'Installation' ) { echo "selected='selected'";}?>>Installation</option>
                      <option value="Only Service" <?php if($get_edit_recd[0]['service_type'] == 'Only Service' ) { echo "selected='selected'";}?>>Only Service</option>
                  </select>
                  <span id="branch_error"></span> </div>
              </div>
              
              <!--<div class="control-group">
                <label class="control-label">Work Type:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="work_type" id="work_type"  class="mandatory" placeholder="Work Type" value="<?=$get_edit_recd[0]['work_type']?>"/>
                  <span id="branch_error"></span> </div>
              </div>-->
              
              <div class="control-group">
                <label class="control-label">Work Type:<font color="red">* </font></label>
                <div class="controls">
                  <!--<input type="text" name="work_type" id="work_type"  class="mandatory" placeholder="Work Type" />-->
                  <select name="work_type" id="work_type" class="form-control">
                      <option value="">Select Work Type</option>
                      <option value="Under AMC" <?php if($get_edit_recd[0]['work_type'] == 'Under AMC' ) { echo "selected='selected'";}?>>Under AMC</option>
                      <option value="Chargeable" <?php if($get_edit_recd[0]['work_type'] == 'Chargeable' ) { echo "selected='selected'";}?>>Chargeable</option> 
                      <option value="Warranty" <?php if($get_edit_recd[0]['work_type'] == 'Warranty' ) { echo "selected='selected'";}?>>Warranty</option> 
                      <option value="Other" <?php if($get_edit_recd[0]['work_type'] == 'Other' ) { echo "selected='selected'";}?>>Other</option>                      
                  </select>
                  
                  <input type="hidden" name="call_type" id="call_type" value="<?=$get_edit_recd[0]['call_type']?>" />
                  <span id="branch_error"></span> </div>
              </div>
              
              
              <!--<div class="control-group">
                <label class="control-label">Call Type:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="call_type" id="call_type"  class="mandatory" placeholder="Call Type" value="<?=$get_edit_recd[0]['call_type']?>"/>
                  <span id="branch_error"></span> </div>
              </div>-->
              
              <div class="control-group">
                <label class="control-label">Product Type:<font color="red">* </font></label>
                <div class="controls">
                  <!--<input type="text" name="product_group" id="product_group"  class="mandatory" placeholder="Product Group" value="<?=$get_edit_recd[0]['product_group']?>"/>-->
                  <select name="product_group" id="product_group" class="form-control">
                      <option value="">Select Product Type</option>
                      <option value="High Temperature" <?php if($get_edit_recd[0]['product_group'] == 'High Temperature' ) { echo "selected='selected'";}?>>High Temperature</option>
                      <option value="Low Temperature" <?php if($get_edit_recd[0]['product_group'] == 'Low Temperature' ) { echo "selected='selected'";}?>>Low Temperature</option>                      
                  </select>
                  <span id="branch_error"></span> </div>
              </div>
              
              <? $get_model = select_query("SELECT * FROM $db_name.customer_model_master WHERE customer_id='".$get_edit_recd[0]['customer_id']."' 
			  and is_active='1' and  loginid='".$_SESSION['user_id']."' "); ?>
              
              <div class="control-group">
                <label class="control-label">Model Number:<font color="red">* </font></label>
                <div class="controls" id="textModel">
                  <!--<input type="text" name="model_no" id="model_no"  class="mandatory" placeholder="Model Number" value="<?=$get_edit_recd[0]['model_no']?>" readonly="readonly"/>-->
                  <select name="model_no" id="model_no" class="form-control" onchange="ShowSerialno(this.value)">
                      <option value="">Select Model</option>
                      <? for($md=0;$md<count($get_model);$md++) { ?>
                      <option value="<?=$get_model[$md]['model_purchased'];?>" <?php if($get_edit_recd[0]['model_no'] == $get_model[$md]['model_purchased'] ) { echo "selected='selected'";}?>><?=$get_model[$md]['model_purchased'];?></option>
                      <? } ?>
                  </select>
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Serial Number:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="serial_no" id="serial_no"  class="mandatory" placeholder="Serial Number" value="<?=$get_edit_recd[0]['serial_no']?>" readonly="readonly"/>
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group" id="Symptom_show" style="display:none">
                <label class="control-label">Symptom:<font color="red">* </font></label>
                <div class="controls">
                  <select name="symptom" id="symptom" class="form-control">
                      <option value="">Select Symptom</option>
                      <? for($sym=0;$sym<count($get_Symptom);$sym++) { ?>
                      <option value="<?=$get_Symptom[$sym]['sym_name'];?>" <?php if($get_edit_recd[0]['symptom'] == $get_Symptom[$sym]['sym_name'] ) { echo "selected='selected'";}?>><?=$get_Symptom[$sym]['sym_name'];?></option>
                      <? } ?>
                  </select>
                  <span id="branch_error"></span> </div>
                <!--<div class="controls">
                  <input type="text" name="symptom" id="symptom"  class="mandatory" placeholder="Symptom" value="<?=$get_edit_recd[0]['symptom']?>"/>
                  <span id="branch_error"></span> </div>-->
              </div>
              
              <!--<div class="control-group">
                <label class="control-label">Defect:</label>
                <div class="controls">
                  <input type="text" name="defect" id="defect"  class="mandatory" placeholder="Defect" value="<?=$get_edit_recd[0]['defect']?>"/>
                  <span id="branch_error"></span> </div>
              </div>-->
              
              
              
              <div class="control-group" id="Priority_show" style="display:none">
                <label class="control-label">Priority Type:<font color="red">* </font></label>
                <div class="controls">
                  <select name="priority_type" id="priority_type" class="form-control">
                      <option value="">Select Priority Type</option>
                      <option value="Low" <?php if($get_edit_recd[0]['priority_type'] == 'Low' ) { echo "selected='selected'";}?>>Low</option>
                      <option value="High" <?php if($get_edit_recd[0]['priority_type'] == 'High' ) { echo "selected='selected'";}?>>High</option>
                      <option value="Very Urgent" <?php if($get_edit_recd[0]['priority_type'] == 'Very Urgent' ) { echo "selected='selected'";}?>>Very Urgent</option>
                      
                  </select>
                  <span id="branch_error"></span> </div>
                  
                <!--<div class="controls">
                  <input type="text" name="priority_type" id="priority_type"  class="mandatory" placeholder="Priority Type" value="<?=$get_edit_recd[0]['priority_type']?>"/>
                  <span id="branch_error"></span> </div>-->
              </div>
              
              <!--<div class="control-group">
                <label class="control-label">Cubic ft:</label>
                <div class="controls">
                  <input type="text" name="cubic_ft" id="cubic_ft"  class="mandatory" placeholder="Cubic ft" value="<?=$get_edit_recd[0]['cubic_ft']?>"/>
                  <span id="branch_error"></span> </div>
              </div>-->
                            
              <div class="control-group">
                <label class="control-label">Customer Email Id:</label>
                <div class="controls">
                  <input type="text" name="cust_email_id" id="cust_email_id"  class="mandatory" placeholder="Customer Email Id" value="<?=$get_edit_recd[0]['customer_email_id']?>" readonly="readonly"/>
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Customer Mobile Number:</label>
                <div class="controls">
                  <input type="text" name="cust_phone_no" maxlength="10" id="cust_phone_no" value="<?=$get_edit_recd[0]['customer_phone_no']?>"  class="mandatory" placeholder="Customer Mobile Number"  readonly="readonly"/>
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Amount To be Collected (if any):</label>
                <div class="controls">
                  <input type="text" name="amount_to_be_collected" id="amount_to_be_collected" value="<?=$get_edit_recd[0]['amount_to_be_collected']?>"  class="mandatory" placeholder="Amount To be Collected " />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Total Working Hours Req:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="total_working_hrs" id="total_working_hrs" value="<?=$get_edit_recd[0]['total_working_hrs_req']?>" class="mandatory" placeholder="Total Working Hours Req" />
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
			$("#print_err").html(" Please Enter Work Type.");
			return false;
		}
		
		/*var call_type = $("#call_type").val();
		if( call_type == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select Call Type.");
			return false;
		}*/
		
		var model_no = $("#model_no").val();
		if( model_no == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Model Number.");
			return false;
		}
		
		var serial_no = $("#serial_no").val();
		if( serial_no == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Serial Number.");
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
		
		
		/*var cust_email_id = $("#cust_email_id").val();
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
		}*/
		
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
<script>ShowDropDownVal("<?=$get_edit_recd[0]['service_type'];?>");</script> 

