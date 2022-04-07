<?php 
include('inc/header.php'); 

$req_id = base64_decode($_REQUEST['id']);

$get_edit_recd = select_query("SELECT * FROM $employee_track.employee_trip_details WHERE id='".$req_id."' ");

//echo "<pre>";print_r($get_edit_recd);die;
?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view-request-job.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Billing Images List</a> </div>
    
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Billing Images List</h5>
			<!--<a href="add-employee.php" style="float:right; margin:3px;" class="btn btn-info">Add Employee</a>-->
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg">
              <thead>
                <tr>
                    <th nowrap>S No.</th>
                    <th nowrap>Job ID</th>
                    <th nowrap>Image</th>
                    <!--<th nowrap>Action</th>-->
                </tr>
              </thead>
              <tbody>
			  	<?php 
					
					for($emp=0;$emp<count($get_edit_recd);$emp++) { 
										
				?>
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_edit_recd[$emp]['id']; ?></td>
                  
                  <td><? if ($get_edit_recd[$emp]['image'] != ""){?><img src="<?=$image_path?>/uploads/services/billing_image/<?=$get_edit_recd[$emp]['image']?>" width="200" height="100"><? } ?></td>
                                    
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

<script>
/*$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});*/
</script>

</body>
</html>
