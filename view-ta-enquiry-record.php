<?php 
session_start();
include('inc/header.php'); 

$id_roles=$_SESSION['id_roles'];

if($id_roles==1)
{
	$get_ExtraExpenseData = select_query("SELECT * FROM $employee_track.extra_expense_claim_tbl WHERE  login_id='".$_SESSION['user_id']."' and is_active='1' order by id desc ");
	
} else {
	
	$get_ExtraExpenseData = select_query("SELECT * FROM $employee_track.extra_expense_claim_tbl WHERE  login_id='".$_SESSION['user_id']."' and emp_id!=2 and is_active='1' order by id desc ");
	
}
 


//echo "<pre>";print_r($get_ExtraExpenseData);die;
?>


<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
    	<a href="view-request-job.php" title="Go to Home" class="tip-bottom"> <i class="icon-home"></i> Home</a> 
        <a href="#" class="current">Extra Expense List</a>     	
    </div>
    
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        
		 <?php if(isset($_SESSION['success_msg'])) {  ?>
			<div class="alert alert-success success_display">
			<button class="close" data-dismiss="alert">x</button>
			<strong class="error_submission">Success!</strong><span> Succesfully Closed.</span>
		  </div>
		  <?php } 
		  unset($_SESSION['success_msg']);
		  ?>
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Extra Expense List</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg">
              <thead>
                <tr>
                    <th nowrap>S No.</th>
                    <th nowrap>Job ID</th>
                    <th nowrap>Phone Number</th>
                    <th nowrap>Employee Name</th>
                    <th nowrap>Start Location</th>
                    <th nowrap>End Location</th>
                    <th nowrap>Fare </th>
                    <th nowrap>Request Date</th>
                    <th nowrap>Actions</th>
                </tr>
              </thead>
              <tbody>
			  	<?php 
				
				for($emp=0;$emp<count($get_ExtraExpenseData);$emp++) { 
				
				$get_EmpName = select_query("SELECT id,emp_name,mobile_no FROM $employee_track.login_emp_details WHERE  id='".$get_ExtraExpenseData[$emp]['emp_id']."' and 
								mobile_no='".$get_ExtraExpenseData[$emp]['phone_no']."' and is_active='1' order by id desc ");	
				
				?>
                
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_ExtraExpenseData[$emp]['id']; ?></td>                                    
                  <td><?php echo $get_ExtraExpenseData[$emp]['phone_no']; ?></td>
                  <td><?php echo $get_EmpName[0]['emp_name']; ?></td>
                  <td><?php echo $get_ExtraExpenseData[$emp]['start_location']; ?></td>
                  <td><?php echo $get_ExtraExpenseData[$emp]['end_location']; ?></td>                                    
                  <td><?php echo $get_ExtraExpenseData[$emp]['bill_amount']; ?></td>
                  <td><?php echo $get_ExtraExpenseData[$emp]['req_date']; ?></td>
				  <td> <a class="btn-harish btn-info-harish" onclick="closeRecd('<?php echo base64_encode($get_ExtraExpenseData[$emp]["id"])?>')">Close</a></td>
                </tr>
                <?php } ?>         
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12"> <?php echo date('Y');?> &copy; Gtrac. All Rights Reserved. </div>
</div>
<!--end-Footer-part-->
<script src="js/jquery.min.js"></script> 
<script src="js/jquery.ui.custom.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/jquery.uniform.js"></script> 
<script src="js/select2.min.js"></script> 
<script src="js/jquery.dataTables.min.js"></script> 
<script src="js/matrix.js"></script> 
<script src="js/matrix.tables.js"></script>
<script type="text/javascript">
              
function closeRecd(id){

	var txt;
	if (confirm("Are You Sure  Do you want to Close This Extra Expense Record!")) {
		txt = "You pressed OK!";
		
		window.location.href='close-request-job.php?id='+id+'&action=closeEnquiry';
	} else {
		txt = "You pressed Cancel!";
	}
}
</script>
</body>
</html>
