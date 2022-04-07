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
	$add = $jsondata["results"][0]["formatted_address"];
	/*echo "<script type='text/javascript'>alert('".$lat."');</script>";*/
  return $lat."@".$lng."@".$add;
  
}

if (isset($_POST['save_people'])) {
	
	//echo "<pre>";print_r($_POST);die;
	
	$name = $_POST['name'];
	$number = $_POST['number'];
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

	//echo "<pre>";print_r($_POST);die;
	
	$chkEmp = select_query("SELECT * FROM $db_name.technicians_login_details WHERE mobile_no='".$number."' AND is_active='1'");
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
		
		$home_locationcheck = select_query("SELECT * from $db_name.location WHERE location like '".$home_address."%' LIMIT 1");
		
		if(count($home_locationcheck) > 0){
			
			$home_lat = $home_locationcheck[0]['latitude'];
			$home_lng = $home_locationcheck[0]['longitude'];
		
		} else{
			
			$hm_address=str_replace(' ', '%20',$home_address);
			
			$home_latlng = googlatlang($hm_address);
			$splithomelatlng = explode("@", $home_latlng);
	
			$home_lat = (float)$splithomelatlng[0];
			$home_lng = (float)$splithomelatlng[1];
			
			$insert_home_lat_long = array('latitude' => $home_lat , 'longitude' => $home_lng, 'location' => $home_address, 'phone_no' => $number);
			$insert_home_loc = insert_query($db_name.'.location', $insert_home_lat_long);
						
		}
		
		/*$create_people = mysql_query("INSERT INTO people SET name = '".$name."', level = '".$level."', branch= '".$branch."', number= '".$number."',email= '".$email."',  user_id = '".$user_id."', status = '1', date = '".$current_date."'");*/
		
		$insert_query = insert_query($db_name.'.technicians_login_details', array('emp_name' => $name, 'mobile_no' => $number , 
		'technician_id' => $technician_id , 'aadhar_no' => $aadhar_no, 'gender' => $gender, 'dob' => $birth_date , 'document_submit' => $document,
		'home_address' =>$home_address, 'home_pin_code' => $home_pin_code, 'home_latitude' => $home_lat, 'home_longitude' => $home_lng,
		'ofy_address' =>$ofy_address,  'ofy_pin_code' => $ofy_pin_code , 'ofy_latitude' => $lat, 'ofy_longtitude' => $lng,  
		'ofy_from_time' =>$from_time, 'ofy_to_time' => $to_time,  'specialization' => $specialization , 'date_of_joining' => $joining_date ,
		'monthly_salary' => $monthly_salary ));
		
		if($insert_query) {

			echo "<script>window.location.href='view_technicians.php'</script>";
	
			$_SESSION['success_msg'] = 'set';
		
		}
	
	}

}
?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view_technicians.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Add Technicians</a> </div>
    
  </div>
  <div class="container-fluid">
   
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add Technicians</h5>
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
                <strong class="error_submission">Success!</strong><span> Succesfully added. Click <a href="view_technicians.php">here</a> to View</span>
			  </div>
			  <?php } else if(isset($_SESSION['unsuccess_msg'])) {  ?>
				<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> Technician Phone No Already Exist. </span>
			  </div>
			  <?php } 
			  unset($_SESSION['success_msg']);
			  unset($_SESSION['unsuccess_msg']);
	
			  ?>
			  
			  <div class="control-group">
                <label class="control-label">Name:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="name" id="name" class="mandatory" placeholder="Name" value="<?=$name;?>" />
                  <span id="branch_error"></span> </div>
              </div>
 								
			 <div class="control-group">
                <label class="control-label">Mobile Number:<font color="red">* </font></label>
                <div class="controls">
                  <input type="text" name="number" maxlength="10" id="number"  class="mandatory" placeholder="Number" value="<?=$number;?>" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Technician Id:</label>
                <div class="controls">
                  <input type="text" name="technician_id" id="technician_id" class="mandatory" placeholder="Technician Id" value="<?=$technician_id;?>" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Aadhar Number:</label>
                <div class="controls">
                  <input type="text" name="aadhar_no" id="aadhar_no" class="mandatory" placeholder="Aadhar Number " value="<?=$aadhar_no;?>" />
                  <span id="branch_error"></span> </div>
              </div>              
                            
              <div class="control-group">
                <label class="control-label">Date of Birth:</label>
                <div class="controls date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="mandatory date-picker" name="birth_date" id="dateStart" type="text" value="<?=$_POST['birth_date'];?>" placeholder="Date of Birth" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                
              </div>
              
              <div class="control-group">
                <label class="control-label">Gender:<font color="red">* </font></label>
                <div class="controls">
                  <input type="radio" name="gender" id="gender" class="mandatory" value="Male" <?php if($gender=='Male') {echo "checked=\"checked\""; }?>/> Male
                  <input type="radio" name="gender" id="gender" class="mandatory" value="Female" <?php if($gender=='Female') {echo "checked=\"checked\""; }?>/> Female
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Documents Submitted:</label>
                <div class="controls">
                  <input type="radio" name="document" id="document" class="mandatory" value="Yes" <?php if($document=='Yes') {echo "checked=\"checked\""; }?>/> Yes
                  <input type="radio" name="document" id="document" class="mandatory" value="No" <?php if($document=='No') {echo "checked=\"checked\""; }?>/> No
                  <span id="branch_error"></span> </div>
              </div>
                            
              <div class="control-group">
                <label class="control-label">Home Adress:</label>
                <div class="controls">
                  <input type="text" name="home_address" id="autocomplete1" class="mandatory" placeholder="Address " value="<?=$home_address;?>"  />
                  <input type="text" name="home_pin_code" id="home_pin_code" class="mandatory" placeholder="PIN Code" value="<?=$_POST['home_pin_code'];?>" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
              	<label class="control-label">Same as Above</label>
              	<div class="controls"><input type="checkbox" name="sameasabove" id="sameasabove" ></div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Office Address:</label>
                <div class="controls">
                  <input type="text" name="ofy_address" id="autocomplete" class="mandatory" placeholder="Address " value="<?=$ofy_address;?>" />
                  <input type="text" name="ofy_pin_code" id="ofy_pin_code" class="mandatory" placeholder="PIN Code" value="<?=$_POST['ofy_pin_code'];?>" />
                  <span id="branch_error"></span> </div>
              </div>

             <div class="control-group">
                <label class="control-label">Office Timings:</label>
                <div class="controls date form_time" data-date="" data-date-format="hh:ii" data-link-field="dtp_input2" data-link-format="hh:ii">
                  <input class="mandatory date-picker" name="from_time" id="StartTime" type="text" value="<?=$_POST['from_time'];?>" placeholder="From Time" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                <div class="controls date form_time" data-date="" data-date-format="hh:ii" data-link-field="dtp_input2" data-link-format="hh:ii">
                  <input class="mandatory date-picker" name="to_time" id="EndTime" type="text" value="<?=$_POST['to_time'];?>" placeholder="To Time" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Specialization:</label>
                <div class="controls">
                  <input type="text" name="specialization" id="specialization" class="mandatory" placeholder="Specialization " value="<?=$specialization;?>" />
                  <span id="branch_error"></span> </div>
              </div>
             
             
             <div class="control-group">
                <label class="control-label">Date of Joining:</label>
                <div class="controls date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="mandatory date-picker" name="joining_date" id="joining_date" type="text" value="<?=$_POST['joining_date'];?>" placeholder="Date of Joining " readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                
                <!--<div class="controls">
                  <input type="text" name="joining_date" id="joining_date" class="mandatory" placeholder="Date of Joining *" />
                  <span id="branch_error"></span> </div>-->
              </div>
              
              <div class="control-group">
                <label class="control-label">Monthly Salary:</label>
                <div class="controls">
                  <input type="text" name="monthly_salary" id="monthly_salary" class="mandatory" placeholder="Monthly Salary " value="<?=$monthly_salary;?>" />
                  <span id="branch_error"></span> </div>
              </div>
			  
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
		//alert('Hii');
		var name = $("#name").val();
		var number = $("#number").val();
		
						
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
		
				
		var fromTime = $("#StartTime").val();
		var toTime = $("#EndTime").val();
		
		if((fromTime != '' && toTime != '') && (fromTime != null && toTime != null)) {
			
			if(fromTime>=toTime)
			{
			    $(".error_display").css("display","block");
				$("#print_err").html(" Office To Time always greater then From Time.");
				return false;
			}
			
		}
				
		if((fromTime != '' && toTime == '' && fromTime != null)) {
			
			    $(".error_display").css("display","block");
				$("#print_err").html(" Place Select Office To Time.");
				return false;
			
		}
		
		if((fromTime == '' && toTime != '' && toTime != null)) {
			
			    $(".error_display").css("display","block");
				$("#print_err").html(" Place Select Office From Time.");
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
