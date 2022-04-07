<?php 
include('inc/header.php'); 

  
$get_job_data = select_query("SELECT req.id,req.job_id,req.phone_no,req.emp_id,req.job_location,req.client_name,req.contactname,req.contactno, req.fromtime, req.totime, req.jobassigndate, req.current_record, req.job_type, req.sequence_no, req.sequence_date, led.emp_name
FROM $employee_track.request as req left join $employee_track.login_emp_details as led on req.emp_id=led.id WHERE req.login_id='".$_SESSION['user_id']."' and ((req.current_record=0 and req.job_type=2) or (req.current_record=2)) and req.is_active=1 and req.sequence_date='".date("Y-m-d")."'  order by req.sequence_no ");

/*$get_job_data = select_query("SELECT * FROM $employee_track.request WHERE login_id='".$_SESSION['user_id']."' and current_record!=1 order by created_date desc ");*/
//echo "<pre>";print_r($get_job_data);die;
?>


<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
    	<a href="view-request-job.php" title="Go to Home" class="tip-bottom"> <i class="icon-home"></i> Home</a> 
        <a href="#" class="current">Today Job Sequence List</a>     	
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
            <h5>Today Job Sequence List</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                    <th nowrap>S No.</th>
                    <th nowrap>Job ID</th>
                    <th nowrap>Phone Number</th>
                    <th nowrap>Employee Name</th>
                    <th nowrap>Location</th>
                    <th nowrap>Current Status</th>
                    <th nowrap>Client Name</th>
                    <!--<th nowrap>Client Mobile No.</th>-->
                    <th nowrap>Contact Person </th>
                    <th nowrap>Contact Person No.</th>
                    <th nowrap>From time</th>
                    <th nowrap>To time</th>
                    <th nowrap>Assigned On</th>
                    <th nowrap>Job Number</th>
                    <th nowrap>Actions</th>
                </tr>
              </thead>
              <tbody>
			  	<?php for($emp=0;$emp<count($get_job_data);$emp++) { ?>
                
                <tr class="gradeX" <? if($get_job_data[$emp]['current_record']==2){ echo 'style="background-color:#FFC184"';} elseif($get_job_data[$emp]['current_record']==1){ echo 'style="background-color:#ADFF2F"';}?>>
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_job_data[$emp]['job_id']; ?></td>                                    
                  <td><?php echo $get_job_data[$emp]['phone_no']; ?></td>
                  <td><?php echo $get_job_data[$emp]['emp_name']; ?></td>
                  <td><?php echo $get_job_data[$emp]['job_location']; ?></td>
                  <td>
						<?php
                        if($get_job_data[$emp]['job_type']==1 && $get_job_data[$emp]['current_record']==0)
                        {
                            echo "<span class='label label-outline-success'>Ongoing</span>";
                        }
                        elseif($get_job_data[$emp]['job_type']==2 && $get_job_data[$emp]['current_record']==0)
                        {
                            echo "<span class='label label-outline-success'>Pending</span>";
                        }
                        elseif($get_job_data[$emp]['job_type']==2 && $get_job_data[$emp]['current_record']==1){
                            
                            echo "<span class='label label-outline-success'>Completed</span>";
                        }
                        elseif($get_job_data[$emp]['job_type']==1 && $get_job_data[$emp]['current_record']==1)
                        {
                            echo "<span class='label label-outline-success'>Completed</span>";
                        }
						elseif($get_job_data[$emp]['job_type']==1 && $get_job_data[$emp]['current_record']==2)
                        {
                            echo "<span class='label label-outline-success'>Back</span>";
                        }
						elseif($get_job_data[$emp]['job_type']==2 && $get_job_data[$emp]['current_record']==2)
                        {
                            echo "<span class='label label-outline-success'>Back</span>";
                        }
                        else
                        {
                            echo "<span class='label label-outline-success'>Don't Know</span>";
                        }
            
						?>
                  </td>
                  
                  <td><?php echo $get_job_data[$emp]['client_name']; ?></td>
                  <td><?php echo $get_job_data[$emp]['contactname']; ?></td>
                  <td><?php echo $get_job_data[$emp]['contactno']; ?></td>
                                    
                  <td><?php if($get_job_data[$emp]['fromtime'] != '0000-00-00 00:00:00' && $get_job_data[$emp]['fromtime'] != ''){echo date("d/m/Y h:i A",strtotime($get_job_data[$emp]['fromtime']));} ?></td>
                   <td><?php if($get_job_data[$emp]['totime'] != '0000-00-00 00:00:00' && $get_job_data[$emp]['totime'] != ''){echo date("d/m/Y h:i A",strtotime($get_job_data[$emp]['totime']));} ?></td>
                   <td><?php if($get_job_data[$emp]['jobassigndate'] != '0000-00-00 00:00:00' && $get_job_data[$emp]['jobassigndate'] != ''){echo date("d/m/Y h:i A",strtotime($get_job_data[$emp]['jobassigndate']));} ?></td>
                   
                  <td><?php echo $get_job_data[$emp]['sequence_no']; ?></td>
				  <td><?php if($get_job_data[$emp]['current_record']!=1){?>
                  <a class="btn-harish btn-info-harish" href="edit-request-sequence-change.php?id=<?php echo base64_encode($get_job_data[$emp]['id']);?>&emp_id=<?php echo base64_encode($get_job_data[$emp]['emp_id']);?>">Edit</a>
                  
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
