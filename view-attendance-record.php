<?php 
include('inc/header.php'); 

if(isset($_POST["submit"]))
{
	//echo "<pre>";print_r($_POST);die;
	
	$startdate = date('Y-m-d',strtotime($_POST['dateStart']));
	//$Enddate = date('Y-m-d',strtotime($_POST['dateEnd']));
	$location_no = $_POST['location_no'];
	
	$get_emp_data = select_query("SELECT * FROM $employee_track.login_emp_details WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by created_date desc ");
	
}
else
{
	$startdate = date('Y-m-d',strtotime("-1 days"));
	//$Enddate = date('Y-m-d',strtotime("-1 days"));
	$location_no = 5;
	$get_emp_data = select_query("SELECT * FROM $employee_track.login_emp_details WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by created_date desc ");
}

//echo "<pre>";print_r($get_emp_data);die;
?>

<link href="<?php echo __SITE_URL;?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
        <a href="index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
        <a href="#" class="current">Attendance Record</a> 
    </div>
    <form name="myformlisting" method="post" action="">
    	<div class="col-sm-1"></div>
        <div class="col-md-2 col-lg-2">
              <select name="location_no" id="location_no" >
                <?php for($lc=1;$lc<=15;$lc++){?>
                <option value="<?=$lc?>" <? if($location_no==$lc){ echo "Selected"; }?>><?=$lc?></option>
                <?php } ?>
              </select>
        </div>
    	<div class="col-md-2 col-lg-2">
          <div class="controls input-append date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input class="form-control date-picker" name="dateStart" id="dateStart" size="16" type="text" value="<?=$startdate;?>" placeholder="From Time" >
            <span class="add-on"><i class="icon-th"></i></span> </div>
        </div>
        <!--<div class="col-md-2 col-lg-2">
              <div class="controls input-append date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                <input class="form-control date-picker" name="dateEnd" id="dateEnd" size="16" type="text" value="<?=$Enddate;?>" placeholder="To Time">
                <span class="add-on"><i class="icon-th"></i></span> </div>
        </div>-->
        <div class="col-md-1">
            <!--<input type="submit" name="submit" value="Submit" id="submit" class="btn btn-primary"  />-->
            <input value="Submit" name="submit" style="width: 80px; margin: 0px 4px 0px 3px; height: 32px; background: rgb(0, 172, 237) none repeat scroll 0% 0%; color: rgb(255, 255, 255); border: medium none; border-radius: 2px;" class=" form-control" type="submit">
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
            <h5>Attendance Record</h5>
			<!--<a href="add-request-job.php" style="float:right; margin:3px;" class="btn btn-info">Add Job Request</a>-->
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered"><!-- class="table table-bordered data-table"-->
              <thead>
                <tr>
                    <th rowspan="2">Sr No.</th>
                    <th rowspan="2">Employee Name</th>
                    
                    <? for($i=0;$i<$location_no;$i++){ ?>

                       <th colspan="3">Location <?=$i+1;?> </th>

				  <? }?>
			  
                </tr>
                <tr>
                <? for($i=0;$i<$location_no;$i++){ ?>
                
                    <th nowrap>Start</th>
                    <th nowrap>End</th>
                    <th nowrap>TA</th>
                
                <? }?>
                </tr>
              </thead>
              <tbody>
			  	<?php 
					
					for($emp=0;$emp<count($get_emp_data);$emp++) { 
					
					$emp_job_count = select_query("select * from $employee_track.request where phone_no='".$get_emp_data[$emp]['mobile_no']."' and 
		emp_id='".$get_emp_data[$emp]['id']."' and current_record='1' and completion_time is not null and sequence_date>='".$startdate."' and sequence_date<='".$startdate."' and login_id='".$_SESSION['user_id']."' order by sequence_no");
					
					//echo "<pre>";print_r($emp_job_count);die;
									
				?>
                <tr class="gradeX">
                  <td rowspan="2"><?php echo $emp+1; ?></td>
                  <td rowspan="2"><?php echo $get_emp_data[$emp]['emp_name']; ?></td>                  
                  <? for($i=0;$i<$location_no;$i++){ ?>

                       <td colspan="3"><?=$emp_job_count[$i]['job_location'];?> </td>

				  <? }?>
                </tr>
                <tr>
                <? for($i=0;$i<$location_no;$i++){ 
					
					$get_emp_s_e = select_query("SELECT * FROM $employee_track.installer_time_extension WHERE  phone_no='".$emp_job_count[$i]['phone_no']."' and inst_id='".$emp_job_count[$i]['emp_id']."' and req_id='".$emp_job_count[$i]['id']."'");
					
					$get_emp_td = select_query("SELECT * FROM $employee_track.installer_conveyance_sheet WHERE  installer_id='".$emp_job_count[$i]['emp_id']."' and job_id='".$emp_job_count[$i]['id']."'");
				?>
                
                    <td><?=$get_emp_s_e[0]['job_start_time'];?></td>
                    <td><?=$get_emp_s_e[0]['job_close_time'];?></td>
                    <td><?=$get_emp_td[0]['fare'];?></td>
                
                <? }?>
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
</body>
</html>
