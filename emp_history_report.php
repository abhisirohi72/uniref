<?php 
session_start();
include('inc/header.php'); 
include_once(__DOCUMENT_ROOT.'/classes/cls_db_manager.php');
include_once(__DOCUMENT_ROOT.'/classes/cls_calculate_distance.php');

$id_roles=$_SESSION['id_roles'];
$currentdate = date('Y-m-d');

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

function getEmpTriplDistance($phoneNo,$installerid,$branchid,$trip_id)
{
	$condition = "";
	$employee_track="employeetrack_uniled";
	
	$calcObj = new calculate_distance();
	
	$todayTravelData = select_query("select * from $employee_track.emp_today_tracking where phone_no='".$phoneNo."' and 
	emp_id='".$installerid."' and trip_id='".$trip_id."' and login_id='".$branchid."' and is_active='1' order by id");
	
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
	
	$startdate = $_POST['dateStart'];
    $Enddate   = $_POST['dateEnd'];
	$Showemp   = $_POST["Showemp"];
	
	$emp_phone=explode("##",$Showemp);
		
	$phone=$emp_phone[0];
	$emp_id=$emp_phone[1];
	
	if($startdate != '' && $Enddate != '' && ($Showemp !='0' && $Showemp !=''))
	{
		
		$getEmpTripData = select_query("select * from $employee_track.employee_trip_details where emp_id='".$emp_id."' and DateOf_journey>='".$startdate."' and 
						DateOf_journey<='".$Enddate."' and trip_end_time is not null and is_active='0' and login_id='".$_SESSION['user_id']."' order by id desc");
		
		//echo "<pre>";print_r($getEmpTripData);die;
	} 
	else if($startdate == '' && $Enddate == '' && ($Showemp !='0' && $Showemp !=''))
	{
		$currentTime = date("Y-m-d");
		
		$getEmpTripData = select_query("select * from $employee_track.employee_trip_details where emp_id='".$emp_id."' and DateOf_journey>='".$currentTime."' and 
						DateOf_journey<='".$currentTime."' and trip_end_time is not null and is_active='0' and login_id='".$_SESSION['user_id']."' order by id desc");
		
		//echo "<pre>";print_r($getEmpTripData);die;
	}
	else
	{
		$_SESSION['unsuccess_msg'] = 'set';	
	}
	
}
  
?>

<style>
.alert-error-new {
    background-color: #C0C0C0;
    border-color: #C0C0C0;
    color: #fff;
}
</style>

<!--<link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/custom.css" />-->

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 id="mySmallModalLabel" class="modal-title"> Employees Trip History
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
        <a href="#" class="current">Employees Trip History</a>     	
    </div>
    <div class="container-fluid">
    	<div class="row-fluid">
        
        <form name="myformlisting" method="post" action="">
    
    <? 
	if($id_roles==1) {
		
		$get_emp_recd = select_query("SELECT id,concat(mobile_no,' - ',emp_name) as emp_name,concat(mobile_no,'##',id) as emp_details, 
			  day_start_end FROM $employee_track.login_emp_details WHERE is_active='1' and loginid='".$_SESSION['user_id']."'");
	
	} else {
		
		$get_emp_recd = select_query("SELECT id,concat(mobile_no,' - ',emp_name) as emp_name,concat(mobile_no,'##',id) as emp_details, 
			  day_start_end FROM $employee_track.login_emp_details WHERE is_active='1' and id_roles='5' and loginid='".$_SESSION['user_id']."'");
	}
	?>
    
    <div class="col-sm-2">
          <select name="Showemp" id="Showemp" class="selectpicker" data-live-search="true" title="Select">
           	<option value="0" <? if($_POST['Showemp']==0){ echo 'Selected'; }?>>Select Employee</option> 
			<?php for($rq=0;$rq<count($get_emp_recd);$rq++) { ?>
              <option value="<?=$get_emp_recd[$rq]['emp_details'];?>"<? if($_POST['Showemp']==$get_emp_recd[$rq]['emp_details']) {?> selected="selected" <? } ?>><?=$get_emp_recd[$rq]['emp_name'];?></option>
              
            <? } ?>
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
    <input value="Submit" name="submit" style="width: 80px; margin: 0px 4px 0px 3px; height: 32px; background: rgb(127, 126, 126) none repeat scroll 0% 0%; color: rgb(255, 255, 255); border: medium none; border-radius: 2px;" class=" form-control" type="submit">
    </div>
    </form>
  </div>
  </div>
  </div>
  
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        
        <?php if(isset($_SESSION['unsuccess_msg'])) {  ?>
            <div class="alert alert-error-new error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> Kindly Select Employee Name & Date to view Report. </span>
            </div>
		  <?php } 
		  unset($_SESSION['unsuccess_msg']);
		  ?>
        
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Employees Trip History</h5>			
          </div>
          <div class="widget-content nopadding">
          <?php if(isset($_POST["submit"])) {?>
            <table class="table table-bordered data-table  table-responsive">
            <?php } else {?>
            <table class="table table-bordered data-table  table-responsive-sm">
            <?php } ?>
              <thead>
                <tr>
                    <th nowrap>S No.</th>
                    <!--<th nowrap>Job ID</th>-->
                    <th nowrap>Phone No/ Name</th>
                    <th nowrap>Start Time</th>
                    <th nowrap>Trip Start Location</th>
                    <th nowrap>Trip Destination</th>
                    <th nowrap>End Time</th>
                    <th nowrap>Trip End Location</th>
                    <th nowrap>Mode of Travel</th>
                    <th nowrap>Going To</th>
                    <th nowrap>ETA</th>
                    <th nowrap>KM</th>
                    <th nowrap>Amount</th>
                    <th nowrap>Trip Id</th>
                    <th nowrap>View</th>
                </tr>
              </thead>
              <tbody>
			  	<?php 
				
				$empDetails = select_query("select id,emp_name,mobile_no from $employee_track.login_emp_details where id='".$emp_id."'");
				
				for($emp=0;$emp<count($getEmpTripData);$emp++) { 
				
				$ta_pay_status = $getEmpTripData[$emp]['ta_pay_status'];
				
				$empTravelDistance = getEmpTriplDistance($empDetails[0]['mobile_no'],$getEmpTripData[$emp]['emp_id'],$_SESSION['user_id'],$getEmpTripData[$emp]['id']);
				
				if($getEmpTripData[$emp]['ta_pay_status']==1)
				{	
					if($getEmpTripData[$emp]['transport_id'] == 1 || $getEmpTripData[$emp]['transport_id'] == 2)
					{
						$transportModeVal = select_query("select * from $employee_track.mode_of_transport where id='".$getEmpTripData[$emp]['transport_id']."' and is_active='1' ");
						
						$fare = ($empTravelDistance * $transportModeVal[0]['rate_per_km']);
						
					} else {
						$fare = $getEmpTripData[$emp]['payment_amount'];
					}
					
				} 
				else 
				{
					 $fare = 0.00;
				}
									
				?>
                
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  
                  <td><?php echo $empDetails[0]['mobile_no']." / ".$empDetails[0]['emp_name']; ?></td>
                  
                  <td><?php if($getEmpTripData[$emp]['trip_start_time'] != '0000-00-00 00:00:00' && $getEmpTripData[$emp]['trip_start_time'] != ''){echo date("d/m/Y h:i A",strtotime($getEmpTripData[$emp]['trip_start_time']));} ?></td>
                  
                  <td title="<?php echo $getEmpTripData[$emp]['trip_start_location']; ?>"><?=$getEmpTripData[$emp]['trip_start_location']." LatLong:-".$getEmpTripData[$emp]['trip_start_latitude'].",".$getEmpTripData[$emp]['trip_start_longitude'];?> </td>
                  
                  <td title="<?php echo $getEmpTripData[$emp]['trip_end_location']; ?>"><?=$getEmpTripData[$emp]['trip_end_location']." LatLong:-".$getEmpTripData[$emp]['trip_end_latitude'].",".$getEmpTripData[$emp]['trip_end_longitude'];?> </td>
                  
                  <td><?php if($getEmpTripData[$emp]['trip_end_time'] != '0000-00-00 00:00:00' && $getEmpTripData[$emp]['trip_end_time'] != ''){echo date("d/m/Y h:i A",strtotime($getEmpTripData[$emp]['trip_end_time']));} ?></td>
                  
                  <td title="<?php echo $getEmpTripData[$emp]['trip_reched_location']; ?>"><?=$getEmpTripData[$emp]['trip_reched_location']." LatLong:-".$getEmpTripData[$emp]['trip_reched_latitude'].",".$getEmpTripData[$emp]['trip_reched_longitude'];?> </td>
                  
                  <td><?php echo $getEmpTripData[$emp]['transport_mode']; ?></td>
                  <td><?php echo $getEmpTripData[$emp]['destination_name']; ?></td>
                  <td><?php echo $getEmpTripData[$emp]['eta_travel_time']; ?></td>
                  <td><?php echo $empTravelDistance; ?></td>
                  <td><?php echo $fare; ?></td>
                  <td><?php echo $getEmpTripData[$emp]['id']; ?></td>
                  
                  <td><a class="btn-harish btn-info-harish"  onclick="window.open('<? echo __SITE_URL;?>/snailmapmyindia.php?vid=<?=$getEmpTripData[$emp]['id']?>&startTime=<?=$getEmpTripData[$emp]['trip_start_time']?>&Endtime=<?= $getEmpTripData[$emp]['trip_end_time']?>','popUpWindow','height=600,width=900,left=100,top=50,scrollbars=yes,menubar=no'); return false;" >Map</a>
                  <br /><br />
                  <a onclick="Show_info('GetTripLocation','<?=$getEmpTripData[$emp]['id'];?>','<?=$getEmpTripData[$emp]['trip_start_time'];?>','<?=$getEmpTripData[$emp]['trip_end_time'];?>');" class="btn-harish btn-info-harish" data-toggle="modal" data-target=".bs-example-modal-sm">View </a>
                  
                  <? if ($getEmpTripData[$emp]['image'] != ""){ ?> <br /><br /> <a class="btn-harish btn-info-harish" href="trip-bill-image-view.php?id=<?php echo base64_encode($getEmpTripData[$emp]['id']);?>" target="_blank">Image</a><? } ?>
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

<script type="text/javascript">

function Show_info(action,rid,starttime,endtime)
{
    $.ajax({
            type:"GET",
            url:"show_tracking_info.php?action="+action,

            data:"rid="+rid+"&starttime="+starttime+"&endtime="+endtime,
            success:function(msg){

            document.getElementById("innercontent").innerHTML = msg;

            }
    });
}

</script>

</body>
</html>
