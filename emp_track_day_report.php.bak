<?php 
include('inc/header.php'); 
include_once(__DOCUMENT_ROOT.'/classes/cls_db_manager.php');
include_once(__DOCUMENT_ROOT.'/classes/cls_calculate_distance.php');

function dateDifferenceSecond($date1, $date2)
{ 
	$datetime1 = strtotime($date1);
	$datetime2 = strtotime( $date2);
	$interval  = abs($datetime2 - $datetime1);
	$minutes   = $interval / 60;
	return $minutes*(60);

}

function minDifferenceForJourney($Seconds)
{ 
	$mins=$Seconds/60;
	$diff = $mins;
	$hour = $diff/60; // in day

	 $hourFix = floor($hour);
	 $hourPen = $hour - $hourFix;
	 if($hourPen > 0)
	 {
		  $min = $hourPen*(60); // in hour (1 hour = 60 min)
		  $minFix = floor($min);
		  $minPen = $min - $minFix;
		  if($minPen > 0)
		  {
			  $sec = $minPen*(60); // in sec (1 min = 60 sec)
			  $secFix = floor($sec);
		  }
	 }

	 if($hourFix > 0)
	 {
		 $str.= $hourFix.":";
	 }
	 else
	 {
		 $str.= "0:";
	 }
	
	 if($minFix > 0)
	 {
		 $str.= $minFix.":";
	 }
	 else
	 {
		 $str.= "0:";
	 }
	
	 if($secFix > 0)
	 {
		 $str.= $secFix;
	 }
	 else
	 {
		 $str.= "0";
	 }
	 return $str;

}

