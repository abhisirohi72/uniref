<?php 
include('inc/header.php'); 
 
$get_support_data = select_query("SELECT * FROM $employee_track.login_details  WHERE login_id='".$_SESSION['user_id']."' and id_roles='1' and active_status='1' ");

//echo "<pre>";print_r($get_support_data);die;
?>


<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
    	<a href="view-request-job.php" title="Go to Home" class="tip-bottom"> <i class="icon-home"></i> Home</a> 
        <a href="#" class="current">Support List</a>     	
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
            <h5>Support List</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-sm">
              <thead>
                <tr>
                    <th nowrap>S No.</th>
                    <th nowrap>Support Number</th>
                    <th nowrap>Support Email</th>
                    <th nowrap>TA Enquiry Number</th>
                    <th nowrap>TA Enquiry Email</th>
                    <th nowrap>Actions</th>
                </tr>
              </thead>
              <tbody>
			  	<?php for($emp=0;$emp<count($get_support_data);$emp++) { ?>
                
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_support_data[$emp]['support_number']; ?></td>                                    
                  <td><?php echo $get_support_data[$emp]['support_email']; ?></td>
                  <td><?php echo $get_support_data[$emp]['ta_enquiry_number']; ?></td>
                  <td><?php echo $get_support_data[$emp]['ta_enquiry_email']; ?></td>
				  <td> <a class="btn-harish btn-info-harish" href="edit-support-record.php?id=<?php echo base64_encode($get_support_data[$emp]['id']);?>">Edit</a></td>
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

</body>
</html>
