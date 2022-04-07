<?php 
session_start();
include('inc/header.php'); 

$id_roles = $_SESSION['id_roles'];

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

if(isset($_POST["submit"]))
{
	//echo "<pre>";print_r($_POST);die;
	$startdate = date('Y-m-d',strtotime($_POST['dateStart']));	
}
else
{
	$startdate = date('Y-m-d');
}


$get_people = select_query("SELECT * FROM $db_name.technicians_login_details WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by id  ");
	
//echo "<pre>";print_r($get_people);die;
?>

<link href="<?php echo __SITE_URL;?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

<div class="modal fade bs-example-modal-sm-job" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 id="mySmallModalLabel" class="modal-title"> Technicians KM Record
          
        </h4>
      </div>
      <div class="modal-body" id="innercontent_job"> <img id="loading-image_small_all_job"  src="<?php echo __SITE_URL;?>/img/smallloading.gif"  style="position: absolute; z-index: 9999;  display: none;"/></div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view_technicians.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
    <a href="#" class="current">All Technicians Attendance</a> 
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
      <!--<form name="report" action="data_excel.php" id="employe_day_report" method="post" target="_blank">
      	<div class="col-md-1" > 
        	<input type="hidden" name="today_date" id="today_date" value="<?=$startdate;?>" />
            <button type="submit" name="submit" class="btn btn-sm btn-danger" style="float:right" value="TodayTrackingExcel"><i class="fa fa-download"></i> Export Excel </button>
        </div>
      </form>-->
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
            <h5>All Technicians Attendance</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg">
              <thead>
                <tr>
                  <th>SNo</th>
                  <th>Name/ID</th>
                  <th>Mobile No</th>
                  <th>Day Start Location</th>
                  <th>Day End Location</th>
                  <!--<th>Task Created</th>-->
                  <th>Task Assigned</th>
                  <th>Current Status</th>
                  <th>Day In Time</th>
                  <th>Day Out Time</th>
                  <th>Total Working Hrs</th>
                  <th>Start KM</th>
                  <th>End KM</th>
                  <th>Total Kms Travelled</th>
                </tr>
              </thead>
              <tbody>
			  	<?php 
				
				for($emp=0;$emp<count($get_people);$emp++) { 
				
				$service_done = select_query("SELECT count(id) as no_of_job FROM $db_name.all_job_details WHERE loginid='".$_SESSION['user_id']."' 
				and to_technician='".$get_people[$emp]['id']."' and request_date='".$startdate."' and is_active='1'");
												
					/*$emp_own_job = select_query("SELECT count(*) as Total_job FROM $employee_track.request WHERE 
						emp_id='".$get_emp_data[$emp]['id']."' and phone_no='".$get_emp_data[$emp]['mobile_no']."' and 
						(date(fromtime)='".$startdate."' or date(totime)='".$startdate."') and latitude is null 
						and longtitude is null and current_record=1 and login_id='".$_SESSION['user_id']."' ");
		
					$emp_assign_job = select_query("SELECT count(*) as Total_job FROM $employee_track.request WHERE 
						emp_id='".$get_emp_data[$emp]['id']."' and phone_no='".$get_emp_data[$emp]['mobile_no']."' and 
						(date(fromtime)='".$startdate."' or date(totime)='".$startdate."') and latitude is not null 
						and longtitude is not null and current_record IN (0,1,3) and login_id='".$_SESSION['user_id']."' ");*/
					
					$tech_day_in_out = select_query("SELECT * FROM $db_name.installer_attendence_tbl WHERE 
						inst_id='".$get_people[$emp]['id']."'  and  req_date='".$startdate."'  and is_active='1' and 
						login_id='".$_SESSION['user_id']."' ");
					
					//echo "<pre>";print_r($tech_day_in_out);//die;
					if(count($tech_day_in_out)>0)
					{
						
						if($tech_day_in_out[0]['start_time']!=''){$tech_day_in = date("h:i A",strtotime($tech_day_in_out[0]['start_time']));}
						else{$tech_day_in = '';}
						
						if($tech_day_in_out[0]['end_time']!=''){$tech_day_out = date("h:i A",strtotime($tech_day_in_out[0]['end_time']));}
						else{$tech_day_out = '';}
												
						if($tech_day_in_out[0]['end_time']!='')
						{
							$journeyHrSec = dateDifferenceSecond($tech_day_in_out[0]['start_time'], $tech_day_in_out[0]['end_time']);
							$total_hr = minDifferenceForJourney($journeyHrSec);
							
							$start_km = $tech_day_in_out[0]['odometer_start_km'];
							$end_km   = $tech_day_in_out[0]['odometer_end_km'];
							
						}else{$total_hr = '';}
						
						if($tech_day_in_out[0]['odometer_start_km']!='' && $tech_day_in_out[0]['odometer_end_km']!='')
						{
							$getdistance = $tech_day_in_out[0]['odometer_end_km'] - $tech_day_in_out[0]['odometer_start_km'];
						} else{$getdistance = '0';}
						
					} else {
						$tech_day_in = '';
						$tech_day_out = '';
						$total_hr = '';
						$start_km = '';
						$end_km = '';
						$getdistance = '0';
					}
				
				?>
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_people[$emp]['emp_name'].'/'.$get_people[$emp]['technician_id']; ?></td>
                  <td><?php echo $get_people[$emp]['mobile_no']; ?></td>
                  <td><?php echo $tech_day_in_out[0]['start_location']; ?></td>
                  <td><?php echo $tech_day_in_out[0]['end_location']; ?></td>
                  <td><?php echo $service_done[0]['no_of_job']; ?></td>
                  <td><?php if( $get_people[$emp]['job_status'] == '1' ) { echo "On the Way";} else if( $get_people[$emp]['job_status'] == '2' ) { echo "Currently Working";} else if( $get_people[$emp]['job_status'] == '5' ) { echo "Completed";} else {echo "No Action";} ?></td> 
                  <td><?php echo $tech_day_in; ?></td>
                  <td><?php echo $tech_day_out; ?></td>
                  <td><?php echo $total_hr; ?></td>
                  <td><a onclick="Show_job_info('GetEmpStartKm','<?php echo $get_people[$emp]['mobile_no'];?>','<?php echo $get_people[$emp]['id'];?>','<?php echo $_SESSION['user_id'];?>','<?php echo $startdate;?>');" data-toggle="modal" data-target=".bs-example-modal-sm-job"><?php echo $start_km; ?></a></td>
                  <td><a onclick="Show_job_info('GetEmpEndKm','<?php echo $get_people[$emp]['mobile_no'];?>','<?php echo $get_people[$emp]['id'];?>','<?php echo $_SESSION['user_id'];?>','<?php echo $startdate;?>');" data-toggle="modal" data-target=".bs-example-modal-sm-job"><?php echo $end_km; ?></a></td>
                  <td><?php echo $getdistance." Kms"; ?></td>
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

function Show_job_info(action,mobile_no,rid,branch,date)
{
    $.ajax({
            type:"GET",
            url:"show_tracking_info.php?action="+action,

            data:"Phoneno="+mobile_no+"&rid="+rid+"&branch="+branch+"&daterange="+date,
			beforeSend : function()
			{
				$("#loading-image_small_all_job").show();
				//document.getElementById("loading-image_small_all_job").show();
			},
            success:function(msg){

            document.getElementById("innercontent_job").innerHTML = msg;

            }
    });
}

</script>
</body>
</html>
