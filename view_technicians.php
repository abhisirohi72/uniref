<?php 
session_start();
include('inc/header.php'); 

$id_roles = $_SESSION['id_roles'];

$get_people = select_query("SELECT * FROM $db_name.technicians_login_details WHERE is_active='1' order by id desc ");
	
//echo "<pre>";print_r($get_people);die;
?>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 id="mySmallModalLabel" class="modal-title"> Technicians Details
          
        </h4>
      </div>
      <div class="modal-body" id="innercontent"> </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view_technicians.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">All Technicians</a> </div>
    
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
            <h5>All Technicians</h5>
             <form name="report" action="technician_data_excel.php" id="customer_job_report" method="post" target="_blank">
                <div class="col-md-7" > </div>
                <div class="col-md-1" > 
                    <input type="hidden" name="login_id" id="login_id" value="<?=$_SESSION['user_id'];?>" />
                    <button type="submit" name="submit" class="btn-harish btn-info-harish" style="float:right" value="CustomerDataExcel"><i class="fa fa-download"></i> Export Excel </button>
                </div>
              </form>
			<a href="add_technicianse.php" style="float:right; margin:3px;" class="btn-harish btn-info-harish">Add Technicians</a>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg" id="filtertable">
              <thead>
                <tr>
                  <th>SNo</th>
                  <th>Name/ID</th>
				  
                  <th>Mobile No</th>
                  <th>Home Address</th>
                  <th>Aadhar No</th>
                  <th>Gender</th>
                  <th>DOB</th>
                  <th>Battery</th>
				  <th>Active Status</th>
                  <th>Day Status</th>
				  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
			  	<?php for($emp=0;$emp<count($get_people);$emp++) { ?>
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_people[$emp]['emp_name'].'/'.$get_people[$emp]['technician_id']; ?></td>
				  
                  <td><?php echo $get_people[$emp]['mobile_no']; ?></td>
                  <td title="<?php if($get_people[$emp]['home_address'] != ''){echo $get_people[$emp]['home_address'].', PIN-'.$get_people[$emp]['home_pin_code'];} ?>"><?=substr($get_people[$emp]['home_address'], 0, 20);?> ..</td>
                  
                  <td><?php echo $get_people[$emp]['aadhar_no']; ?></td>
                  <td><?php echo $get_people[$emp]['gender']; ?></td>
                  <td><?php if($get_people[$emp]['dob'] != '0000-00-00' && $get_people[$emp]['dob'] != ''){echo date("d/m/Y",strtotime($get_people[$emp]['dob'])); }?></td>    
                  <td><?php echo $get_people[$emp]['battery_level']; ?></td>              
                  <td><?php if( $get_people[$emp]['is_active'] == '1' ) { echo "Active";} else {echo "Deactive";} ?></td>
                  <td><?php if( $get_people[$emp]['day_start_end'] == '1' ) { echo "Job Start";} else if( $get_people[$emp]['day_start_end'] == '2' ) { echo "Job End";} else {echo "Job Not Start";} ?></td>  
                                  
                  
				  <td> <?php if($get_people[$emp]['is_active']==1){?>
                 <!--<a class="btn btn-danger" href="delete.php?id=<?php //echo base64_encode($fetch_people['id']);?>&action=people">Delete</a> -->
					<?php if($_SESSION['id_roles']=="1"){?>
            		<a class="btn-harish btn-info-harish" href="edit_technicianse.php?id=<?php echo base64_encode($get_people[$emp]['id']);?>">Edit</a>
                    <?php }?>
					<a onclick="Show_info('GetTechnicianDetails','<?=$get_people[$emp]['id'];?>');" class="btn-harish btn-info-harish" data-toggle="modal" data-target=".bs-example-modal-sm">View </a>
                    <!--</br></br>-->
					<?php if($_SESSION['id_roles']=="1"){?>
						<a class="btn-harish btn-info-harish" onclick="deleteTech('<?php echo base64_encode($get_people[$emp]["id"])?>')">Delete</a>
					<?php }?>	
                  	<?php } ?>
                  </td>
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

function deleteTech(id){

	var txt;
	if (confirm("Are You Sure Do you want to Delete Technicians!")) {
		txt = "You pressed OK!";
		
		window.location.href='close-request-job.php?id='+id+'&action=deleteTech';
	} else {
		txt = "You pressed Cancel!";
	}
}

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
