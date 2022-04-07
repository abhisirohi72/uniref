<?php 
include('inc/header.php');

$action = $_GET['action'];
$req_id = base64_decode($_REQUEST['id']);

$get_cust_recd = select_query("SELECT * FROM $db_name.customer_details WHERE id='".$req_id."' and loginid='".$_SESSION['user_id']."' ");
// echo "<pre>";print_r($get_cust_recd);die;

$get_model_recd = select_query("SELECT * FROM $db_name.customer_model_master WHERE cust_id='".$req_id."' and loginid='".$_SESSION['user_id']."' ");
//echo "<pre>";print_r($get_model_recd);die;

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
	$add = $jsondata["results"][0]["formatted_address"];
	/*echo "<script type='text/javascript'>alert('".$lat."');</script>";*/
  return $lat."@".$lng."@".$add;
  
}

if (isset($_POST['save_people'])) {
	
	//echo "<pre>";print_r($_POST);die;
	$req_id = $_POST['req_id'];
	//file upload
	// echo "<pre>";
	// print_r($_FILES);
	// exit;
	if (isset($_FILES["myFile"]['name']) && !empty($_FILES["myFile"]['name'])) {
		$filepath = $_FILES['myFile']['tmp_name'];
		$fileSize = filesize($filepath);
		$fileinfo = finfo_open(FILEINFO_MIME_TYPE);
		$filetype = finfo_file($fileinfo, $filepath);

		if ($fileSize === 0) {
			echo "<script>window.location.href='edit_customer.php?action=edit&id=".base64_encode($req_id)."'</script>";
	
			$_SESSION['fileError'] = 'set';
		}

		if ($fileSize > 3145728) { // 3 MB (1 byte * 1024 * 1024 * 3 (for 3 MB))
			echo "<script>window.location.href='edit_customer.php?action=edit&id=".base64_encode($req_id)."'</script>";
	
			$_SESSION['fileSizeError'] = 'set';
		}

		$allowedTypes = [
		   'image/png' => 'png',
		   'image/jpeg' => 'jpg'
		];

		if (!in_array($filetype, array_keys($allowedTypes))) {
			echo "<script>window.location.href='edit_customer.php?action=edit&id=".base64_encode($req_id)."'</script>";
	
			$_SESSION['fileTypeError'] = 'set';
		}

		$filename = time().basename($filepath); // I'm using the original name here, but you can also change the name of the file here
		$extension = $allowedTypes[$filetype];
		$targetDirectory ="uploads"; // __DIR__ is the directory of the current PHP file

		$newFilepath = $targetDirectory . "/" . $filename . "." . $extension;

		if (!copy($filepath, $newFilepath)) { // Copy the file, returns false if failed
			echo "<script>window.location.href='edit_customer.php?action=edit&id=".base64_encode($req_id)."'</script>";
	
			$_SESSION['fileMoveError'] = 'set';
		}
		unlink($filepath); // Delete the temp file
	}else{
		if(empty($_POST['aadhar_hidden'])){
			echo "<script>window.location.href='edit_customer.php?action=edit&id=".base64_encode($req_id)."'</script>";
	
			$_SESSION['fileError'] = 'set';
		}else{
			$newFilepath = $_POST['aadhar_hidden'];
		}
	}

	
	
	
	$name = $_POST['name'];
	$number = $_POST['number'];
	$organization_name = $_POST['organization_name'];
	$cust_email_id = $_POST['cust_email_id'];
	$aadhar_no = $_POST['aadhar_no'];
	if($_POST['birth_date']==''){$birth_date = "0000-00-00";}else{$birth_date = $_POST['birth_date'];}	
	$gender = $_POST['gender'];
	$document = $_POST['document'];
	
	$home_address = $_POST['home_address'][0];
	if($_POST['home_pin_code'][0]==''){
		$home_pin_code = "0";
	}else{
		$home_pin_code = $_POST['home_pin_code'][0];
	}	
	$ofy_address = $_POST['ofy_address'];
	if($_POST['ofy_pin_code']==''){$ofy_pin_code = "0";}else{$ofy_pin_code = $_POST['ofy_pin_code'];}
	
	
	$model_purchased = $_POST['model_purchased'][0];
	$serial_number = $_POST['serial_number'][0];
	
	$warranty_month = $_POST['warranty_month'];
	$amc_service = $_POST['amc_service'];
	
	//if($_POST['installation_date']==''){$inst_date = "0000-00-00";}else{$inst_date = $_POST['installation_date'];}
	
	if($_POST['from_date']==''){$from_date = "0000-00-00";}else{$from_date = $_POST['from_date'];}
	if($_POST['to_datee']==''){$to_date = "0000-00-00";}else{$to_date = $_POST['to_datee'];}
			
	$total_purchase = $_POST['total_purchase'];	
	$amount_recd_adv = $_POST['amount_recd_adv'];		

	//echo "<pre>";print_r($_POST);die;
	
	$chkEmp = select_query("SELECT * FROM $db_name.customer_details WHERE phone_no='".$number."' AND is_active='1' AND loginid='".$_SESSION['user_id']."' and id!='".$req_id."' ");
	
	//echo "<pre>";print_r($chkEmp);die;
	
	if(count($chkEmp)>0)
	{
		$_SESSION['unsuccess_msg'] = 'set';
		
	} else {
		
		$locationcheck = select_query("SELECT * from $db_name.location WHERE location like '".$ofy_address."%' LIMIT 1");
		//print_r($locationcheck);die;
		//echo $locationcheck[0]->latitude;die;
		
		if(count($locationcheck) > 0){
			
			$lat = $locationcheck[0]['latitude'];
			$lng = $locationcheck[0]['longitude'];
		
		} else{
			
			$address=str_replace(' ', '%20',$ofy_address);
			
			$latlng = googlatlang($address);
			//echo $latlng;die;
			$splitlatlng = explode("@", $latlng);
	
			$lat = (float)$splitlatlng[0];
			$lng = (float)$splitlatlng[1];
			
			$insert_lat_long = array('latitude' => $lat , 'longitude' => $lng, 'location' => $ofy_address, 'phone_no' => $number);
			$insert_loc = insert_query($db_name.'.location', $insert_lat_long);
			
		}
		
		for($ar=0;$ar<count($_POST["home_address"]);$ar++)
		{
			$loop_home_address= $_POST['home_address'][$ar];
			$home_locationcheck = select_query("SELECT * from $db_name.location WHERE location like '".$loop_home_address."%' LIMIT 1");
			
			if(count($home_locationcheck) > 0){
				
				$home_lat = $home_locationcheck[0]['latitude'];
				$home_lng = $home_locationcheck[0]['longitude'];
			
			} else{
				
				$hm_address=str_replace(' ', '%20',$loop_home_address);
				
				$home_latlng = googlatlang($hm_address);
				$splithomelatlng = explode("@", $home_latlng);
		
				$home_lat = (float)$splithomelatlng[0];
				$home_lng = (float)$splithomelatlng[1];
				
				$insert_home_lat_long = array('latitude' => $home_lat , 'longitude' => $home_lng, 'location' => $loop_home_address, 'phone_no' => $number);
				$insert_home_loc = insert_query($db_name.'.location', $insert_home_lat_long);
							
			}
		}
		
		/*$create_people = mysql_query("INSERT INTO people SET name = '".$name."', level = '".$level."', branch= '".$branch."', number= '".$number."',email= '".$email."',  user_id = '".$user_id."', status = '1', date = '".$current_date."'");*/
	
		$form_val = array('name' => $name, 'phone_no' => $number , 
			'company_name' => $organization_name , 'aadhar_no' => $aadhar_no, 'gender' => $gender, 'dob' => $birth_date , 
			'document_submit' => $document, 'home_address' => $home_address, 'home_pin_code' => $home_pin_code, 'home_latitude' => $home_lat, 
			'home_longitude' => $home_lng, 'ofy_address' => $ofy_address,  'ofy_pin_code' => $ofy_pin_code , 'ofy_latitude' => $lat, 
			'ofy_longtitude' => $lng, 'amc_tenure_from' => $from_date, 'amc_tenure_to' => $to_date, 'amc_no_of_service' => $amc_service, 
			'model_purchased' => $model_purchased, 'serial_no' => $serial_number, 'warranty_month' => $warranty_month, 
			'email_id' => $cust_email_id, 'total_purchase_amount' => $total_purchase, 'amount_recd_advance' => $amount_recd_adv, 'aadhar_img'=> $newFilepath);
			//echo "<pre>";print_r($form_val);die;
		$condition = array('id' => $req_id, 'loginid' => $_SESSION['user_id']);            
		$result = update_query($db_name.'.customer_details', $form_val, $condition);
		
		if(count($get_model_recd)>0)
		{
			select_query("DELETE FROM $db_name.customer_model_master WHERE cust_id='".$req_id."' and customer_id='".$get_cust_recd[0]['cust_id']."'
			and loginid='".$_SESSION['user_id']."' and is_active='1' ");
		}
		
		for($ar=0;$ar<count($_POST["model_purchased"]);$ar++)
		{			
			$insert_model = insert_query($db_name.'.customer_model_master', array('cust_id' => $req_id, 
			'customer_id' => $get_cust_recd[0]['cust_id'], 'model_purchased' => $_POST['model_purchased'][$ar], 
			'serial_no' => $_POST['serial_number'][$ar], 'home_address'=>$_POST['home_address'][$ar],'home_pin_code'=>$_POST['home_pin_code'][$ar], 'loginid' => $_SESSION['user_id']));
			
			//echo $insert_model;//echo "<br/>";
		}
		
		if($result) {
			$_SESSION['success_msg'] = 'update';
			echo "<script>window.location.href='view_customer.php'</script>";
	
			
		
		}
	
	}

}
?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view_customer.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Edit Customers</a> </div>
    
  </div>
  <div class="container-fluid">
   
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Edit Customers</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="myForm" id="myForm" action="" method="post" class="form-horizontal" autocomplete="off" enctype="multipart/form-data">
              <div class="alert alert-error error_display" style="display:none">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span id="print_err"></span>
			  </div>
			  
			  <?php if(isset($_SESSION['success_msg'])) {  ?>
				<div class="alert alert-success success_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Success!</strong><span> Succesfully added. Click <a href="view_customer.php">here</a> to View</span>
			  </div>
			  <?php } else if(isset($_SESSION['unsuccess_msg'])) {  ?>
				<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> Technician Phone No Already Exist. </span>
			  </div>
			  <?php }else if(isset($_SESSION['fileError'])) {  ?>
				<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> Aadhar file is required... </span>
			  </div>
			  <?php }else if(isset($_SESSION['fileSizeError'])) {  ?>
				<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> Aadhar file is too heavy... </span>
			  </div>
			  <?php }else if(isset($_SESSION['fileTypeError'])) {  ?>
				<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> Aadhar file should be PNG or JPG format... </span>
			  </div>
			  <?php } 
			  unset($_SESSION['success_msg']);
			  unset($_SESSION['unsuccess_msg']);
			  unset($_SESSION['fileError']);
			  unset($_SESSION['fileSizeError']);
			  unset($_SESSION['fileTypeError']);
			  ?>
			  <input type="hidden" name="req_id" id="req_id" value="<?php echo $get_cust_recd[0]['id'];?>"/>
			  <div class="control-group">
                <label class="control-label">Name:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="name" id="name" class="mandatory" placeholder="Name"  value="<?php echo $get_cust_recd[0]['name'];?>"/>
                  <span id="branch_error"></span> </div>
              </div>
 								
			 <div class="control-group">
                <label class="control-label">Mobile Number:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="number" maxlength="10" id="number"  class="mandatory" placeholder="Number" value="<?php echo $get_cust_recd[0]['phone_no'];?>" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Organization Name:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="organization_name" id="organization_name" class="mandatory" placeholder="Organization Name" value="<?php echo $get_cust_recd[0]['company_name'];?>"/>
                  <span id="branch_error"></span> </div>
              </div>
              
               <div class="control-group">
                <label class="control-label">Customer Email Id:</label>
                <div class="controls">
                  <input type="text" name="cust_email_id" id="cust_email_id"  class="mandatory" placeholder="Customer Email Id" value="<?php echo $get_cust_recd[0]['email_id'];?>" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Aadhar Number:</label>
                <div class="controls">
                  <input type="text" name="aadhar_no" id="aadhar_no" class="mandatory" placeholder="Aadhar Number " value="<?php echo $get_cust_recd[0]['aadhar_no'];?>"/>
                  <span id="branch_error"></span> <br>
				  <input type="file" name="myFile">
				  <input type="hidden" name="aadhar_hidden" value="<?php echo $get_cust_recd[0]['aadhar_img'];?>">
				  <?php if(isset($get_cust_recd[0]['aadhar_img'])){?>
				  <img src="<?php echo $get_cust_recd[0]['aadhar_img'];?>" style="width:100px;height:100px;">
				  <?php }?>
				</div>
              </div>              
                            
              <div class="control-group">
                <label class="control-label">Date of Birth:</label>
                <div class="controls date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="mandatory date-picker" name="birth_date" id="dateStart" type="text" value="<?php if($get_cust_recd[0]['dob']!='0000-00-00'){ echo $get_cust_recd[0]['dob'];}?>" placeholder="Date of Birth" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                
              </div>
              
              <div class="control-group">
                <label class="control-label">Gender:<font color="red">* </font></label>
                <div class="controls">
                  <input type="radio" name="gender" id="gender" class="mandatory" value="Male" <?php if($get_cust_recd[0]['gender']=='Male') {echo "checked=\"checked\""; }?>/> Male
                  <input type="radio" name="gender" id="gender" class="mandatory" value="Female" <?php if($get_cust_recd[0]['gender']=='Female') {echo "checked=\"checked\""; }?>/> Female
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Documents Submitted:</label>
                <div class="controls">
                  <input type="radio" name="document" id="document" class="mandatory" value="Yes" <?php if($get_cust_recd[0]['document_submit']=='Yes') {echo "checked=\"checked\""; }?>/> Yes
                  <input type="radio" name="document" id="document" class="mandatory" value="No" <?php if($get_cust_recd[0]['document_submit']=='No') {echo "checked=\"checked\""; }?>/> No
                  <span id="branch_error"></span> </div>
              </div>
                            
              <div class="control-group">
                <label class="control-label">Cold Room Adress:</label>
                <div class="controls">
                  <input type="text" name="home_address[]" id="autocomplete1" class="mandatory" placeholder="Address " value="<?php echo $get_cust_recd[0]['home_address'];?>"/>
                  <input type="text" name="home_pin_code[]" id="home_pin_code" class="mandatory" placeholder="PIN Code" value="<?php echo $get_cust_recd[0]['home_pin_code'];?>"/>
                  <span id="branch_error"></span> </div>
              </div>
              
               <div class="control-group">
              	<label class="control-label">Same as Above</label>
              	<div class="controls"><input type="checkbox" name="sameasabove" id="sameasabove" ></div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Office Address:</label>
                <div class="controls">
                  <input type="text" name="ofy_address" id="autocomplete" class="mandatory" placeholder="Address " value="<?php echo $get_cust_recd[0]['ofy_address'];?>"/>
                  <input type="text" name="ofy_pin_code" id="ofy_pin_code" class="mandatory" placeholder="PIN Code" value="<?php echo $get_cust_recd[0]['ofy_pin_code'];?>"/>
                  <span id="branch_error"></span> </div>
              </div>

            <!-- <div class="control-group">
                <label class="control-label">Office Timings:</label>
                <div class="controls date form_time" data-date="" data-date-format="hh:ii" data-link-field="dtp_input2" data-link-format="hh:ii">
                  <input class="mandatory date-picker" name="from_time" id="StartTime" type="text" value="" placeholder="From Time" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                <div class="controls date form_time" data-date="" data-date-format="hh:ii" data-link-field="dtp_input2" data-link-format="hh:ii">
                  <input class="mandatory date-picker" name="to_time" id="EndTime" type="text" value="" placeholder="To Time" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
              </div>-->
              
              <? if($action == 'editapp') { ?>
              <table style=" padding-left: 100px;width: 800px;" cellspacing="5" cellpadding="5">
                <tr>
                    <td colspan="2">
                    <div class="control-group">
                     <label class="control-label"></label>
                     <div class="controls">
                      <INPUT type="button" value="Add(+)" class="button" onClick="addRow('dataTable')" />
                
                      <INPUT type="button" value="Delete(-)" class="button" onClick="deleteRow('dataTable')" />
                      </div>
                    </div>
                  </td>
                </tr>
              </table>
              
              <table id="dataTable" style="padding-left: 100px;width: 800px;" cellspacing="5" cellpadding="5">
               <tr>
               	 <td colspan="2">   
                  <div class="control-group">
                      <label class="control-label"><font color="red">* </font></label>
                      <div class="controls">
                          <input type="text" name="model_purchased[]" id="model_purchased" class="mandatory" value="<?=$model_purchased?>" placeholder="Model Purchased " />
                          
                          <input type="text" name="serial_number[]" id="serial_number" class="mandatory" value="<?=$serial_number?>" placeholder="Serial Number " />
                      <span id="branch_error"></span> </div> 
                  </div>
                  
                 </td>
               </tr>
              </table>
              
              <? } else { ?>
              
              <table style=" padding-left: 100px;width: 800px;" cellspacing="5" cellpadding="5">
                <tr>
                    <td colspan="2">
                    <div class="control-group">
                     <label class="control-label"></label>
                     <div class="controls">
                      <INPUT type="button" value="Add(+)" class="button" onClick="addRow('dataTable')" />
                
                      <INPUT type="button" value="Delete(-)" class="button" onClick="deleteRow('dataTable')" />
                      </div>
                    </div>
                  </td>
                </tr>
              </table>
              
              <table id="dataTable" style="padding-left: 100px;width: 800px;" cellspacing="5" cellpadding="5">
               
			   <? if(count($get_model_recd)>0){
			   		for($ml=0;$ml<count($get_model_recd);$ml++)
					{
			   ?>
               <tr>
               	 <td colspan="2">   
                  <div class="control-group">
                      <label class="control-label"><font color="red">* </font></label>
                      <div class="controls">
                        <input type="text" name="model_purchased[]" id="model_purchased" class="mandatory" value="<?=$get_model_recd[$ml]["model_purchased"]?>" placeholder="Model Purchased " />
                          
                        <input type="text" name="serial_number[]" id="serial_number" class="mandatory" value="<?=$get_model_recd[$ml]["serial_no"]?>" placeholder="Serial Number " />
						  
						<input type="text" name="home_address[]" id="autocomplete1" class="mandatory" placeholder="Cool Room Address " value="<?=$get_model_recd[$ml]["home_address"]?>"/>
							
						<input type="text" name="home_pin_code[]" id="home_pin_code" class="mandatory" placeholder="Cool Room PIN Code" value="<?=$get_model_recd[$ml]["home_pin_code"]?>"/>
                      <span id="branch_error"></span> </div> 
                  </div>
                  
                 </td>
               </tr>
               <?  }
			   } else { 
			   ?>               
               <tr>
               	 <td colspan="2">   
                  <div class="control-group">
                      <label class="control-label"><font color="red">* </font></label>
                      <div class="controls">
                          <input type="text" name="model_purchased[]" id="model_purchased" class="mandatory" value="<?=$model_purchased?>" placeholder="Model Purchased " />
                          
                          <input type="text" name="serial_number[]" id="serial_number" class="mandatory" value="<?=$serial_number?>" placeholder="Serial Number " />
                      <span id="branch_error"></span> </div> 
                  </div>
                  
                 </td>
               </tr>
               
               <? } ?>
               
              </table>
             
             <? } ?>
             
             <!--<div class="control-group">
                <label class="control-label">Installation date &amp; handing over:<font color="red">* </font></label>
                <div class="controls date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="mandatory date-picker" name="installation_date" id="installation_date" type="text" value="<?php if($get_cust_recd[0]['date_of_installation']!='0000-00-00'){ echo $get_cust_recd[0]['date_of_installation'];}?>" placeholder="Installation date &amp; handing over " readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
              </div>-->
              
              <div class="control-group">
                <label class="control-label">Warranty:<font color="red">* </font></label>
                <div class="controls">
                  <select name="warranty_month" id="warranty_month" class="form-control">
                      <option value="">Warranty Months</option>
                      <option value="12" <?php if($get_cust_recd[0]['warranty_month'] == '12' ) { echo "selected='selected'";}?>>12 Months</option>
                      <option value="15" <?php if($get_cust_recd[0]['warranty_month'] == '15' ) { echo "selected='selected'";}?>>15 Months</option>
                      <option value="18" <?php if($get_cust_recd[0]['warranty_month'] == '18' ) { echo "selected='selected'";}?>>18 Months</option>
                      <!--<option value="21" <?php if($get_cust_recd[0]['warranty_month'] == '21' ) { echo "selected='selected'";}?>>21 Months</option>
                      <option value="24" <?php if($get_cust_recd[0]['warranty_month'] == '24' ) { echo "selected='selected'";}?>>24 Months</option>-->
                  </select>
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">No. of services in the AMC Period:<font color="red">* </font></label>
                <div class="controls">
                  <!--<input type="text" name="amc_service" id="amc_service" class="mandatory" placeholder="No. of services in the AMC Period  " />-->
                  <select name="amc_service" id="amc_service" class="form-control">
                      <option value="">No. of services in the AMC Period</option>
                      <option value="1" <?php if($get_cust_recd[0]['amc_no_of_service'] == '1' ) { echo "selected='selected'";}?>>1</option>
                      <option value="2" <?php if($get_cust_recd[0]['amc_no_of_service'] == '2' ) { echo "selected='selected'";}?>>2</option>
                      <option value="3" <?php if($get_cust_recd[0]['amc_no_of_service'] == '3' ) { echo "selected='selected'";}?>>3</option>
                      <option value="4" <?php if($get_cust_recd[0]['amc_no_of_service'] == '4' ) { echo "selected='selected'";}?>>4</option>
                      <option value="5" <?php if($get_cust_recd[0]['amc_no_of_service'] == '5' ) { echo "selected='selected'";}?>>5</option>
                      <option value="6" <?php if($get_cust_recd[0]['amc_no_of_service'] == '6' ) { echo "selected='selected'";}?>>6</option>
                  </select>
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Warranty tenure :</label>
                <div class="controls date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="mandatory date-picker" name="from_date" id="from_date" type="text" value="<?php if($get_cust_recd[0]['amc_tenure_from']!='0000-00-00'){ echo $get_cust_recd[0]['amc_tenure_from'];}?>" placeholder="From Date" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                <div class="controls date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="mandatory date-picker" name="to_datee" id="to_datee" type="text" value="<?php if($get_cust_recd[0]['amc_tenure_to']!='0000-00-00'){ echo $get_cust_recd[0]['amc_tenure_to'];}?>" placeholder="To Date" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
              </div>
              
              
              
              <!--<div class="control-group">
                <label class="control-label">AMC Tenure:</label>
                <div class="controls date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="mandatory date-picker" name="amc_date" id="amc_date" type="text" value="<?php if($get_cust_recd[0]['amc_dates']!='0000-00-00'){ echo $get_cust_recd[0]['amc_dates'];}?>" placeholder="Date of AMC " readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                
              </div>-->
              
              <div class="control-group">
                <label class="control-label">Total Purchase Amount:</label>
                <div class="controls">
                  <input type="text" name="total_purchase" id="total_purchase" class="mandatory" placeholder="Total Purchase Amount " value="<?php echo $get_cust_recd[0]['total_purchase_amount'];?>"/>
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Amount Received in Advance:</label>
                <div class="controls">
                  <input type="text" name="amount_recd_adv" id="amount_recd_adv" class="mandatory" placeholder="Amount Received in Advance " value="<?php echo $get_cust_recd[0]['amount_recd_advance'];?>"/>
                  <span id="branch_error"></span> </div>
              </div>
			  
              <div class="form-actions">
                <button type="submit" class="btn-harish btn-info-harish save_step_1" name="save_people">Save</button>
                <a  class="btn-harish btn-info-harish" href="view_customer.php" style="color: #fff;">Cancel</a>

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
		var name = $("#name").val();
		var number = $("#number").val();
		var toDate= $("#to_datee").val();
		var amcDate= $("#amc_date").val();
						
		if( name == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please enter name.");
			return false;
		}
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}	
		
		if( number == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please enter number.");
			return false;
		}
		else if(number != '')
		{			   
			var charnumber=number.length;
			if(charnumber < 10 || charnumber > 12 || number.search(/[^0-9\-()+]/g) != -1) {
				$(".error_display").css("display","block");
				$("#print_err").html(" Please enter valid number.");
				return false;
			}
		}
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}	
		
		var organization_name = $("#organization_name").val();
						
		if( organization_name == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please enter Organization Name.");
			return false;
		}
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}
		
		var genderlength = $('input[name="gender"]:checked').length;
		
		if( genderlength == 0) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select Gender.");
			return false;
		}
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}
		
		var DrowCount = $('#dataTable tr').length;
		//console.log(DrowCount);
		
		for(var m=0; m<DrowCount; m++)
		{
		  	if(m == 0)
			{
				var fcounter = 0;
				var fmodel_no = 'model_purchased';
				var fserial_no = 'serial_number';
			}
			else
			{
				var fmodel_no = 'model_purchased'+fcounter;
				var fserial_no = 'serial_number'+fcounter;
			}
		
		//console.log('Loop-'+m);
		
		  var modelChk = $('#'+fmodel_no).val();
		  var serialChk = $('#'+fserial_no).val();
		  //console.log(modelChk+'--'+serialChk);
		  
		  if(modelChk == '')
		  {
			    $(".error_display").css("display","block");
				$("#print_err").html(" Please Enter Model No.");
				return false;
		  }
		  else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		  }
		  
		  if(serialChk == '')
		  {
			    $(".error_display").css("display","block");
				$("#print_err").html(" Please Enter Serial No.");
				return false;
		  }	 
		  else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		  }
		 
		  m=m+1;
		  fcounter++;
		  
		}
		
		var warranty_month = $("#warranty_month").val();
						
		if( warranty_month == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select Warranty Months.");
			return false;
		}
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}
		
		var amc_ser = $("#amc_service").val();
						
		if( amc_ser == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select No. of services in the AMC Period.");
			return false;
		}
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}
		
		if(new Date(toDate) > new Date(amcDate))
		{
			$(".error_display").css("display","block");
			$("#print_err").html(" AMC start date should be equal to or greater than warranty end date");
			return false;
		}else{
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}
		
		/*var total_purc = $("#total_purchase").val();
						
		if( total_purc == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please enter Total Purchase Amount.");
			return false;
		}
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}*/
		
		/*var amount_recd = $("#amount_recd_adv").val();
						
		if( amount_recd == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please enter Amount Received in Advance.");
			return false;
		}
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}*/
		
	   
    });
	
	$('#myForm').on('keyup keypress', function(e) {
	  var keyCode = e.keyCode || e.which;
	  if (keyCode === 13) { 
		e.preventDefault();
		return false;
	  }
	});
	
	 $("#number").keydown(function (e) {
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
	
	$("#ofy_pin_code").keydown(function (e) {
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
	
	$("#home_pin_code").keydown(function (e) {
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
	
	$('input[name="sameasabove"]').click(function () {

		//alert("Thanks for checking me");
		var sameabove = $('input[name="sameasabove"]:checked').length
		//alert(sameabove);
		var homeaddress = $("#autocomplete1").val();
		var homepin = $("#home_pin_code").val();
		
		if(sameabove == 1)
		{
			if( homeaddress != '' ) {
				$("#autocomplete").val(homeaddress);
			}
			
			if( homepin != '' ) {
				$("#ofy_pin_code").val(homepin);
			}
		
		}
		
		//ofy_pin_code,autocomplete
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

var placeSearch, autocomplete, autocomplete1;
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

autocomplete1 = new google.maps.places.SearchBox(
	/** @type {!HTMLInputElement} */(document.getElementById('autocomplete1')),
	{types: ['geocode']});
// When the user selects an address from the dropdown, populate the address
// fields in the form.
autocomplete.addListener('place_changed', fillInAddress);
autocomplete1.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
// Get the place details from the autocomplete object.
var place = autocomplete.getPlace();
var place1 = autocomplete1.getPlace();

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
<SCRIPT language="javascript">
	
	var counter = 1;
	
	function addRow(tableID) 
	{
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
						
		if(rowCount>=10){
			alert("Only 10 Model allow");
			return false;
		}
		
		var row = table.insertRow(rowCount);
		var colCount = table.rows[0].cells.length;
		//console.log('colCount'+colCount);
		//return false;
		
		for(var i=0; i<colCount; i++) {

			var newcell	= row.insertCell(i);
			
			newcell.innerHTML = table.rows[0].cells[i].innerHTML;
			//console.log('childnode'+newcell.childNodes[0]);
			//console.log(newcell.childNodes[0].id);
			
			switch(i) {
								
				case 0:
					newcell.childNodes[0].value = "";
					newcell.childNodes[0].id = 'model_purchased' + counter ;									
					break;
			    case 1:
					newcell.childNodes[0].value = "";
					newcell.childNodes[0].id = 'serial_number' + counter ;									
					break;
						
				/*case "text":
						newcell.childNodes[0].value = "";
						newcell.childNodes[0].id = 'TxtDeviceType' + counter ;
						break;
				case "checkbox":
						newcell.childNodes[0].checked = false;
						break;
				case "select-one":
						newcell.childNodes[0].selectedIndex = 0;
						break;*/	
				
			}
			
		}
		
		counter++;
	}
	
	function deleteRow(tableID) {
	  try {
			var table = document.getElementById(tableID);
			var rowCount = table.rows.length;
			
			if(rowCount <= 1) {
				alert("Cannot delete all the rows.");
				return false;
			}
			if(rowCount > 1) {
				var row = table.rows[rowCount-1];
				table.deleteRow(rowCount-1);
				rowCount = rowCount-1;
				counter--;
			}
		}
	  catch(e) {
			//alert(e);
		}
	}
	
	
</SCRIPT>
<?php include('inc/footer.php');?>
