<?php 
session_start();
include('inc/header.php'); 


$get_people = select_query("SELECT * FROM $db_name.technicians_login_details WHERE is_active='1' order by id desc ");
	
//echo "<pre>";print_r($get_people);die;
?>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 id="mySmallModalLabel" class="modal-title"> Technicians Job Details
          
        </h4>
      </div>
      <div class="modal-body" id="innercontent"> </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>


<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view_technicians.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">All Technicians Service History</a> </div>
    
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
            <h5>All Technicians Service History</h5>
			<a href="download_excel.php?action=technicians_job_history" style="float:right; margin:3px;" class="btn-harish btn-info-harish">Export Excel</a>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg" id="filtertable">
              <thead>
                <tr>
                  <th>SNo</th>
                  <th>Name</th>
                  <th>Mobile No</th>
                  <th>Technicians Id</th>
                  <th>Date of Joining</th>
                  <th>Service Done</th>
                  <th>Date of Leaving</th>
				  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
			  	<?php 
				
				for($emp=0;$emp<count($get_people);$emp++) { 
				
				$service_done = select_query("SELECT count(id) as no_of_job FROM $db_name.all_job_details WHERE to_technician='".$get_people[$emp]['id']."' and job_status='5' order by id desc ");
								
				/*$leave_status = select_query("SELECT * FROM $db_name.technicians_login_details WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by id desc ");*/
				
				?>
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_people[$emp]['emp_name']; ?></td>
                  <td><?php echo $get_people[$emp]['mobile_no']; ?></td>
                  <td><?php echo $get_people[$emp]['technician_id']; ?></td>
                  
                  <td><?php if($get_people[$emp]['date_of_joining'] != '0000-00-00' && $get_people[$emp]['date_of_joining'] != ''){echo date("d/m/Y",strtotime($get_people[$emp]['date_of_joining'])); }?></td> 
                     
                  <td><?php echo $service_done[0]['no_of_job']; ?></td>              
                  <td><?php if($get_people[$emp]['job_status'] == 0){echo "Assign to Installer";} else if( $get_people[$emp]['job_status'] == 1 ) { echo "Accept/On the Way";} else if( $get_people[$emp]['job_status'] == 2 ) { echo "On the Way";} else if( $get_people[$emp]['job_status'] == 3 ) { echo "Currently Working";} else if( $get_people[$emp]['job_status'] == 4 ) { echo "Reject";} else if( $get_people[$emp]['job_status'] == 5 ) { echo "Completed";} else {echo "No Action";} ?></td> 
                  
				  <td> <a onclick="Show_info('GetTechnicianAllJobDetails','<?=$get_people[$emp]['id'];?>');" class="btn-harish btn-info-harish" data-toggle="modal" data-target=".bs-example-modal-sm">View </a> </td>
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
<?php
	$filename= basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
	require_once ('filtertable.php');
?>
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
