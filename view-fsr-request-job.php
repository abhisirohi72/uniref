<?php 
include('inc/header.php'); 

$currentdate = date('Y-m-d');
  
$get_job_data = select_query("SELECT * FROM $db_name.all_job_details WHERE to_technician is null  and job_status!=5 and is_active='1' and request_date<='".$currentdate."'  order by id desc ");

//echo "<pre>";print_r($get_job_data);die;
?>

<!--<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/custom.css" />-->

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 id="mySmallModalLabel" class="modal-title"> Job Details
          <??>
        </h4>
      </div>
      <div class="modal-body" id="innercontent"> </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>


<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
    	<a href="view-request-job.php" title="Go to Home" class="tip-bottom"> <i class="icon-home"></i> Home</a> 
        <a href="#" class="current">Customer Service/Complaint</a>     	
    </div>
    
    <!--<div class="container-fluid">
    	<div class="row-fluid">
          
         </div>    
      </div>-->
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
            <h5>Customer Service/Complaint</h5>
              
			<a href="download_excel.php?action=view-fsr-request-job" style="float:right; margin:3px;" class="btn-harish btn-info-harish">Export Excel</a>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg" id="filtertable">
              <thead>
                <tr>
                    <th nowrap>S No.</th>
                    <th nowrap>Ticket ID</th>
                    <!--<th nowrap>Call Type</th>-->
                    <th nowrap>Service Type</th>
                    <!--<th nowrap>Priority</th>-->
                    <th nowrap>Location</th>
                    <th nowrap>Status</th>
                    <th nowrap>Created On</th>
                    <!--<th nowrap>Product Group</th>-->
                    <th nowrap>Technician Name</th>
                    <th nowrap>Customer Name</th>
                    <!--<th nowrap>Pin Code</th>
                    <th nowrap>Product</th>
                    <th nowrap>Phone No</th>-->
                    <th nowrap>View/Action</th>
                    <!--<th nowrap>Close Time</th>
                    <th nowrap>Back Reason</th>-->
                </tr>
              </thead>
              <tbody>
			  	<?php for($emp=0;$emp<count($get_job_data);$emp++) { 
					
					$technician_name = select_query("select emp_name from $db_name.technicians_login_details where id='".$get_job_data[$emp]['to_technician']."'");
				?>
                
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_job_data[$emp]['ticket_no']; ?></td>  
                                  
                  <!--<td><?php echo $get_job_data[$emp]['call_type']; ?></td>-->
                  <td><?php echo $get_job_data[$emp]['service_type']; ?></td> 
                  <!--<td><?php echo $get_job_data[$emp]['priority_type']; ?></td> -->
                  
                  <td title="<?php echo $get_job_data[$emp]['job_location']; ?>"><?=substr($get_job_data[$emp]['job_location'], 0, 15);?> ..</td>
                                    
                  <td><?php if($get_job_data[$emp]['job_status']==0 && $get_job_data[$emp]['to_technician']!=''){echo "Assign to Installer";} else if($get_job_data[$emp]['job_status']==0 && $get_job_data[$emp]['to_technician']==''){echo "Job Not Assign";} else if($get_job_data[$emp]['job_status']==1){echo "Accept/On the Way";} else if($get_job_data[$emp]['job_status']==2){echo "On the Way";} else if($get_job_data[$emp]['job_status']==3){echo "Working";} else if($get_job_data[$emp]['job_status']==4){echo "Reject";} else if($get_job_data[$emp]['job_status']==5){echo "Complete";}?></td> 
                  <td><?php echo $get_job_data[$emp]['request_date']; ?></td> 
                  
                  
                  <!--<td><?php echo $get_job_data[$emp]['product_group']; ?></td>-->
                  <td><?php echo $technician_name[0]['emp_name']; ?></td>
                  <td><?php echo $get_job_data[$emp]['customer_name']; ?></td>
                                    
                  <!--<td><?php echo $get_job_data[$emp]['pin_code']; ?></td>
                  <td><?php echo $get_job_data[$emp]['product_group']; ?></td>
                  <td><?php echo $get_job_data[$emp]['customer_phone_no']; ?></td>-->
                  
                  <td><a onclick="Show_info('GetJobRequestDetails','<?=$get_job_data[$emp]['id'];?>');" class="btn-harish btn-info-harish" data-toggle="modal" data-target=".bs-example-modal-sm">View </a>
                  <? if ($get_job_data[$emp]['job_status'] == 0){ ?>
                  <br /><br /> <a class="btn-harish btn-info-harish" href="edit-service-job.php?id=<?php echo base64_encode($get_job_data[$emp]['id']);?>">Assign</a>
                  <? } ?>
                  
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
  <div id="footer" class="span12"> <?php echo date('Y');?> &copy; Gtrac. All Rights Reserved. </div>
</div>
<!--end-Footer-part-->
<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/jquery.ui.custom.js"></script> 
<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/jquery.uniform.js"></script> 
<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/select2.min.js"></script> 
<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/jquery.dataTables.min.js"></script> 
<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/matrix.js"></script> 
<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/matrix.tables.js"></script>
<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/libs/bootstrap/bootstrap-datetimepicker.js" charset="UTF-8"></script>

<script type="text/javascript">
              
function closeRecd(id){

	var txt;
	if (confirm("Are You Sure Do you want to Close This Ticket!")) {
		txt = "You pressed OK!";
		
		window.location.href='close-request-job.php?id='+id+'&action=closeTicket';
	} else {
		txt = "You pressed Cancel!";
	}
}

function confirmPostPond(id){

	var txt;
	if (confirm("Are You Sure Do you want to PostPoned This Ticket!")) {
		txt = "You pressed OK!";
		
		window.location.href='close-request-job.php?id='+id+'&action=PostPond';
	} else {
		txt = "You pressed Cancel!";
	}
}

function confirmCancel(id){

	var txt;
	if (confirm("Are You Sure Do you want to Cancel PostPoned Ticket!")) {
		txt = "You pressed OK!";
		
		window.location.href='close-request-job.php?id='+id+'&action=Cancel';
	} else {
		txt = "You pressed Cancel!";
	}
}

function hideDate(val)
{
	//alert(val);	
	
	if(val=="Today")
    {
        document.getElementById('dateStart').value='';
		document.getElementById('dateEnd').value='';
		document.getElementById('dateStart').disabled=true;
		document.getElementById('dateEnd').disabled=true;
    }
    else if(val=="Tomorrow")
    {
         document.getElementById('dateStart').value='';
		 document.getElementById('dateEnd').value='';
		 document.getElementById('dateStart').disabled=true;
		 document.getElementById('dateEnd').disabled=true;
    }
	else if(val=="NextDay")
    {
         document.getElementById('dateStart').value='';
		 document.getElementById('dateEnd').value='';
		 document.getElementById('dateStart').disabled=true;
		 document.getElementById('dateEnd').disabled=true;
    }
    else
    {
         document.getElementById('dateStart').disabled=false;
		 document.getElementById('dateEnd').disabled=false;
    }
	
}
</script>
<link href="<?php echo __SITE_URL;?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $('.form_date').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    $('.form_time').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });

</script>
<script>hideDate("<?=$_POST['Showday'];?>");</script> 
<script type="text/javascript">

function Show_info(action,rid,starttime,endtime)
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
