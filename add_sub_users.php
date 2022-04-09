<?php 
include('inc/header.php');
$getDetails= array();
$edit_id="";
if(isset($_REQUEST['action']) && $_REQUEST['action']=="edit"){
	$edit_id = base64_decode($_REQUEST['id']);
	$getDetails= select_query("SELECT * FROM `login_details` WHERE `id`=".$edit_id);
	foreach($getDetails as $getDetails);
}
if(isset($_POST['save_people'])){
	if(isset($_POST['edit_id']) && $_POST['edit_id']!=''){
		$update = update_query("login_details", array(
													"password"			=>	$_POST['password'],
													"support_number"	=>	$_POST['support_number'],
													"support_email"		=>	$_POST['support_email'],
													"active_status"		=>	$active_status,
													"id_roles"			=>	'2'
												),
												array(
													"id"				=>	$edit_id
												)
								);
		if($update){
			$_SESSION['update_success_msg'] = 'set';
			echo "<script>window.location.href='add_sub_users.php?action=edit&id=".$_REQUEST['id']."'</script>";
			exit();
		}						
	}else{
		//check the username is exxist or not
		$check= select_query("SELECT id FROM $db_name.login_details where `username`='".$_POST['username']."'");
		if(count($check)>0){
			$_SESSION['unsuccess_msg'] = 'set';
			echo "<script>window.location.href='add_sub_users.php'</script>";
			exit();
		}
		$activeStatus= (isset($_POST['active_status']))?$_POST['active_status']:'0';
		$getLastInsertId	=	insert_query('login_details', array(
								"username"			=>	$_POST['username'],
								"password"			=>	$_POST['password'],
								"support_number"	=>	$_POST['support_number'],
								"support_email"		=>	$_POST['support_email'],
								"active_status"		=>	$active_status,
								"id_roles"			=>	'2'
								));
		if($getLastInsertId) {
			$update = update_query("login_details",array("login_id"=>$getLastInsertId), array("id"=>$getLastInsertId));
			if($update){
				$_SESSION['success_msg'] = 'set';
				echo "<script>window.location.href='add_sub_users.php'</script>";
				exit();
			}
		}
	}
}

?>
<div id="content">
	<div id="content-header">
		<div id="breadcrumb"> 
			<a href="view_customer.php" title="Go to Home" class="tip-bottom">
				<i class="icon-home"></i> 
				Home
			</a> 
			<a href="#" class="current">Add SubUsers</a>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12">
				<div class="widget-box">
					<div class="widget-title"> 
						<span class="icon"> 
							<i class="icon-align-justify"></i> 
						</span>
						<h5>Add Sub Users</h5>
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
									<strong class="error_submission">Success!</strong><span> Succesfully added. Click <a href="view_sub_users.php">here</a> to View</span>
								</div>
							<?php unset($_SESSION['success_msg']);} else if(isset($_SESSION['update_success_msg'])) {  ?>
								<div class="alert alert-success success_display">
									<button class="close" data-dismiss="alert">x</button>
									<strong class="error_submission">Success!</strong><span> Succesfully updated. Click <a href="view_sub_users.php">here</a> to View</span>
								</div>
							<?php unset($_SESSION['success_msg']);} else if(isset($_SESSION['unsuccess_msg'])) {  ?>
								<div class="alert alert-error error_display">
									<button class="close" data-dismiss="alert">x</button>
									<strong class="error_submission">Error!</strong><span> Username Already Exist. </span>
								</div>
							<?php unset($_SESSION['unsuccess_msg']); }else if(isset($_SESSION['fileError'])) {  ?>
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
								
								
								unset($_SESSION['fileError']);
								unset($_SESSION['fileSizeError']);
								unset($_SESSION['fileTypeError']);			  
							?>
							<div class="control-group">
								<label class="control-label">
									Username:<font color="red">* </font>
								</label>
								<div class="controls">
									<input type="text" name="username" id="username" class="mandatory" placeholder="Username" value="<?php echo $getDetails['username'];?>" <?php if($edit_id!=""){?>readonly<?php } ?>/>
									<span id="username_error"></span> 
								</div>
							</div>

							<div class="control-group">
								<label class="control-label">
									Password:<font color="red">* </font>
								</label>
								<div class="controls">
									<input type="<?php echo ($edit_id!="")?'text':'password';?>" name="password" id="password"  class="mandatory" placeholder="Password" value="<?php echo $getDetails['password'];?>"/>
									<span id="password_error"></span> 
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">
									Support Number:<font color="red">* </font>
								</label>
								<div class="controls">
									<input type="number" name="support_number" id="support_number"  class="mandatory" placeholder="Support Number" value="<?php echo $getDetails['support_number'];?>" maxlength="10"/>
									<span id="support_number_error"></span> 
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">
									Support Email:<font color="red">* </font>
								</label>
								<div class="controls">
									<input type="text" name="support_email" id="support_email"  class="mandatory" placeholder="Support Email" value="<?php echo $getDetails['support_email'];?>"/>
									<span id="support_email_error"></span> 
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">
									Is Active:
								</label>
								<div class="controls">
									<input type="checkbox" name="active_status" id="active_status"  class="mandatory"  value="1" <?php if($getDetails['active_status']==1){ echo "checked"; }?>/>
									<span id="active_status_error"></span> 
								</div>
							</div>
							<div class="form-actions">
							<input type="hidden" name="edit_id" value="<?php echo $getDetails['id']; ?>">
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
		// alert('Hii');
		// return false;
		$("#print_err").html("");
		$(".error_display").css("display","none");
		
		var username = $("#username").val();
		var password = $("#password").val();
		var support_number= $("#support_number").val();
		var support_email= $("#support_email").val();
						
		if( username == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter User Name.");
			return false;
		}
		
		if( password == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Password.");
			return false;
		}
						
		if( support_number == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please enter Support Number.");
			return false;
		}else{			   
			var charnumber=support_number.length;
			if(charnumber < 10 || charnumber > 12 || support_number.search(/[^0-9\-()+]/g) != -1) {
				$(".error_display").css("display","block");
				$("#print_err").html(" Please Enter Valid Number.");
				return false;
			}
		}
		
		if( support_email == 0) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Support Email.");
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

////////////////////////// Validation ////////////////////////
	
});

</script>

<?php include('inc/footer.php');?>
