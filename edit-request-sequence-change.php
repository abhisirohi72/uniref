<?php 
include('inc/header.php');

$user_id = $_SESSION['user_id'];

$req_id = base64_decode($_REQUEST['id']);
$emp_id = base64_decode($_REQUEST['emp_id']);

//$get_emp_recd = select_query("SELECT * FROM $employee_track.request WHERE id='".$req_id."' and login_id='".$_SESSION['user_id']."' ");
//echo "<pre>";print_r($get_emp_recd);die;

if (isset($_POST['save_people'])) {
	
	//echo "<pre>";print_r($_POST);die;
	
	$req_id = $_POST['req_id'];
	$changeType = $_POST['changeType'];
	$changeNumber = $_POST['changeNumber'];
	
	$check_data = select_query("SELECT * FROM $employee_track.request where id=".$req_id);
	$emp_id = $check_data[0]['emp_id'];
	$sequence_no  = $check_data[0]['sequence_no'];
	
	
	if($changeType == 'exchange') {
								
		$exchange_job = array('sequence_no' => $changeNumber);
		$condition = array('id' => $req_id, 'is_active' => 1);            
		$result = update_query($employee_track.'.request', $exchange_job, $condition);
		
		$sql2 = select_query("UPDATE $employee_track.request SET `sequence_no`=".$sequence_no." WHERE `sequence_no`=".$changeNumber." 
		AND `emp_id`=".$emp_id." AND `id`<> ". $req_id." AND is_active=1 AND sequence_date='".date("Y-m-d")."' AND 
		login_id='".$_SESSION['user_id']."'");
				
		if ($result) {
			echo "<script>window.location.href='view-request-sequence.php'</script>";
			$_SESSION['success_msg'] = 'set';
		}
		else {
			$_SESSION['unsuccess_msg'] = 'set';
		}
	}
	
	if($changeType == 'sequence') {
								
		$sql = "select id,sequence_no from $employee_track.request where emp_id = ".$emp_id." AND id <> ".$req_id." 
		AND `sequence_no` >= ".$changeNumber." AND is_active=1 AND job_type=2 AND sequence_date='".date("Y-m-d")."' AND 
		login_id='".$_SESSION['user_id']."'";

		$arraySelection = select_query($sql);
		
		if (count($arraySelection) > 0)

		$sql2 = "UPDATE $employee_track.request SET `sequence_no` = (CASE id ";

		foreach ($arraySelection as $array => $newarray) {						
				$sql2 .= " WHEN ";
				$sql2 .= $newarray['id'];
				$sql2 .= " THEN ";
				$sql2 .= intval($newarray['sequence_no']+1);
				$arrTemp[] = $newarray['id'];
		}
		
		$sql2 .= " END) WHERE id IN( ";
		
		foreach ($arrTemp as $k => $element) {
			if ($k==count($arrTemp)-1)
				$sql2 .= $element;
			else
				$sql2 .= $element.", ";
		}
		
		$sql2 .= " ) AND sequence_date='".date("Y-m-d")."' AND 
		login_id='".$_SESSION['user_id']."' AND emp_id = ".$emp_id;
		
		
		$result = select_query($sql2);
		
		$sequence_job = array('sequence_no' => $changeNumber);
		$condition2 = array('id' => $req_id, 'is_active' => 1);  
		$sequenceData = update_query($employee_track.'.request', $sequence_job, $condition2);
		
		//echo "<br/>";echo "<br/>";
		//echo $sql2;die;
		
		if ($sequenceData) {
			echo "<script>window.location.href='view-request-sequence.php'</script>";
			$_SESSION['success_msg'] = 'set';
		}
		else {
			$_SESSION['unsuccess_msg'] = 'set';
		}
		
	}

}
?>

<link rel="stylesheet" href="<? echo __SITE_URL?>/css/bootstrap-select.css">

 
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> 
    	<a href="view-request-job.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
        <a href="#" class="current">Edit Job Sequence</a> 
    </div>
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Edit Job Sequence</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="form1" action="" method="post" class="form-horizontal" autocomplete="off" >
              <div class="alert alert-error error_display" style="display:none">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span id="print_err"></span>
			  </div>
			  
			  <?php if(isset($_SESSION['success_msg'])) {  ?>
				<div class="alert alert-success success_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Success!</strong><span> Succesfully Changed.</span>
			  </div>
               <?php } else if(isset($_SESSION['unsuccess_msg'])) {  ?>
				<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> Number Not Changed. </span>
			  </div>
			  <?php } 
			  unset($_SESSION['success_msg']);
			  ?>
			  	<input type="hidden" name="req_id" id="req_id" value="<?php echo $req_id;?>"/>
				
              
               <div class="control-group">
                <label class="control-label">Change With:</label>
                <div class="controls">
                  <input type="radio" name="changeType" id="changeType" value="exchange">Exchange
 		 		  <input type="radio" name="changeType" id="changeType2" value="sequence">Sequence
                  <span id="branch_error"></span> </div>
              </div>
 			                			
			  <div class="control-group">
                <label class="control-label">Change Number:</label>
                <div class="controls">
                	<div class="col-sm-3">
                  		<select name="changeNumber" id="changeNumber" class="selectpicker pull-right" data-live-search="true" title="Select Change Number">
                            <!--<option value="">-- Select One --</option>-->
                            <?php
                            $sequence_query = "select sequence_no from $employee_track.request where login_id='".$_SESSION['user_id']."' and ((current_record=0 and job_type=2) or (current_record=2)) and is_active=1 and sequence_date='".date("Y-m-d")."' and emp_id='".$emp_id."' group by sequence_no order by sequence_no ";
                            $seq_data = select_query($sequence_query);
                            
                            for($sp=0;$sp<count($seq_data);$sp++){
                            ?>          
                            <option value="<?=$seq_data[$sp]['sequence_no'];?>"><?=$seq_data[$sp]['sequence_no'];?></option>
                            <?php  }  ?>
                      </select>
                  <span id="branch_error"></span> </div>
                  </div>
              </div>
              
              <div class="form-actions">
                <button type="submit" class="btn-harish btn-info-harish save_step_1" name="save_people">Save</button>
                <a  class="btn-harish btn-info-harish" href="view-request-sequence.php" style="color: #fff;">Cancel</a>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<!--<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/libs/jquery/jquery.min.js"></script> -->
<script src="<? echo __SITE_URL?>/js/bootstrap-select.js"></script>   
<script>
	  
$( document ).ready(function(){

////////////////////////// Validation ////////////////////////
	
    $('.save_step_1').click(function(e) {
	
		var sequenceNo   = $("#changeNumber").val();
		var radioValue   = $("input[id='changeType']").prop('checked');
		var radioValue2  = $("input[id='changeType2']").prop('checked');
  		
		if(radioValue == false && radioValue2 == false)
		{
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select One In Exchange/Sequence.");
			return false;
		}
		else if(sequenceNo == '')
		{
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select Value.");
			return false;
		}
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}	   
	   
    });
	
////////////////////////// Validation ////////////////////////
	
});

</script>

<?php include('inc/footer.php');?>
