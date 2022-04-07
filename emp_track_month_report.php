<?php 
include('inc/header.php'); 

class Custom_Array
{
    public static function multipleSearch(array $array, array $pairs)
    {
        $found = array();
        foreach ($array as $aKey => $aVal) {
            $coincidences = 0;
            foreach ($pairs as $pKey => $pVal) {
            
                $new_val = date("Y-m-d",strtotime($aVal[$pKey]));
                //echo "<br>";
                //echo $pVal;
                
                if (array_key_exists($pKey, $aVal) && $new_val == $pVal) {
                
                //if(in_array($aVal[$pKey],$aVal) && array_key_exists($pKey, $aVal) ) {
                //echo "<pre>";echo print_r($aVal);
                
                $coincidences++;
                }
            }
            if ($coincidences == count($pairs)) {
                $found[$aKey] = $aVal;
            }
        }
        
        return $found;
    }    
}

if(isset($_POST["submit"]))
{
	//echo "<pre>";print_r($_POST);die;
	
	$startdate = date('Y-m-d',strtotime($_POST['dateStart']));
	$Enddate = date('Y-m-d',strtotime($_POST['dateEnd']));
	//$location_no = $_POST['location_no'];
	
	$get_emp_data = select_query("SELECT * FROM $employee_track.login_emp_details WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by created_date desc ");
	
}
else
{
	/*$startdate = date('Y-m-01');*/
	$startdate = date("Y-m-d", strtotime('-15 day'));
	$Enddate = date('Y-m-d');
	
	$get_emp_data = select_query("SELECT * FROM $employee_track.login_emp_details WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by created_date desc ");
}

$start_ts = strtotime($startdate);
$end_ts = strtotime($Enddate);

$diff = $end_ts - $start_ts;

$Dayrange=round($diff / 86400)+1;

if($Dayrange>31)
{
    $Dayrange=31;
}

//echo "<pre>";print_r($get_emp_data);die;
?>