function getEmpTravelDistance($phoneNo,$installerid,$branchid,$journeyDate)
{
	$condition = "";
	$employee_track="employee_track";
	
	$calcObj = new calculate_distance();
	
	$todayTravelData = select_query("select * from $employee_track.emp_today_tracking where phone_no='".$phoneNo."' and 
	emp_id='".$installerid."' and Date_of_journey='".$journeyDate."' and login_id='".$branchid."' and is_active='1' order by id");
	
	$distance = 0;
	$totalDistance = 0;
	for($ep=1;$ep<count($todayTravelData);$ep++)
	{
		$PointArray[0]['lat']  = $todayTravelData[$ep]['gps_latitude'];
		$PointArray[0]['long'] = $todayTravelData[$ep]['gps_longitude'];

		$PointArray[1]['lat']  = $todayTravelData[$ep - 1]['gps_latitude'];
		$PointArray[1]['long'] = $todayTravelData[$ep - 1]['gps_longitude'];
		
		if ($PointArray[1]['lat'] != "") {

			$distance = $calcObj->calc_distance($PointArray);
			
			###### Sum of distance############
			$totalDistance = $totalDistance + $distance;
		}
	}
	
	return $totalDistance;
}

if(isset($_POST["submit"]))
{
	//echo "<pre>";print_r($_POST);die;
	
	$startdate = date('Y-m-d',strtotime($_POST['dateStart']));
	//$location_no = $_POST['location_no'];
	
	$get_emp_data = select_query("SELECT * FROM $employee_track.login_emp_details WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by created_date desc ");
	
}
else
{
	$startdate = date('Y-m-d');
	//$location_no = 1;
	$get_emp_data = select_query("SELECT * FROM $employee_track.login_emp_details WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by created_date desc ");
}

//echo "<pre>";print_r($get_emp_data);die;
?>

<link href="<?php echo __SITE_URL;?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 id="mySmallModalLabel" class="modal-title"> Employee Tracking Record
          <??>
        </h4>
      </div>
      <div class="modal-body" id="innercontent"> </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>


<div class="modal fade bs-example-modal-sm-job" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 id="mySmallModalLabel" class="modal-title"> Employee Job Record
          <??>
        </h4>
      </div>
      <div class="modal-body" id="innercontent_job"> </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
        <a href="index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
        <a href="#" class="current">Employee Tracking Day Record</a> 
    </div>
    <form name="myformlisting" method="post" action="">
    	<div class="col-sm-1"></div>
      
    	<div class="col-md-2 col-lg-2">
          <div class="controls input-append date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input class="form-control date-picker" name="dateStart" id="dateStart" size="16" type="text" value="<?=$startdate;?>" placeholder="From Time" >
            <span class="add-on"><i class="icon-th"></i></span> </div>
        </div>
        <div class="col-sm-1"></div>
        <div class="col-md-1">
            <!--<input type="submit" name="submit" value="Submit" id="submit" class="btn btn-primary"  />-->
            <input value="Submit" name="submit" style="width: 80px; margin: 0px 4px 0px 3px; height: 32px; background: rgb(0, 172, 237) none repeat scroll 0% 0%; color: rgb(255, 255, 255); border: medium none; border-radius: 2px;" class=" form-control" type="submit">
        </div>
     </form>
     <div class="col-sm-5"></div>
      <form name="report" action="data_excel.php" id="employe_day_report" method="post" target="_blank">
      	<div class="col-md-1" > 
        	<input type="hidden" name="today_date" id="today_date" value="<?=$startdate;?>" />
            <button type="submit" name="submit" class="btn btn-sm btn-danger" style="float:right" value="TodayTrackingExcel"><i class="fa fa-download"></i> Export Excel </button>
        </div>
      </form>
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
            <h5>Employee Tracking Day Record</h5>
			<!--<a href="add-request-job.php" style="float:right; margin:3px;" class="btn btn-info">Add Job Request</a>-->
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table"><!-- class="table table-bordered data-table"-->
              <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Employee Name</th>
                    <th>Current Location</th>
					<th>Created by Him</th>
                    <th>Task Assigned</th>
                    <th>Day In Time</th>
                    <th>Day Out Time</th>
                    <th>Total Working Hrs</th>
                    <th>KM</th>
                    <th>TA</th>
                </tr>
              </thead>
              <tbody>
			  	<?php 
					for($emp=0;$emp<count($get_emp_data);$emp++) { 
					
					$emp_track_data = select_query("select job_location,created_datetime from $employee_track.emp_today_tracking where 
						phone_no='".$get_emp_data[$emp]['mobile_no']."' and emp_id='".$get_emp_data[$emp]['id']."' and is_active='1'
						and login_id='".$_SESSION['user_id']."' and Date_of_journey='".$startdate."'  order by id desc limit 1");
						
					$emp_own_job = select_query("SELECT count(*) as Total_job FROM $employee_track.request WHERE 
						emp_id='".$get_emp_data[$emp]['id']."' and phone_no='".$get_emp_data[$emp]['mobile_no']."' and 
						(date(fromtime)='".$startdate."' or date(totime)='".$startdate."') and latitude is null 
						and longtitude is null and current_record=1 and login_id='".$_SESSION['user_id']."' ");
		
					$emp_assign_job = select_query("SELECT count(*) as Total_job FROM $employee_track.request WHERE 
						emp_id='".$get_emp_data[$emp]['id']."' and phone_no='".$get_emp_data[$emp]['mobile_no']."' and 
						(date(fromtime)='".$startdate."' or date(totime)='".$startdate."') and latitude is not null 
						and longtitude is not null and current_record IN (0,1,3) and login_id='".$_SESSION['user_id']."' ");
					
					$emp_day_in_out = select_query("SELECT * FROM $employee_track.installer_attendence_tbl WHERE 
						inst_id='".$get_emp_data[$emp]['id']."' and mobile_no='".$get_emp_data[$emp]['mobile_no']."' and 
						req_date='".$startdate."'  and is_active=1 and login_id='".$_SESSION['user_id']."' ");
					
					if(count($emp_day_in_out)>0)
					{
						if($emp_day_in_out[0]['start_time']!=''){$emp_day_in = date("d/m/Y h:i A",strtotime($emp_day_in_out[0]['start_time']));}
						else{$emp_day_in = '';}
						
						if($emp_day_in_out[0]['end_time']!=''){$emp_day_out = date("d/m/Y h:i A",strtotime($emp_day_in_out[0]['end_time']));}
						else{$emp_day_out = '';}
						
						if($emp_day_in_out[0]['end_time']!='')
						{
							$journeyHrSec = dateDifferenceSecond($emp_day_in_out[0]['start_time'], $emp_day_in_out[0]['end_time']);
							$total_hr = minDifferenceForJourney($journeyHrSec);
							
							$getdistance = getEmpTravelDistance($get_emp_data[$emp]['mobile_no'],$get_emp_data[$emp]['id'],$_SESSION['user_id'],$startdate);
							
						}else{$total_hr = ''; $getdistance = 0;}
						
					} else {
						$emp_day_in = '';
						$emp_day_out = '';
						$total_hr = '';
						$getdistance = 0;
					}
					
					//echo "<pre>";print_r($emp_track_data);die;
									
				?>
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_emp_data[$emp]['emp_name']; ?></td>                  
                  <? if($emp_track_data[0]['created_datetime']!=''){$loc_in_time = ' - '.date("d/m/Y h:i A",strtotime($emp_track_data[0]['created_datetime']));} else {$loc_in_time = '';}
				  ?>
				                    
                  <td><a onclick="Show_info('GetAllLocationWithDate','<?php echo $get_emp_data[$emp]['mobile_no'];?>','<?php echo $get_emp_data[$emp]['id'];?>','<?php echo $_SESSION['user_id'];?>','<?php echo $startdate;?>');" data-toggle="modal" data-target=".bs-example-modal-sm"><?=$emp_track_data[0]['job_location'].''.$loc_in_time;?> </a></td>
                  
                  <td><a onclick="Show_job_info('GetEmpOwnJob','<?php echo $get_emp_data[$emp]['mobile_no'];?>','<?php echo $get_emp_data[$emp]['id'];?>','<?php echo $_SESSION['user_id'];?>','<?php echo $startdate;?>');" data-toggle="modal" data-target=".bs-example-modal-sm-job"><?php echo $emp_own_job[0]['Total_job']; ?></a></td>
                  <td><a onclick="Show_job_info('GetEmpAssignJob','<?php echo $get_emp_data[$emp]['mobile_no'];?>','<?php echo $get_emp_data[$emp]['id'];?>','<?php echo $_SESSION['user_id'];?>','<?php echo $startdate;?>');" data-toggle="modal" data-target=".bs-example-modal-sm-job"><?php echo $emp_assign_job[0]['Total_job']; ?></a></td>
                  <td><?php echo $emp_day_in; ?></td>
                  <td><?php echo $emp_day_out; ?></td>
                  <td><?php echo $total_hr; ?></td>
                  <td><?php echo $getdistance; ?></td>
                  <td><?php echo ($getdistance*2); ?></td>
			
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
<script src="<? echo __SITE_URL?>/js/jquery.min.js"></script> 
<script src="<? echo __SITE_URL?>/js/jquery.ui.custom.js"></script> 
<script src="<? echo __SITE_URL?>/js/bootstrap.min.js"></script> 
<script src="<? echo __SITE_URL?>/js/jquery.uniform.js"></script> 
<script src="<? echo __SITE_URL?>/js/select2.min.js"></script> 
<script src="<? echo __SITE_URL?>/js/jquery.dataTables.min.js"></script> 
<script src="<? echo __SITE_URL?>/js/matrix.js"></script> 
<script src="<? echo __SITE_URL?>/js/matrix.tables.js"></script>

<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/libs/bootstrap/bootstrap-datetimepicker.js" charset="UTF-8"></script>
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

<script type="text/javascript">

function Show_info(action,mobile_no,rid,branch,date)
{
    $.ajax({
            type:"GET",
            url:"show_tracking_info.php?action="+action,

            data:"Phoneno="+mobile_no+"&rid="+rid+"&branch="+branch+"&daterange="+date,
            success:function(msg){

            document.getElementById("innercontent").innerHTML = msg;

            }
    });
}

function Show_job_info(action,mobile_no,rid,branch,date)
{
    $.ajax({
            type:"GET",
            url:"show_tracking_info.php?action="+action,

            data:"Phoneno="+mobile_no+"&rid="+rid+"&branch="+branch+"&daterange="+date,
            success:function(msg){

            document.getElementById("innercontent_job").innerHTML = msg;

            }
    });
}

</script>

</body>
</html>
