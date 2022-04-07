<?php 
include('inc/header.php'); 

$req_id = base64_decode($_REQUEST['id']);

$get_edit_recd = select_query("SELECT * FROM $db_name.all_job_details WHERE id='".$req_id."' ");

//echo "<pre>";print_r($get_edit_recd);die;
?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view-request-job.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Images List</a> </div>
    
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Images List</h5>
			<!--<a href="add-employee.php" style="float:right; margin:3px;" class="btn btn-info">Add Employee</a>-->
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg">
              <thead>
                <tr>
                    <th nowrap>S No.</th>
                    <th nowrap>Ticket ID</th>
                    <th nowrap>Pre Image1</th>
                    <th nowrap>Pre Image2</th>
                    <th nowrap>Pre Image3</th>
                    <th nowrap>Pre Image4</th>
                    <th nowrap>Post Image1</th>
                    <th nowrap>Post Image2</th>
                    <th nowrap>Post Image3</th>
                    <th nowrap>Post Image4</th>
                    <th nowrap>Signature Image</th>
                    <!--<th nowrap>Action</th>-->
                </tr>
              </thead>
              <tbody>
			  	<?php 
					
					for($emp=0;$emp<count($get_edit_recd);$emp++) { 
										
				?>
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_edit_recd[$emp]['ticket_no']; ?></td>
                  
                  <td><? if ($get_edit_recd[$emp]['pre_image1'] != ""){?><img src="<?=$image_path?>/uploads/services/pre_images/<?=$get_edit_recd[$emp]['pre_image1']?>" width="100" height="100"><? } ?></td>
                  
                  <td><? if ($get_edit_recd[$emp]['pre_image2'] != ""){?><img src="<?=$image_path?>/uploads/services/pre_images/<?=$get_edit_recd[$emp]['pre_image2']?>" width="100" height="100"><? } ?></td>
                  
                  <td><? if ($get_edit_recd[$emp]['pre_image3'] != ""){?><img src="<?=$image_path?>/uploads/services/pre_images/<?=$get_edit_recd[$emp]['pre_image3']?>" width="100" height="100"><? } ?></td>
                  
                  <td><? if ($get_edit_recd[$emp]['pre_image4'] != ""){?><img src="<?=$image_path?>/uploads/services/pre_images/<?=$get_edit_recd[$emp]['pre_image4']?>" width="100" height="100"><? } ?></td>
                  
                  <td><? if ($get_edit_recd[$emp]['post_image1'] != ""){?><img src="<?=$image_path?>/uploads/services/post_images/<?=$get_edit_recd[$emp]['post_image1']?>" width="100" height="100"><? } ?></td>
                  
                  <td><? if ($get_edit_recd[$emp]['post_image2'] != ""){?><img src="<?=$image_path?>/uploads/services/post_images/<?=$get_edit_recd[$emp]['post_image2']?>" width="100" height="100"><? } ?></td>
                  
                  <td><? if ($get_edit_recd[$emp]['post_image3'] != ""){?><img src="<?=$image_path?>/uploads/services/post_images/<?=$get_edit_recd[$emp]['post_image3']?>" width="100" height="100"><? } ?></td>
                  
                  <td><? if ($get_edit_recd[$emp]['post_image4'] != ""){?><img src="<?=$image_path?>/uploads/services/post_images/<?=$get_edit_recd[$emp]['post_image4']?>" width="100" height="100"><? } ?></td>
                  
                  <td><? if ($get_edit_recd[$emp]['signature_image'] != ""){?><img src="<?=$image_path?>/uploads/services/signature_image/<?=$get_edit_recd[$emp]['signature_image']?>" width="100" height="100"><? } ?></td>
                  
                  <!--<td><? if ($get_edit_recd[$emp]['image'] != ""){?>
                    <a class="btn btn-info" onclick="confirmPostPond('<?php echo base64_encode($get_job_data[$emp]["id"])?>')">Send Email</a>                  	
                    <?php } ?>   
                  </td>-->
                  
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