<link href="<?php echo __SITE_URL;?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
        <a href="index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
        <a href="#" class="current">Employee Monthly TA Report</a> 
    </div>
    <form name="myformlisting" method="post" action="" autocomplete="off">
    	<div class="col-md-1"></div>
    	<div class="col-md-2 col-lg-2">
          <div class="controls input-append date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input class="form-control date-picker" name="dateStart" id="dateStart" size="16" type="text" value="<?=$startdate;?>" placeholder="From Time" >
            <span class="add-on"><i class="icon-th"></i></span> </div>
        </div>
        	<div class="col-md-2 col-lg-2">
              <div class="controls input-append date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                <input class="form-control date-picker" name="dateEnd" id="dateEnd" size="16" type="text" value="<?=$Enddate;?>" placeholder="To Time">
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
            <h5>Employee Monthly TA Report</h5>
			<!--<a href="add-request-job.php" style="float:right; margin:3px;" class="btn btn-info">Add Job Request</a>-->
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table"><!-- class="table table-bordered data-table"-->
              <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Employee Name</th>
                    
                    <? for($i=0;$i<$Dayrange;$i++){ ?>

                       <th><b><?=date("d-M",strtotime(date("d-m-Y", strtotime($startdate))." +".$i." days"));?></b></th>

				  <? }?>
			  		<th>Total</th>
                </tr>
              </thead>
              <tbody>
			<?php 					
                for($emp=0;$emp<count($get_emp_data);$emp++) { 
					
						$MergeData = array();
						$MergeFinalData = array();
						$counter=0;$i=0;
						  
						$fromDate = date("Y-m-d",strtotime($startdate));
						$toDate = date("Y-m-d",strtotime(date("Y-m-d", strtotime($startdate)) . " +".$Dayrange." days"));
						  
						$begin = strtotime(date('Y-m-d',strtotime($startdate))); // or your date as well
						$end = strtotime(date('Y-m-d',strtotime(date("Y-m-d", strtotime($startdate)) . " +".$Dayrange." days")));
					
						$emp_job_count = select_query("select SUM(TIME_TO_SEC(Total_journey_hour)) as sec, emp_id, Start_time,Start_location, 
							End_time,End_location,Total_KM, DateOf_journey,created_own_job,task_assigned 
							from $employee_track.employee_consolidate where  emp_id='".$get_emp_data[$emp]['id']."' and 
							login_id='".$_SESSION['user_id']."' and DateOf_journey>='".$fromDate."' and 
							DateOf_journey<='".$toDate."'  group by emp_id,DateOf_journey order by Start_time");
					
						//echo "<pre>";print_r($emp_job_count);die;
					
						if(count($emp_job_count)>0)
						{              
							for($rn=0;$rn<count($emp_job_count);$rn++)
							{
								$currentACArray = array(
									 $emp_job_count[$rn]['emp_id'],
									 $emp_job_count[$rn]['sec'],
									 $emp_job_count[$rn]['DateOf_journey'],
									 $emp_job_count[$rn]['Total_KM'],
									 $emp_job_count[$rn]['created_own_job'],
									 $emp_job_count[$rn]['task_assigned'],
								);
					
								array_push($MergeData, $currentACArray);
							}
							
							//echo "<pre>";print_r($MergeData);die;
							 
							$npl=0;
							for ($i=$begin; $i<$end; $i+=86400) 
							{
								$date_arr = date("Y-m-d", $i);
								$result = Custom_Array::multipleSearch($MergeData, array("2"=>$date_arr));
								//echo "<pre>";print_r($result);
								
								if(count($result)>0)
								{
									$finalACArray = array(
										 'emp_id' => $result[$npl][0],
										 'sec' => $result[$npl][1],
										 'DateOf_journey' => $result[$npl][2],
										 'Total_KM' => $result[$npl][3],
										 'created_own_job' => $result[$npl][4],
										 'task_assigned' => $result[$npl][5],
									);
									$npl++;
								} else {
									
									$finalACArray = array(
										 'emp_id' => $get_emp_data[$emp]['id'],
										 'sec' => '',
										 'DateOf_journey' => $date_arr,
										 'Total_KM' => '',
										 'created_own_job' => 0,
										 'task_assigned' => 0,
									);
								}            
								array_push($MergeFinalData, $finalACArray);
							}
							
							//echo "<pre>";print_r($MergeFinalData);die;
							
						}
						else
						{          
							for ($i=$begin; $i<$end; $i+=86400) 
							{
								$date_arr = date("Y-m-d", $i);
									
								$finalACArray = array(
									 'emp_id' => $get_emp_data[$emp]['id'],
									 'sec' => '',
									 'DateOf_journey' => $date_arr,
									 'Total_KM' => '',
									 'created_own_job' => 0,
									 'task_assigned' => 0,
								);
								
								array_push($MergeFinalData, $finalACArray);
							}
							 
							//echo "<pre>";print_r($MergeFinalData);die;
						}
					
					//echo "<pre>";print_r($MergeFinalData);die;				
				?>
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_emp_data[$emp]['emp_name']; ?></td>                  
                   <?
      
						$TotalRunning=0;
						
						for($vl=0;$vl<count($MergeFinalData);$vl++)
						{
							if($MergeFinalData[$vl]['Total_KM'] != '----' && $MergeFinalData[$vl]['Total_KM'] != '')
							{
								$data_running = $MergeFinalData[$vl]['Total_KM']." KM <br/>".($MergeFinalData[$vl]['Total_KM']*2)." Rs <br/> OJ-".$MergeFinalData[$vl]['created_own_job'].", TA-".$MergeFinalData[$vl]['task_assigned'];
								$TotalRunning = $TotalRunning+$MergeFinalData[$vl]['Total_KM'];
								
							} else {
								$data_running = '----';
							}
								   
							  
							?>
				
							 <td><?=$data_running;?></td>
				
				
							<?
				
							}
				
							if($TotalRunning>0)
							{
								$Total_data_running = $TotalRunning." KM <br/>".($TotalRunning*2)." Rs";
							} else {
								$Total_data_running = '---';
							}
						   
							 ?>
                             
                              <td><?=$Total_data_running;?></td>
                    </tr>
                
                
                
						<?
                        unset($MergeData);
                        unset($MergeFinalData);
                    }
        
                ?> 
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
