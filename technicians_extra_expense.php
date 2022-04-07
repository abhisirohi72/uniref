<?php 
session_start();
include('inc/header.php'); 

$id_roles = $_SESSION['id_roles'];

if(isset($_POST["submit"]))
{
	//echo "<pre>";print_r($_POST);die;
	
	$startdate = $_POST['dateStart'];
    $Enddate = $_POST['dateEnd'];
	
	if($startdate!='' && $Enddate!='')
	{
		$WhereQuery="WHERE is_active='1' and (req_date BETWEEN '".$startdate."' AND '".$Enddate."') ";
	} 
	else if($startdate!='' && $Enddate=='')
	{
		$WhereQuery="WHERE is_active='1' and (req_date BETWEEN '".$startdate."' AND '".$startdate."') ";
	}
	else if($startdate=='' && $Enddate!='')
	{
		$WhereQuery="WHERE is_active='1' and (req_date BETWEEN '".$Enddate."' AND '".$Enddate."') ";
	} 
	else 
	{
		$WhereQuery = "WHERE is_active='1' order by req_date desc limit 50 ";
	}
	
}
else
{			
	$WhereQuery = "WHERE is_active='1' order by req_date desc limit 50 ";
}

//echo "SELECT * FROM $db_name.extra_expense_claim_tbl ".$WhereQuery;

$get_tech_extra_exp = select_query("SELECT * FROM $db_name.extra_expense_claim_tbl ".$WhereQuery);
	
//echo "<pre>";print_r($get_tech_extra_exp);die;
?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view_technicians.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">All Technicians Extra Expense</a> </div>
    <div class="container-fluid">
    	<div class="row-fluid">
        
            <form name="myformlisting" method="post" action="" autocomplete="off">
        
            <div class="col-sm-2">
                <div class="controls input-append date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                  <input class="form-control equalsize date-picker selectsize" name="dateStart" id="dateStart" size="16" type="text" value="<?=$_POST['dateStart'];?>" placeholder="Start Date">
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
            </div>
            <div class="col-sm-1"></div>
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
			<strong class="error_submission">Success!</strong><span> Succesfully deleted.</span>
		  </div>
		  <?php } 
		  unset($_SESSION['success_msg']);
		  ?>
		<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>All Technicians Extra Expense</h5>
			<a href="download_excel.php?action=technicians_extra_expense" style="float:right; margin:3px;" class="btn-harish btn-info-harish">Export Excel</a>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg" id="filtertable">
              <thead>
                <tr>
                  <th>SNo</th>
                  <th>Name</th>
                  <th>Phone NO</th>
                  <th>Start Location</th>
                  <th>End Location</th>
                  <th>Bill Amount</th>
                  <th>Request Date</th>
                  <th>Approve/Disapprove</th>
				  <?php if($_SESSION['id_roles']=="1"){?>
                  <th>Actions</th>
				  <?php }?>
                </tr>
              </thead>
              <tbody>
			  	<?php 
				
				for($emp=0;$emp<count($get_tech_extra_exp);$emp++) { 
					
					$get_people = select_query("SELECT * FROM $db_name.technicians_login_details WHERE id='".$get_tech_extra_exp[$emp]['tech_id']."' and 
					loginid='".$_SESSION['user_id']."' order by id  ");
					
				?>
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_people[0]['emp_name']; ?></td>
                  <td><?php echo $get_tech_extra_exp[$emp]['phone_no']; ?></td>
                  <td><?php echo $get_tech_extra_exp[$emp]['start_location']; ?></td>
                  <td><?php echo $get_tech_extra_exp[$emp]['end_location']; ?></td>
                  <td><?php echo $get_tech_extra_exp[$emp]['bill_amount']; ?></td>
                  <td><?php echo $get_tech_extra_exp[$emp]['req_date']; ?></td>
                  
                  <td><?php if( $get_tech_extra_exp[$emp]['approve_status'] == '1' ) { echo "Approve";} else if( $get_tech_extra_exp[$emp]['approve_status'] == '2' ) { echo "Reject";} else {echo "No Action";} ?></td>  
                                  
					<?php if($_SESSION['id_roles']=="1"){?>
						<td> <?php if($get_tech_extra_exp[$emp]['approve_status']==0){?>
							<a class="btn-harish btn-info-harish" onclick="expanseApprove('<?php echo base64_encode($get_tech_extra_exp[$emp]["id"])?>')">Approve</a> 
							<a class="btn-harish btn-info-harish" onclick="expanseReject('<?php echo base64_encode($get_tech_extra_exp[$emp]["id"])?>')">Reject</a>
							<?php } ?>
						</td>
					<?php }?>	
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

<link href="<?php echo __SITE_URL;?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
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

<script>
/*$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});*/

function expanseApprove(id){

	var txt;
	if (confirm("Are You Sure Do you want to Approve Technicians Leave!")) {
		txt = "You pressed OK!";
		
		window.location.href='close-request-job.php?id='+id+'&action=expanseApprove';
	} else {
		txt = "You pressed Cancel!";
	}
}

function expanseReject(id){

	var txtr;
	if (confirm("Are You Sure Do you want to Approve Technicians Leave!")) {
		txtr = "You pressed OK!";
		
		window.location.href='close-request-job.php?id='+id+'&action=expanseReject';
	} else {
		txtr = "You pressed Cancel!";
	}
}

</script>

</body>
</html>
