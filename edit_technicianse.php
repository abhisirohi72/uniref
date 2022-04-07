<?php 
include('inc/header.php');

$req_id = base64_decode($_REQUEST['id']);

$get_emp_recd = select_query("SELECT * FROM $db_name.technicians_login_details WHERE id='".$req_id."' and loginid='".$_SESSION['user_id']."' ");
//echo "<pre>";print_r($get_emp_recd);die;

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


if (isset($_POST['save_people'])) {
	
	//echo "<pre>";print_r($_POST);die;
	
	$name = $_POST['name'];
	$number = $_POST['number'];
	//$status = $_POST['status'];
	$req_id = $_POST['req_id'];
	
	$technician_id = $_POST['technician_id'];
	$aadhar_no = $_POST['aadhar_no'];
	if($_POST['birth_date']==''){$birth_date = "0000-00-00";}else{$birth_date = $_POST['birth_date'];}	
	$gender = $_POST['gender'];
	$document = $_POST['document'];
	
	$home_address = $_POST['home_address'];
	if($_POST['home_pin_code']==''){$home_pin_code = "0";}else{$home_pin_code = $_POST['home_pin_code'];}	
	$ofy_address = $_POST['ofy_address'];
	if($_POST['ofy_pin_code']==''){$ofy_pin_code = "0";}else{$ofy_pin_code = $_POST['ofy_pin_code'];}
	if($_POST['from_time']==''){$from_time = "00:00:00";}else{$from_time = $_POST['from_time'];}
	if($_POST['to_time']==''){$to_time = "00:00:00";}else{$to_time = $_POST['to_time'];}
	
	$specialization = $_POST['specialization'];
	if($_POST['joining_date']==''){$joining_date = "0000-00-00";}else{$joining_date = $_POST['joining_date'];}	
	$monthly_salary = $_POST['monthly_salary'];	
	
	
	$chkEmp = select_query("SELECT * FROM $db_name.technicians_login_details WHERE mobile_no='".$number."' AND is_active='1' AND loginid='".$_SESSION['user_id']."' and id!='".$req_id."' ");
	
	if(count($chkEmp)>0)
	{
		$_SESSION['unsuccess_msg'] = 'set';
		
	} else {
		
		$locationcheck = select_query("SELECT * from $db_name.location WHERE location like '".$ofy_address."%' LIMIT 1");
		//echo "<pre>";print_r($locationcheck);die;
		
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
		
		$home_locationcheck = select_query("SELECT * from $db_name.location WHERE location like '".$home_address."%' LIMIT 1");
		//echo "<pre>";print_r($home_locationcheck);die;
		if(count($home_locationcheck) > 0){
			
			$home_lat = $home_locationcheck[0]['latitude'];
			$home_lng = $home_locationcheck[0]['longitude'];
		
		} else{
			
			$home_address1=str_replace(' ', '%20',$home_address);
			
			$home_latlng = googlatlang($home_address1);
			$splithomelatlng = explode("@", $home_latlng);
	
			$home_lat = (float)$splithomelatlng[0];
			$home_lng = (float)$splithomelatlng[1];
			
			$insert_home_lat_long = array('latitude' => $home_lat , 'longitude' => $home_lng, 'location' => $home_address, 'phone_no' => $number);
			$insert_home_loc = insert_query($db_name.'.location', $insert_home_lat_long);
						
		}
		
		
		$form_val = array('emp_name' => $name, 'mobile_no' => $number, 'technician_id' => $technician_id, 'aadhar_no' => $aadhar_no, 
		'gender' => $gender, 'dob' => $birth_date , 'document_submit' => $document, 'home_address' => $home_address, 
		'home_pin_code' => $home_pin_code , 'home_latitude' => $home_lat, 'home_longitude' => $home_lng, 'ofy_address' => $ofy_address, 
		'ofy_pin_code' => $ofy_pin_code, 'ofy_latitude' => $lat, 'ofy_longtitude' => $lng, 'ofy_from_time' =>$from_time, 
		'ofy_to_time' => $to_time, 'specialization' => $specialization , 'date_of_joining' => $joining_date, 'monthly_salary' => $monthly_salary);
		//echo "<pre>";print_r($form_val);die;
		$condition = array('id' => $req_id, 'loginid' => $_SESSION['user_id']);            
		$result = update_query($db_name.'.technicians_login_details', $form_val, $condition);
		
		
		/*if($status==0)
		{
			$chkEmpJobs = select_query("SELECT * FROM $db_name.request WHERE phone_no='".$number."' AND emp_id='".$req_id."' AND current_record=0 AND is_active='1' AND login_id='".$_SESSION['user_id']."' ");
			
			if(count($chkEmpJobs)>0)
			{
				select_query("Update $db_name.request set current_record='2', back_reason_date='".date("Y-m-d H:i:s")."',
					back_reason='Employee Deactivated' WHERE phone_no='".$number."' AND emp_id='".$req_id."' AND current_record=0 AND 
					is_active='1' AND login_id='".$_SESSION['user_id']."'");
				
				select_query("Update $db_name.installer_time_extension set is_active='0', job_close_time=NOW() WHERE 
				phone_no='".$number."' AND  inst_id='".$req_id."' AND is_active='1' AND branch_id='".$_SESSION['user_id']."' ");
				
				select_query("insert into request_reject_details select * from $db_name.request where  phone_no='".$number."' AND 
				emp_id='".$req_id."' AND current_record=0 AND is_active='1' AND login_id='".$_SESSION['user_id']."'");
			
			}
			
		}*/
		
		/*$create_people = mysql_query("UPDATE people SET name = '".$name."', number= '".$number."', email='".$email."', level='".$level."', status = '".$status."' WHERE id = '".base64_decode($_GET['id'])."'");*/
		if($result) {
		
			$_SESSION['success_msg'] = 'set';
			 echo "<script>window.location.href='view_technicians.php'</script>";
	
		
		}
	
	}

}


