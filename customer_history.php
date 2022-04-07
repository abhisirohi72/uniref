<?php 
session_start();
include('inc/header.php'); 

$id_roles = $_SESSION['id_roles'];

$get_people = select_query("SELECT * FROM $db_name.customer_details WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by id desc ");
	
//echo "<pre>";print_r($get_people);die;
?>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 id="mySmallModalLabel" class="modal-title"> Customers Request Details
          
        </h4>
      </div>
      <div class="modal-body" id="innercontent"> </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>


<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view_technicians.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">All Customers Report History</a> </div>
    
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        
		 <?php if(isset($_SESSION['success_msg'])) {  ?>
			<div class="alert alert-success success_display">
			<button class="close" data-dismiss="alert">x</button>
			<strong class="error_submission">Success!</strong><span> Succesfully deleted.</span>
		  </div>
		  <?php } 
		  unset($_SESSION['success_msg']);
		  ?>
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>All Customers Report History</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg">
              <thead>
                <tr>
                  <th>SNo</th>
                  <th>Name/ID</th>
                  <th>Mobile No</th>
                  <th>Organisation Name</th>
                  <th>Model Purchased</th>
                  <th>Serial No</th>
                  <th>Date of Installation</th>
				  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
			  	<?php 
				
				for($emp=0;$emp<count($get_people);$emp++) { 
					
					if($get_people[$emp]['date_of_installation'] != '0000-00-00' && $get_people[$emp]['date_of_installation'] != '')
					{
						$installationDate = date("d F Y",strtotime($get_people[$emp]['date_of_installation'])); 
					
					} else {
						$installationDate = '';
					}
				?>
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_people[$emp]['name'].'/'.$get_people[$emp]['cust_id']; ?></td>
                  <td><?php echo $get_people[$emp]['phone_no']; ?></td>
                  <td><?php echo $get_people[$emp]['company_name']; ?></td>
                  <td><?php echo $get_people[$emp]['model_purchased']; ?></td>
                  <td><?php echo $get_people[$emp]['serial_no']; ?></td> 
                  <td><?php echo $installationDate;?></td> 
				  <td> <a onclick="Show_info('GetCustomerDetails','<?=$get_people[$emp]['id'];?>');" class="btn-harish btn-info-harish" data-toggle="modal" data-target=".bs-example-modal-sm">View </a> </td>
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
  <div id="footer" class="span12"> 2019 &copy; Gtrac. All Rights Reserved. </div>
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

function Show_info(action,rid)
{
    $.ajax({
            type:"GET",
            url:"show_view_info.php?action="+action,

            data:"rid="+rid,
            success:function(msg){

            document.getElementById("innercontent").innerHTML = msg;

            }
    });
}

</script>

</body>
</html>
