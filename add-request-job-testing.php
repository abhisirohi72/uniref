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

if (isset($_POST['save_people'])) {
	
	//echo "<pre>";print_r($_POST);die;
	$phone_empid = $_POST['phone_number'];
	$emp_phone=explode("##",$phone_empid);
		
	$phone=$emp_phone[0];
	$emp_id=$emp_phone[1];
	
	$location = $_POST['location'];
	$client_name = $_POST['client_name'];
	$contact_person = $_POST['contact_person'];
	$contact_person_mobile = $_POST['contact_person_mobile'];
	
	if($_POST['fromtime']!='' && $_POST['totime']!='')
	{
		$fromtime = $_POST['fromtime'];
		$totime = $_POST['totime'];
	}
	else if($_POST['fromtime']!='' && $_POST['totime']=='')
	{
		$fromtime = $_POST['fromtime'];
		$totime = date("Y-m-d",strtotime($_POST['fromtime']))." 23:59:59";
	}
	else if($_POST['fromtime']=='' && $_POST['totime']!='')
	{
		$fromtime = date("Y-m-d",strtotime($_POST['totime']))." 00:00:00";
		$totime = $_POST['totime'];
	}
	else if($_POST['fromtime']=='' && $_POST['totime']=='')
	{
		$fromtime = date("Y-m-d")." 00:00:00";
		$totime = date("Y-m-d")." 23:59:59";
	}
	
	/*if($_POST['fromtime']==''){}else{$fromtime = $_POST['fromtime'];}
	if($_POST['totime']==''){$totime = date("Y-m-d")." 23:59:59";}else{$totime = $_POST['totime'];}*/
	
	/*$fromtime = $_POST['fromtime'];
	$totime = $_POST['totime'];*/
	
	$lastId = select_query("SELECT id FROM $employee_track.request order by id desc limit 1");
	
	$last = $lastId[0]['id']+1;
	$job = "JOB00".$last;
	
	$locationcheck = select_query("SELECT * from $employee_track.location WHERE location like '".$location."%' LIMIT 1");
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
		$insert_loc = insert_query($employee_track.'.location', $insert_lat_long);
		
		/*$lat = '00.0000000';
		$lng = '00.0000000';*/
		
	}
	
	$cur_date= date('Y-m-d 00:00:00');
	
	if($client_name!='' && $contact_person!='' && $contact_person_mobile!='')
	{
		$duplicate_chk = select_query("SELECT * FROM $employee_track.request WHERE phone_no='".$phone."' and emp_id='".$emp_id."' and 
			fromtime='".$fromtime."' and totime='".$totime."' and client_name='".$client_name."' and contactname='".$contact_person."' and 
			contactno='".$contact_person_mobile."' and job_location='".$location."%' and login_id='".$_SESSION['user_id']."' ");
		//echo "<pre>";print_r($duplicate_chk);die;
	} else {
		$duplicate_chk = select_query("SELECT * FROM $employee_track.request WHERE phone_no='".$phone."' and emp_id='".$emp_id."' and 
			fromtime='".$fromtime."' and totime='".$totime."' and job_location='".$location."%' and login_id='".$_SESSION['user_id']."' ");
		//echo "<pre>";print_r($duplicate_chk);die;
	}
	
	
	if(count($duplicate_chk)>0)
	{
		$_SESSION['unsuccess_msg'] = 'set';
		
	} else {

		
		$job_sequence = select_query("select max(sequence_no) as sequence_no from $employee_track.request where phone_no='".$phone."' and 
		emp_id='".$emp_id."' and sequence_date='".date("Y-m-d", strtotime($fromtime))."'");
		 
		 //echo "<pre>";print_r($job_sequence);die;
		  
		if($job_sequence[0]['sequence_no']!='') {
			$sequence_no = $job_sequence[0]['sequence_no']+1;
		} else {
			$sequence_no = 0+1;
		}
		
		$check_data = select_query("SELECT * FROM $employee_track.request where phone_no='".$phone."' and emp_id='".$emp_id."' and job_type='1' 
		and current_record=0 and date(fromtime)='".date("Y-m-d", strtotime($fromtime))."' ");
		
		 //echo "<pre>";print_r($check_data);die;
		
		if(count($check_data)>0){	 
			 
			$insert_array = array('job_id' => $job , 'emp_id' => $emp_id, 'phone_no' => $phone, 'client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 2, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)), 'login_id' => $_SESSION['user_id']);
			
			$insert_query = insert_query($employee_track.'.request', $insert_array);
						
		}else{
			
			$insert_array = array('job_id' => $job , 'emp_id' => $emp_id, 'phone_no' => $phone, 'client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 1, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)), 'login_id' => $_SESSION['user_id']);
			
			$insert_query = insert_query($employee_track.'.request', $insert_array);
						
		}
		
		
		/*$tokenResult = select_query("SELECT token FROM $employee_track.login_emp_details where mobile_no='".$phone."' and is_active='1'");
	
		$tokens[] = $tokenResult[0]['token'];

		$Notificato_msg = array("data" => "New Job Allocated");
 
		$androidkey = "AIzaSyDpny2fcVd0GRwCxeb935DQzx-c6WXrsxk";

		$message_status = $this->send_notification_android2($tokens,$Notificato_msg,$androidkey);*/
		
					
		
	
		if($insert_query) {

			echo "<script>window.location.href='view-request-job.php'</script>";
	
		
			$_SESSION['success_msg'] = 'set';
		
		}
	
	}

}
?>

<link rel="stylesheet" href="<? echo __SITE_URL?>/css/bootstrap-select.css">

 
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Add Job Request</a> </div>
    
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add Job Request</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="form1" action="" method="post" class="form-horizontal" autocomplete="off" onsubmit="checkLocation()">
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
			  <?php } 
			  unset($_SESSION['success_msg']);
			  unset($_SESSION['unsuccess_msg']);
			  
			  $get_emp_recd = select_query("SELECT id,concat(mobile_no,' - ',emp_name) as emp_name,concat(mobile_no,'##',id) as emp_details FROM $employee_track.login_emp_details WHERE is_active='1' and loginid='".$_SESSION['user_id']."' ");
			  
			  ?>
			  
				<div class="control-group">
                <label class="control-label">Phone Number:</label>
                <div class="controls"> 
                    <div class="col-sm-3">
                        <select class="selectpicker pull-right" data-live-search="true" title="Select Phone Number" name="phone_number" id="phone_number">
                          <!--<option value="">Select Phone Number</option>-->
                        <?php for($rq=0;$rq<count($get_emp_recd);$rq++) { ?>
                          <option value="<?=$get_emp_recd[$rq]['emp_details'];?>"><?=$get_emp_recd[$rq]['emp_name'];?></option>
                          <!--<input type="text" name="number" maxlength="10" id="number"  class="mandatory" placeholder="Number *" />-->
                        <? } ?>
                        </select>
                        <span id="branch_error"></span> </div>
                  </div>
              </div>
              
               <div class="control-group">
                <label class="control-label">Location:</label>
                
                <div class="controls"  id="dataTable">
                	<INPUT type="button" value="Add(+)" class="button" onClick="addRow('dataTable')" />
                    <INPUT type="button" value="Delete(-)" class="button" onClick="deleteRow('dataTable')" />
                    
                  <table id="dataTable" style="padding-left: 100px;width: 800px;" cellspacing="5" cellpadding="5">
                  <input type="text" name="location" id="autocomplete" class="mandatory" placeholder="Location *" />
                  <span id="branch_error"></span> </div>
                  
                <!--<div class="controls">
                  <input type="text" name="location" id="autocomplete" class="mandatory" placeholder="Location *" />
                  <span id="branch_error"></span> </div>-->
              </div>
              
              <div class="control-group">
                <label class="control-label">From time:</label>
                <div class="controls date form_datetime" data-date="" data-date-format="yyyy-mm-dd hh:ii" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii">
                  <input class="mandatory date-picker" name="fromtime" id="dateStart" type="text" value="" placeholder="From Time" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                <!--<div class="controls">
                  <input type="text" name="client_name" maxlength="10" id="client_name"  class="mandatory" placeholder="Client Name *" />
                  <span id="branch_error"></span> </div>-->
              </div>
              
              <div class="control-group">
                <label class="control-label">To time:</label>
                <div class="controls date form_datetime" data-date="" data-date-format="yyyy-mm-dd hh:ii" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii">
                   <input class="mandatory date-picker" name="totime" id="dateEnd" type="text" value=""  placeholder="To Time" readonly>
                   <span class="add-on"><i class="icon-th"></i></span>
               </div>
                <!--<div class="controls">
                  <input type="text" name="client_name" maxlength="10" id="client_name"  class="mandatory" placeholder="Client Name *" />
                  <span id="branch_error"></span> </div>-->
              </div>
 								
			  <div class="control-group">
                <label class="control-label">Client Name:</label>
                <div class="controls">
                  <input type="text" name="client_name" id="client_name"  class="mandatory" placeholder="Client Name" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Contact Person:</label>
                <div class="controls">
                  <input type="text" name="contact_person" id="contact_person"  class="mandatory" placeholder="Contact Person" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Contact Person No:</label>
                <div class="controls">
                  <input type="text" name="contact_person_mobile" maxlength="10" id="contact_person_mobile"  class="mandatory" placeholder="Contact Person No" />
                  <span id="branch_error"></span> </div>
              </div>
              
			  
              <div class="form-actions">
                <button type="submit" class="btn btn-success save_step_1" name="save_people">Save</button>
                <a  class="btn btn-danger" href="view-request-job.php" style="color: #fff;">Cancel</a>

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
	  
$( document ).ready(function(){

////////////////////////// Validation ////////////////////////
	
    $('.save_step_1').click(function(e) {
	
		var phone_number = $("#phone_number").val();
		if( phone_number == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select Phone No.");
			return false;
		}
		
		var location = $("#autocomplete").val();
		if( location == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Location.");
			return false;
		}
		
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
	
	
	 $("#contact_person_mobile").keydown(function (e) {
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
<SCRIPT language="javascript">
	
	var counter = 1;
	
	function addRow(tableID) 
	{
		alert(tableID);
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
						
		if(rowCount>10){
			alert("Only 10 Location Row allow");
			return false;
		}
		
		var row = table.insertRow(rowCount);
		var colCount = table.rows[0].cells.length;
		//console.log('colCount'+colCount2);
		
		for(var i=0; i<colCount; i++) {

			var newcell	= row.insertCell(i);
			
			newcell.innerHTML = table.rows[0].cells[i].innerHTML;
			//console.log('childnode'+newcell.childNodes[0]);
			//console.log(newcell.childNodes[0].type);
			switch(i) {
				
				case 1:
					//newcell.childNodes[0].selectedIndex = 0;
					newcell.childNodes[0].id = 'autocomplete' + counter ;	
					break;
				
			
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
autocomplete = new google.maps.places.Autocomplete(
	/** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
	{types: ['geocode']});

// When the user selects an address from the dropdown, populate the address
// fields in the form.
autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
// Get the place details from the autocomplete object.
var place = autocomplete.getPlace();

for (var component in componentForm) {
  document.getElementById(component).value = '';
  document.getElementById(component).disabled = false;
}

// Get each component of the address from the place details
// and fill the corresponding field on the form.
for (var i = 0; i < place.address_components.length; i++) {
  var addressType = place.address_components[i].types[0];
  if (componentForm[addressType]) {
	var val = place.address_components[i][componentForm[addressType]];
	document.getElementById(addressType).value = val;
  }
}
}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(position) {
	var geolocation = {
	  lat: position.coords.latitude,
	  lng: position.coords.longitude
	};
	var circle = new google.maps.Circle({
	  center: geolocation,
	  radius: position.coords.accuracy
	});
	autocomplete.setBounds(circle.getBounds());
  });
}
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