?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view_technicians.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Edit Technicians</a> </div>
    
  </div>
  <div class="container-fluid">
   
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Edit Technicians</h5>
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
                <strong class="error_submission">Success!</strong><span> Succesfully Updated. Click <a href="view_technicians.php">here</a> to View</span>
			  </div>
              <?php } else if(isset($_SESSION['unsuccess_msg'])) {  ?>
				<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> Technician Phone No Already Active. </span>
			  </div>
			  <?php } 
			  unset($_SESSION['success_msg']);
			  unset($_SESSION['unsuccess_msg']);
			  ?>
			  	<input type="hidden" name="req_id" id="req_id" value="<?php echo $get_emp_recd[0]['id'];?>"/>
				<div class="control-group">
                <label class="control-label">Name:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="name" id="name" class="mandatory" value="<?php echo $get_emp_recd[0]['emp_name'];?>" placeholder="Name" />
                  <span id="branch_error"></span> </div>
              </div>
 				
				
				
				<div class="control-group">
                <label class="control-label">Mobile Number:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="number" maxlength="10" id="number" class="mandatory" value="<?php echo $get_emp_recd[0]['mobile_no'];?>" placeholder="Number" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Technician Id:</label>
                <div class="controls">
                  <input type="text" name="technician_id" id="technician_id" class="mandatory" value="<?php echo $get_emp_recd[0]['technician_id'];?>" placeholder="Technician Id" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Aadhar Number:</label>
                <div class="controls">
                  <input type="text" name="aadhar_no" id="aadhar_no" class="mandatory" value="<?php echo $get_emp_recd[0]['aadhar_no'];?>" placeholder="Aadhar Number " />
                  <span id="branch_error"></span> </div>
              </div>
                           
              
              <div class="control-group">
                <label class="control-label">Date of Birth:</label>
                <div class="controls date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="mandatory date-picker" name="birth_date" id="dateStart" type="text" value="<?php if($get_emp_recd[0]['dob']!='0000-00-00'){ echo $get_emp_recd[0]['dob'];}?>" placeholder="Date of Birth" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                
              </div>
              
              <div class="control-group">
                <label class="control-label">Gender:<font color="red">* </font></label>
                <div class="controls">
                  <input type="radio" name="gender" id="gender" class="mandatory" value="Male" <?php if($get_emp_recd[0]['gender']=='Male') {echo "checked=\"checked\""; }?>/> Male
                  <input type="radio" name="gender" id="gender" class="mandatory" value="Female" <?php if($get_emp_recd[0]['gender']=='Female') {echo "checked=\"checked\""; }?>/> Female
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Documents Submitted:</label>
                <div class="controls">
                  <input type="radio" name="document" id="document" class="mandatory" value="Yes" <?php if($get_emp_recd[0]['document_submit']=='Yes') {echo "checked=\"checked\""; }?>/> Yes
                  <input type="radio" name="document" id="document" class="mandatory" value="No" <?php if($get_emp_recd[0]['document_submit']=='No') {echo "checked=\"checked\""; }?>/> No
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Home Adress:</label>
                <div class="controls">
                  <input type="text" name="home_address" id="autocomplete1" class="mandatory" placeholder="Address " value="<?php echo $get_emp_recd[0]['home_address'];?>" />
                  <input type="text" name="home_pin_code" id="home_pin_code" class="mandatory" value="<?php echo $get_emp_recd[0]['home_pin_code'];?>" placeholder="PIN Code" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
              	<label class="control-label">Same as Above</label>
              	<div class="controls"><input type="checkbox" name="sameasabove" id="sameasabove" ></div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Office Address:</label>
                <div class="controls">
                  <input type="text" name="ofy_address" id="autocomplete" class="mandatory" placeholder="Address "  value="<?php echo $get_emp_recd[0]['ofy_address'];?>"/>
                  <input type="text" name="ofy_pin_code" id="ofy_pin_code" class="mandatory" placeholder="PIN Code" value="<?php echo $get_emp_recd[0]['ofy_pin_code'];?>" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Office Timings:</label>
                <div class="controls date form_time" data-date="" data-date-format="hh:ii" data-link-field="dtp_input2" data-link-format="hh:ii">
                  <input class="mandatory date-picker" name="from_time" id="StartTime" type="text" value="<?php if($get_emp_recd[0]['ofy_from_time']!='00:00:00'){ echo $get_emp_recd[0]['ofy_from_time'];}?>" placeholder="Time" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                <div class="controls date form_time" data-date="" data-date-format="hh:ii" data-link-field="dtp_input2" data-link-format="hh:ii">
                  <input class="mandatory date-picker" name="to_time" id="EndTime" type="text" value="<?php if($get_emp_recd[0]['ofy_to_time']!='00:00:00'){ echo $get_emp_recd[0]['ofy_to_time'];}?>" placeholder="Time" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Specialization:</label>
                <div class="controls">
                  <input type="text" name="specialization" id="specialization" class="mandatory" value="<?php echo $get_emp_recd[0]['specialization'];?>" placeholder="Specialization " />
                  <span id="branch_error"></span> </div>
              </div>
             
             
             <div class="control-group">
                <label class="control-label">Date of Joining:</label>
                <div class="controls date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="mandatory date-picker" name="joining_date" id="joining_date" type="text" value="<?php if($get_emp_recd[0]['date_of_joining']!='0000-00-00'){ echo $get_emp_recd[0]['date_of_joining'];}?>" placeholder="Date of Joining " readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                
                <!--<div class="controls">
                  <input type="text" name="joining_date" id="joining_date" class="mandatory" placeholder="Date of Joining *" />
                  <span id="branch_error"></span> </div>-->
              </div>
              
              <div class="control-group">
                <label class="control-label">Monthly Salary:</label>
                <div class="controls">
                  <input type="text" name="monthly_salary" id="monthly_salary" class="mandatory" value="<?php echo $get_emp_recd[0]['monthly_salary'];?>" placeholder="Monthly Salary " />
                  <span id="branch_error"></span> </div>
              </div>
			  
			  <!--<div class="control-group">
                <label class="control-label">Status:</label>
                <div class="controls">
                  <select name="status" id="status">
				  <option <?php if($get_emp_recd[0]['is_active'] == '1' ) { echo "selected='selected'";}?> value="1">Active</option>
				  <option <?php if($get_emp_recd[0]['is_active'] == '0' ) { echo "selected='selected'";}?> value="0">Deactive</option>
				  </select>
                  <span id="branch_error"></span> </div>
              </div>-->
			  
              <div class="form-actions">
                <button type="submit" class="btn-harish btn-info-harish save_step_1" name="save_people">Save</button>
                <a  class="btn-harish btn-info-harish" href="view_technicians.php" style="color: #fff;">Cancel</a>

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
	
		var name = $("#name").val();
		var number = $("#number").val();
		
		var fromTime = $("#StartTime").val();
		var toTime = $("#EndTime").val();
		
		if( name == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Name.");
			return false;
		}
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}	
		
		if( number == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Number.");
			return false;
		}
		else if(number != '')
		{			   
			var charnumber=number.length;
			if(charnumber < 10 || charnumber > 12 || number.search(/[^0-9\-()+]/g) != -1) {
				$(".error_display").css("display","block");
				$("#print_err").html(" Please Enter Valid Number.");
				return false;
			}
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
		
		if((fromTime != '' && toTime != '') && (fromTime != null && toTime != null)) {
			
			if(fromTime>=toTime)
			{
			    $(".error_display").css("display","block");
				$("#print_err").html(" Place of Work To Time always greater then From Time.");
				return false;
			}
			
		}	
		
		if((fromTime != '' && toTime == '' && fromTime != null)) {
			
			    $(".error_display").css("display","block");
				$("#print_err").html(" Place Select Place of Work To Time.");
				return false;
			
		}
		
		if((fromTime == '' && toTime != '' && toTime != null)) {
			
			    $(".error_display").css("display","block");
				$("#print_err").html(" Place Select Place of Work From Time.");
				return false;
			
		}	   
	   
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
        showMeridian: 1
    });
    $('.form_date').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
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
<?php include('inc/footer.php');?>
