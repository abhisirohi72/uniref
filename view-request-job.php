<?php 
include('inc/header.php'); 

$currentdate = date('Y-m-d');

if(isset($_POST["submit"]))
{
	//echo "<pre>";print_r($_POST);die;
	
	$startdate = $_POST['dateStart'];
    $Enddate = $_POST['dateEnd'];
	$Showday = $_POST['Showday'];
	$Showrequest = $_POST["Showrequest"];
	
	//$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status!=5 and request_date<='".$currentdate."' ";
	
	if($startdate!='' && $Enddate!='' && ($Showrequest!=0 || $_POST["Showrequest"]!='') && $Showday=='0')
	{
		if($_POST["Showrequest"]==1)
		{
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=1 and is_active='1' and request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
		}
		else if($_POST["Showrequest"]==2)
		{
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=2 and is_active='1' and request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
		}
		else if($_POST["Showrequest"]==3)
		{
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=3 and is_active='1' and request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
		}
		else if($_POST["Showrequest"]==4)
		{
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=4 and is_active='1' and request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
		}
		else if($_POST["Showrequest"]==5)
		{
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=5 and is_active='0' and request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
		}
		else if($_POST["Showrequest"]==6)
		{
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
		}
		else
		{ 
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status IN ('0','1','2','3') and is_active='1' and request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
		}
	}
	else if($startdate=='' && $Enddate=='' && ($Showrequest!=0 || $_POST["Showrequest"]!='') && $Showday=='0')
	{
		if($_POST["Showrequest"]==1)
		{
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and is_active='1' and job_status=1 and to_technician!='' ";
		}
		else if($_POST["Showrequest"]==2)
		{
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and is_active='1' and job_status=2 and to_technician!='' ";
		}
		else if($_POST["Showrequest"]==3)
		{
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and is_active='1' and job_status=3 and to_technician!='' ";
		}
		else if($_POST["Showrequest"]==4)
		{
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and is_active='1' and job_status=4 and to_technician!='' ";
		}
		else if($_POST["Showrequest"]==5)
		{
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and is_active='0' and job_status=5 and to_technician!='' ";
		}
		else if($_POST["Showrequest"]==6)
		{
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and to_technician!='' ";
		}
		else
		{ 
			$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and is_active='1' and job_status IN ('0','1','2','3') and to_technician!='' ";
		
		}
	}
	else if($startdate=='' && $Enddate=='' && ($Showrequest==0 || $_POST["Showrequest"]=='') && $Showday!='0')
	{
		 if($Showday == 'Today')
		 {
			  $todayStdate = date('Y-m-d',(strtotime($currentdate)))." 00:00";
    	 	  $todayEddate = date('Y-m-d',(strtotime($currentdate)))." 23:59";
			  
			  $WhereQuery=" where request_date>='".$todayStdate."' and request_date<='".$todayEddate."' and to_technician!='' ";
		 }
		 else if($Showday == 'Tomorrow')
		 {
			 $tomorrowStdate = date('Y-m-d', strtotime('+1 days'))." 00:00";
    	 	 $tomorrowEddate = date('Y-m-d', strtotime('+1 days'))." 23:59";
			  
			 $WhereQuery=" where request_date>='".$tomorrowStdate."' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
		 }
		 else if($Showday == 'NextDay')
		 {
			 $nextDayStdate = date('Y-m-d',strtotime('+2 days'))." 00:00";
    	 	 $nextDayEddate = date('Y-m-d',strtotime('+2 days'))." 23:59";
			  
			 $WhereQuery=" where request_date>='".$nextDayStdate."' and request_date<='".$nextDayEddate."' and to_technician!='' ";
		 }
	}
	else if($startdate=='' && $Enddate=='' && ($Showrequest!=0 || $_POST["Showrequest"]!='') && $Showday!='0')
	{
		 if($Showday == 'Today')
		 {
			$todayStdate = date('Y-m-d',(strtotime($currentdate)))." 00:00";
			$todayEddate = date('Y-m-d',(strtotime($currentdate)))." 23:59";
			  
			if($_POST["Showrequest"]==1)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=1 and request_date>='".$todayStdate."' and is_active='1' and request_date<='".$todayEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==2)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=2 and request_date>='".$todayStdate."' and is_active='1' and request_date<='".$todayEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==3)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=3 and request_date>='".$todayStdate."' and is_active='1' and request_date<='".$todayEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==4)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=4 and request_date>='".$todayStdate."' and is_active='1' and request_date<='".$todayEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==5)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=5 and request_date>='".$todayStdate."' and is_active='0' and request_date<='".$todayEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==6)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and request_date>='".$todayStdate."' and request_date<='".$todayEddate."' and to_technician!='' ";
			}
			else
			{ 
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status IN ('0','1','2','3') and is_active='1' and request_date>='".$todayStdate."' and request_date<='".$todayEddate."' and to_technician!='' ";
			
			}
			
		 }
		 else if($Showday == 'Tomorrow')
		 {
			 $tomorrowStdate = date('Y-m-d', strtotime('+1 days'))." 00:00";
    	 	 $tomorrowEddate = date('Y-m-d', strtotime('+1 days'))." 23:59";
			  
			if($_POST["Showrequest"]==1)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=1 and request_date>='".$tomorrowStdate."' and is_active='1' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==2)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=2 and request_date>='".$tomorrowStdate."' and is_active='1' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==3)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=3 and request_date>='".$tomorrowStdate."' and is_active='1' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==4)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=4 and request_date>='".$tomorrowStdate."' and is_active='1' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==5)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=5 and request_date>='".$tomorrowStdate."' and is_active='5' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==6)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and request_date>='".$tomorrowStdate."' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
			}
			else
			{ 
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status IN ('0','1','2','3') and is_active='1' and request_date>='".$tomorrowStdate."' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
			
			}
		 }
		 else if($Showday == 'NextDay')
		 {
			 $nextDayStdate = date('Y-m-d',strtotime('+2 days'))." 00:00";
    	 	 $nextDayEddate = date('Y-m-d',strtotime('+2 days'))." 23:59";
			  
			 if($_POST["Showrequest"]==1)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=1 and request_date>='".$nextDayStdate."' and is_active='1' and request_date<='".$nextDayEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==2)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=2 and request_date>='".$nextDayStdate."' and is_active='1' and request_date<='".$nextDayEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==3)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=3 and request_date>='".$nextDayStdate."' and is_active='1' and request_date<='".$nextDayEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==4)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=4 and request_date>='".$nextDayStdate."' and is_active='1' and request_date<='".$nextDayEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==5)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status=5 and request_date>='".$nextDayStdate."' and is_active='0' and request_date<='".$nextDayEddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==6)
			{
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and request_date>='".$nextDayStdate."' and request_date<='".$nextDayEddate."' and to_technician!='' ";
			}
			else
			{ 
				$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and job_status IN ('0','1','2','3') and is_active='1' and request_date>='".$nextDayStdate."' and request_date<='".$nextDayEddate."' and to_technician!='' ";
			
			}
		 }
	}
	
}
else
{			
	$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and to_technician!='' and job_status!=5 and is_active='1' 
	and request_date<='".$currentdate."' ";
}
  
  
$get_job_data = select_query("SELECT * FROM $db_name.all_job_details ". $WhereQuery." order by id desc ");
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
        <a href="#" class="current">All Ticket Request</a>     	
    </div>
    
    <div class="container-fluid">
    	<div class="row-fluid">
        
            <form name="myformlisting" method="post" action="" autocomplete="off">
        
            <div class="col-sm-2">
                  <select name="Showrequest" id="Showrequest" class="selectpicker input-sm equalssize" data-live-search="true" title="Select">
                    <!--<option value="0" <? //if($_POST['Showrequest']==0){ echo 'Selected'; }?>>Select</option>-->
                    <option value="0" <? if($_POST['Showrequest']==0){ echo "Selected"; }?>>All Pending Ticket</option>
                    <option value="1" <? if($_POST['Showrequest']==1){ echo "Selected"; }?>>Accept/OnTheWay Ticket</option>
                    <!--<option value="2" <? if($_POST['Showrequest']==2){ echo "Selected"; }?>>OnTheWay Ticket</option>-->
                    <option value="3" <? if($_POST['Showrequest']==3){ echo "Selected"; }?>>Working Ticket</option>
                    <option value="4" <? if($_POST['Showrequest']==4){ echo "Selected"; }?>>Reject Ticket</option>
                    <option value="5" <? if($_POST['Showrequest']==5){ echo "Selected"; }?>>Completed Ticket</option>
                    <option value="6" <? if($_POST['Showrequest']==6){ echo "Selected" ;}?>>All Ticket</option>
                  </select>
            </div>
             <div class="col-sm-2">
                  <select name="Showday" id="Showday" class="selectpicker input-sm equalssize" data-live-search="true" title="Select" onchange="hideDate(this.value);">
                    <option value="0" <? if($_POST['Showday']=="0"){ echo 'Selected'; }?>>Select Day</option>
                    <option value="Today" <? if($_POST['Showday']=="Today"){ echo "Selected"; }?>>Today</option>
                    <option value="Tomorrow" <? if($_POST['Showday']=="Tomorrow"){ echo "Selected"; }?>>Tomorrow</option>
                    <option value="NextDay" <? if($_POST['Showday']=="NextDay"){ echo "Selected"; }?>>Day After Tomorrow</option>
                  </select>
            </div>
            <div class="col-sm-2">
                <div class="controls input-append date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="form-control equalsize date-picker selectsize" name="dateStart" id="dateStart" size="16" type="text" value="<?=$_POST['dateStart'];?>" placeholder="Start Date">
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="controls input-append date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                   <input class="form-control equalsize date-picker selectsize" name="dateEnd" id="dateEnd" size="16" type="text" value="<?=$_POST['dateEnd']?>"  placeholder="End Date">
                   <span class="add-on"><i class="icon-th"></i></span>
                </div>
            </div>
            
            <div class="col-sm-2">
            <input value="Submit" name="submit" style="float:right; margin:3px;" class="btn-harish btn-info-harish" type="submit">
            </div>
          </form>
          
         
          
         </div>    
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
            <h5>All Ticket Request</h5>
              <form name="report" action="data_excel.php" id="customer_job_report" method="post" target="_blank">
                <div class="col-md-7" > </div>
                <div class="col-md-1" > 
                    <input type="hidden" name="select_req" id="select_req" value="<?=$_POST['Showrequest'];?>" />
                     <input type="hidden" name="select_day" id="select_day" value="<?=$_POST['Showday'];?>" />
                    <input type="hidden" name="from_date" id="from_date" value="<?=$_POST['dateStart'];?>" />
                    <input type="hidden" name="to_date" id="to_date" value="<?=$_POST['dateEnd'];?>" />
                    <button type="submit" name="submit" class="btn-harish btn-info-harish" style="float:right" value="TripDataExcel"><i class="fa fa-download"></i> Export Excel </button>
                </div>
              </form>
			<a href="add-request-job.php" style="float:right; margin:3px;" class="btn-harish btn-info-harish">Add Ticket Request</a>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg">
              <thead>
                <tr>
                    <th nowrap>S No.</th>
                    <th nowrap>Ticket ID</th>
                    <th nowrap>Call Type</th>
                    <th nowrap>Service Type</th>
                    <th nowrap>Priority</th>
                    <th nowrap>Location</th>
                    <th nowrap>Status</th>
                    <th nowrap>Created On</th>
                    <th nowrap>Product Group</th>
                    <th nowrap>Technician Name</th>
                    <th nowrap>Customer Name</th>
                    <th nowrap>Pin Code</th>
                    <th nowrap>Product</th>
                    <th nowrap>Phone No</th>
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
                                  
                  <td><?php echo $get_job_data[$emp]['call_type']; ?></td>
                  <td><?php echo $get_job_data[$emp]['service_type']; ?></td> 
                  <td><?php echo $get_job_data[$emp]['priority_type']; ?></td> 
                  
                  <td title="<?php echo $get_job_data[$emp]['job_location']; ?>"><?=substr($get_job_data[$emp]['job_location'], 0, 15);?> ..</td>
                                    
                  <td><?php if($get_job_data[$emp]['job_status']==0 && $get_job_data[$emp]['to_technician']!=''){echo "Assign to Installer";} else if($get_job_data[$emp]['job_status']==0 && $get_job_data[$emp]['to_technician']==''){echo "Job Not Assign";} else if($get_job_data[$emp]['job_status']==1){echo "Accept/On the Way";} else if($get_job_data[$emp]['job_status']==2){echo "On the Way";} else if($get_job_data[$emp]['job_status']==3){echo "Working";} else if($get_job_data[$emp]['job_status']==4){echo "Reject";} else if($get_job_data[$emp]['job_status']==5){echo "Complete";}?></td> 
                  <td><?php echo $get_job_data[$emp]['request_date']; ?></td> 
                  
                  
                  <td><?php echo $get_job_data[$emp]['product_group']; ?></td>
                  <td><?php echo $technician_name[0]['emp_name']; ?></td>
                  <td><?php echo $get_job_data[$emp]['customer_name']; ?></td>
                                    
                  <td><?php echo $get_job_data[$emp]['pin_code']; ?></td>
                  <td><?php echo $get_job_data[$emp]['product_group']; ?></td>
                  <td><?php echo $get_job_data[$emp]['customer_phone_no']; ?></td>
                  
                  <td><a onclick="Show_info('GetJobRequestDetails','<?=$get_job_data[$emp]['id'];?>');" class="btn-harish btn-info-harish" data-toggle="modal" data-target=".bs-example-modal-sm">View </a>
                  <? if ($get_job_data[$emp]['job_status'] == 0){ ?>
                  <br /><br /> <a class="btn-harish btn-info-harish" href="edit-request-job.php?id=<?php echo base64_encode($get_job_data[$emp]['id']);?>">Edit</a>
                  <? } else if($get_job_data[$emp]['job_status'] == 4){?>
                  <br /><br /> 
                  <a class="btn-harish btn-info-harish" onclick="closeRecd('<?php echo base64_encode($get_job_data[$emp]["id"])?>')">Close</a>
                  
                  <? } if ($get_job_data[$emp]['pre_image1'] != ""){ ?> <br /><br /> <a class="btn-harish btn-info-harish" href="job_image_view.php?id=<?php echo base64_encode($get_job_data[$emp]['id']);?>" target="_blank">Image</a><? } ?>
                  </td> 
                  
                  <!--<td><?php if($get_job_data[$emp]['job_status']!=1 && $get_job_data[$emp]['job_status']!=3){?>
                  <a class="btn-harish btn-info-harish" href="edit-request-job.php?id=<?php echo base64_encode($get_job_data[$emp]['id']);?>">Edit</a>
                  </br></br>
                  <a class="btn-harish btn-info-harish" onclick="closeRecd('<?php echo base64_encode($get_job_data[$emp]["id"])?>')">Close</a>
                  	<?php } else if($get_job_data[$emp]['job_status']==3){?>
                    <a class="btn-harish btn-info-harish" onclick="confirmPostPond('<?php echo base64_encode($get_job_data[$emp]["id"])?>')">OK</a>
                  	</br></br>
                  	<a class="btn-harish btn-info-harish" onclick="confirmCancel('<?php echo base64_encode($get_job_data[$emp]["id"])?>')">Cancel</a>
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
