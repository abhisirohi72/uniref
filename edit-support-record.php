<?php 
include('inc/header.php');

$req_id = base64_decode($_REQUEST['id']);

$get_emp_recd = select_query("SELECT * FROM $employee_track.login_details WHERE id='".$req_id."' ");
//echo "<pre>";print_r($get_emp_recd);die;


if (isset($_POST['save_people'])) {
	
	//echo "<pre>";print_r($_POST);die;
	
	$req_id = $_POST['req_id'];
	$support_number = $_POST['support_number'];
	$support_email = $_POST['support_email'];
	$ta_enquiry_number = $_POST['ta_enquiry_number'];
	$ta_enquiry_email = $_POST['ta_enquiry_email'];
	
	
	$support_data = array('support_number' => $support_number, 'support_email' => $support_email, 'ta_enquiry_number' => $ta_enquiry_number,
	'ta_enquiry_email' => $ta_enquiry_email,);
	$condition2 = array('id' => $req_id, 'active_status' => 1);  
	$sequenceData = update_query($employee_track.'.login_details', $support_data, $condition2);
	
	if ($sequenceData) {
		echo "<script>window.location.href='view-support-record.php'</script>";
		$_SESSION['success_msg'] = 'set';
	}
	else {
		$_SESSION['unsuccess_msg'] = 'set';
	}
	
}


?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view-request-job.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Edit Support List</a> </div>
    
  </div>
  <div class="container-fluid">
   
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Edit Support List</h5>
          </div>
          <div class="widget-content nopadding">
            <form action="#" method="post" class="form-horizontal" autocomplete="off">
              <div class="alert alert-error error_display" style="display:none">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span id="print_err"></span>
			  </div>
			  
			  <?php if(isset($_SESSION['success_msg'])) {  ?>
				<div class="alert alert-success success_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Success!</strong><span> Succesfully Updated. Click <a href="view-support-record.php">here</a> to View</span>
			  </div>
              <?php } else if(isset($_SESSION['unsuccess_msg'])) {  ?>
				<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> Details are Missing. </span>
			  </div>
			  <?php } 
			  unset($_SESSION['success_msg']);
			  unset($_SESSION['unsuccess_msg']);
			  ?>
			  	<input type="hidden" name="req_id" id="req_id" value="<?php echo $get_emp_recd[0]['id'];?>"/>
				
				
			  <div class="control-group">
                <label class="control-label">Support Phone Number:</label>
                <div class="controls">
                  <input type="text" name="support_number" maxlength="10" id="support_number" class="mandatory" value="<?php echo $get_emp_recd[0]['support_number'];?>" placeholder="Number *" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Support Email:</label>
                <div class="controls">
                  <input type="text" name="support_email" id="support_email" class="mandatory" value="<?php echo $get_emp_recd[0]['support_email'];?>" placeholder="Email *" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">TA Enquiry Phone Number:</label>
                <div class="controls">
                  <input type="text" name="ta_enquiry_number" maxlength="10" id="ta_enquiry_number" class="mandatory" value="<?php echo $get_emp_recd[0]['ta_enquiry_number'];?>" placeholder="Number " />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">TA Enquiry Email:</label>
                <div class="controls">
                  <input type="text" name="ta_enquiry_email" id="ta_enquiry_email" class="mandatory" value="<?php echo $get_emp_recd[0]['ta_enquiry_email'];?>" placeholder="Email *" />
                  <span id="branch_error"></span> </div>
              </div>
              
			  
              <div class="form-actions">
                <button type="submit" class="btn-harish btn-info-harish save_step_1" name="save_people">Save</button>
                <a  class="btn-harish btn-info-harish" href="view-support-record.php" style="color: #fff;">Cancel</a>

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
	
		var email = $("#support_email").val();
		var number = $("#support_number").val();
		
		var ta_email = $("#ta_enquiry_email").val();
		
		if( number == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Support number.");
			return false;
		}
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}
		
		if( email == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Support Email Id.");
			return false;
		}
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}	
		
		if( ta_email == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter TA Enquiry Email Id.");
			return false;
		}
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}			   
	   
    });
	
	
	 $("#support_number").keydown(function (e) {
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
	
	$("#ta_enquiry_number").keydown(function (e) {
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

<?php include('inc/footer.php');?>
