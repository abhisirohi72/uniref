<?php 
include('inc/header.php'); 

if(isset($_POST["submit"]))
{
	//echo "<pre>";print_r($_POST);die;
	
	$startdate = date('Y-m-d',strtotime($_POST['dateStart']));
	$location_no = $_POST['location_no'];
	
	$get_emp_data = select_query("SELECT * FROM $employee_track.login_emp_details WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by created_date desc ");
	
}
else
{
	$startdate = date('Y-m-d');
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
        <a href="#" class="current">Employee Tracking Record</a> 
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
            <h5>Employee Tracking Record</h5>
			<!--<a href="add-request-job.php" style="float:right; margin:3px;" class="btn btn-info">Add Job Request</a>-->
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table"><!-- class="table table-bordered data-table"-->
              <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Employee Name</th>
                    
                    <? for($i=0;$i<$location_no;$i++){ ?>

                       <th>Location <?=$i+1;?> </th>

				    <? } ?>
                </tr>
              </thead>
              <tbody>
			  	<?php 
					for($emp=0;$emp<count($get_emp_data);$emp++) { 
					
					$emp_track_data = select_query("select job_location,created_datetime from $employee_track.emp_today_tracking where 
					phone_no='".$get_emp_data[$emp]['mobile_no']."' and emp_id='".$get_emp_data[$emp]['id']."' and is_active='1'
					and login_id='".$_SESSION['user_id']."' and Date_of_journey='".$startdate."'  order by id desc limit ".$location_no);
					
					//echo "<pre>";print_r($emp_track_data);die;
									
				?>
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_emp_data[$emp]['emp_name']; ?></td>                  
                  <? for($i=0;$i<$location_no;$i++){ 
				  	 
					 if($emp_track_data[$i]['created_datetime']!=''){$loc_in_time = ' - '.date("d/m/Y h:i A",strtotime($emp_track_data[$i]['created_datetime']));} else {$loc_in_time = '';}
				  ?>

                  <td><?=$emp_track_data[$i]['job_location'].''.$loc_in_time;?> </td>

				  <? } ?>
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
