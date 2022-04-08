<?php 
session_start();
include('inc/header.php'); 

$get_details = select_query("SELECT * FROM login_details WHERE id_roles='2' AND is_deleted='0' order by id desc ");
?>
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view_sub_users.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">View Sub Users</a> </div>
    
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
            <h5>All Sub Users</h5>
             <form name="report" action="customer_data_excel.php" id="customer_job_report" method="post" target="_blank">
                <div class="col-md-7" > </div>
                <div class="col-md-1" > 
                    <input type="hidden" name="login_id" id="login_id" value="<?=$_SESSION['user_id'];?>" />
                    <button type="submit" name="submit" class="btn-harish btn-info-harish" style="float:right" value="CustomerDataExcel"><i class="fa fa-download"></i> Export Excel </button>
                </div>
              </form>
			<a href="add_sub_users.php" style="float:right; margin:3px;" class="btn-harish btn-info-harish">Add Sub Users</a>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg" id="filtertable">
              <thead>
                <tr>
                  <th>SNo</th>
                  <th>Username/ID</th>
                  <th>Password</th>
                  <th>Support Number</th>
                  <th>Support Emails</th>
				  <th>Status</th>
                  <th>Created Date</th>
				  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
			  	<?php foreach($get_details as $key=>$get_detail){?>
                <tr class="gradeX">
                  <td><?php echo $key+1; ?></td>
                  <td><?php echo $get_detail['username']; ?></td>
                  <td><?php echo $get_detail['password']; ?></td>
                  <td><?php echo $get_detail['support_number']; ?></td>
                  <td><?php echo $get_detail['support_email']; ?></td>
                  <td><?php echo ($get_detail['active_status']==1)?"Active":"In Active"; ?></td>
                  <td><?php echo $get_detail['created']; ?></td>                  
				  <td> 
                    <a class="btn-harish btn-info-harish" href="add_sub_users.php?action=edit&id=<?php echo base64_encode($get_detail['id']);?>">Edit</a>
                    
					<a class="btn-harish btn-info-harish" onclick="deleteSubUser('<?php echo base64_encode($get_detail["id"])?>')">Delete</a>
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

function deleteSubUser(id){

	var txt;
	if (confirm("Are You Sure Do you want to Delete Sub User!")) {
		txt = "You pressed OK!";
		
		window.location.href='close-request-job.php?id='+id+'&action=deleteSubUser';
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
