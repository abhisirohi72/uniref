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

function dateDifference($date1, $date2)
{ 
	$datetime1 = strtotime($date1);
	$datetime2 = strtotime( $date2);
	$interval  = abs($datetime2 - $datetime1);
	$minutes   = $interval / 60;
	return $minutes;

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

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 id="mySmallModalLabel" class="modal-title"> Technicians Tracking Record
          <??>
        </h4>
      </div>
      
      <div class="modal-body" id="innercontent"> <img id="loading-image_small_all"  src="<?php echo __SITE_URL;?>/img/smallloading.gif"  style="position: absolute; z-index: 9999;  display: none;"/></div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>


<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
        <a href="view_technicians.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
        <a href="#" class="current">Technicians Tracking Day Record</a> 
    </div>
    <div class="container-fluid">
    	<div class="row-fluid">
            <form name="myformlisting" method="post" action="" autocomplete="off">
                <div class="col-sm-2">
                  <div class="controls input-append date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control equalsize date-picker selectsize" name="dateStart" id="dateStart" size="16" type="text" value="<?=$startdate;?>" placeholder="From Time" >
                    <span class="add-on"><i class="icon-th"></i></span> </div>
                </div>
                <div class="col-sm-1"></div>
                <div class="col-sm-2">
                    <!--<input type="submit" name="submit" value="Submit" id="submit" class="btn btn-primary"  />-->
                    <input value="Submit" name="submit" style="width: 80px; margin: 0px 4px 0px 3px; height: 32px; background: rgb(0, 172, 237) none repeat scroll 0% 0%; color: rgb(255, 255, 255); border: medium none; border-radius: 2px;" class=" form-control" type="submit">
                </div>
             </form>
     
     		 <div class="col-sm-1"></div>
           
          </div>
      </div>
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
            <h5>Technicians Tracking Day Record</h5>
			<!--<a href="add-request-job.php" style="float:right; margin:3px;" class="btn btn-info">Add Job Request</a>-->
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg"><!-- class="table table-bordered data-table"-->
              <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Name/ID</th>
                  	<th>Mobile No</th>
                    <th>Location</th>
                    <th>Battery Level</th>
                    <th>Location Time</th>
                    <th>Date</th>
                    <th>View</th>
                </tr>
              </thead>
              <tbody>
			  	<?php 
					for($emp=0;$emp<count($get_people);$emp++) { 
					
					$tech_current_locId = select_query("select max(id) as id from $db_name.technicians_tracking where 
								tech_id='".$get_people[$emp]['id']."' and Date_of_journey='$startdate' and is_active='1'  group by tech_id");			
				
					$get_tech_loc = select_query("SELECT * FROM $db_name.technicians_tracking WHERE id='".$tech_current_locId[0]['id']."'  ");
					//echo "<pre>";print_r($get_tech_loc);die;	
					
					$get_tech_leave_data = select_query("SELECT * FROM $db_name.leave_request WHERE tech_id='".$get_people[$emp]['id']."' and
											is_active='1' and is_status='1' and ((from_date BETWEEN '".$startdate."' AND '".$startdate."')  
											or (to_date BETWEEN '".$startdate."' AND '".$startdate."'))");
				?>
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_people[$emp]['emp_name']."/ ".$get_people[$emp]['technician_id'];?><font color="red"><? if(count($get_tech_leave_data)>0){ echo "<br/>"; echo "Leave";} ?></font></td>
                  <td><?php echo $get_people[$emp]['mobile_no']; ?></td>
                  <td><?php echo $get_tech_loc[0]['job_location']; ?></td>
                  <td><?php echo $get_tech_loc[0]['battery_level']; ?></td>
                  <td><?php echo $get_tech_loc[0]['location_time']; ?></td>  
                  <td><?php echo $get_tech_loc[0]['Date_of_journey']; ?></td> 
                  
                  <td> 
                  <a class="btn btn-info"  onclick="window.open('<? echo __SITE_URL;?>/snailmapmyindia_new.php?vid=<?=$get_people[$emp]['id']?>&requestTime=<?=$startdate?>&branchid=<?=$_SESSION['user_id']?>','popUpWindow','height=600,width=900,left=100,top=50,scrollbars=yes,menubar=no'); return false;" >Map</a>
                  <br /><br />
                  <a onclick="Show_info('GetAllLocationWithDate','<?php echo $get_people[$emp]['mobile_no'];?>','<?php echo $get_people[$emp]['id'];?>','<?php echo $_SESSION['user_id'];?>','<?php echo $startdate;?>');" class="btn btn-info" data-toggle="modal" data-target=".bs-example-modal-sm">View </a>
                  
                 
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
			beforeSend : function()
			{
				$("#loading-image_small_all").show();
				//document.getElementById("loading-image_small_all").show();
			},
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
