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
		$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and is_active='1' and (from_date BETWEEN '".$startdate."' AND '".$Enddate."') 
		and (to_date BETWEEN '".$startdate."' AND '".$Enddate."') ";
	} 
	else if($startdate!='' && $Enddate=='')
	{
		$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and is_active='1' and (from_date BETWEEN '".$startdate."' AND '".$startdate."') ";
	}
	else if($startdate=='' && $Enddate!='')
	{
		$WhereQuery="WHERE loginid='".$_SESSION['user_id']."' and is_active='1' and (to_date BETWEEN '".$Enddate."' AND '".$Enddate."') ";
	} 
	else 
	{
		$WhereQuery = "WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by to_date desc,create_time desc limit 50 ";
	}
	
}
else
{			
	$WhereQuery = "WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by to_date desc,create_time desc limit 50 ";
}

//echo "SELECT * FROM $db_name.cust_push_notification ".$WhereQuery;

$get_tech_leave_data = select_query("SELECT * FROM $db_name.cust_push_notification ".$WhereQuery);
	
//echo "<pre>";print_r($get_tech_leave_data);die;
?>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view_technicians.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">All Customer Notification</a> </div>
    
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
            <h5>All Customer Notification</h5>
			<a href="add_cust_notification.php" style="float:right; margin:3px;" class="btn-harish btn-info-harish">Add Notification</a>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg">
              <thead>
                <tr>
                  <th>SNo</th>
                  <th>Send To</th>
                  <th>Notification Message</th>
                  <th>From</th>
                  <th>To</th>
                </tr>
              </thead>
              <tbody>
			  	<?php 
						for($emp=0;$emp<count($get_tech_leave_data);$emp++) {
							
						if($get_tech_leave_data[$emp]['person_id']!= '' && $get_tech_leave_data[$emp]['person_id']!='All')
						{	
							$get_cust_data = select_query("SELECT * FROM $db_name.customer_details WHERE cust_id='".$get_tech_leave_data[$emp]['person_id']."' ");
							$cust_name = $get_cust_data[0]['name'];
						} 
						else 
						{
							$cust_name = $get_tech_leave_data[$emp]['person_id'];
						}
				?>
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $cust_name; ?></td>
                  <td><?php echo $get_tech_leave_data[$emp]['message']; ?></td>
                  <td><?php echo $get_tech_leave_data[$emp]['from_date']; ?></td>
                  <td><?php echo $get_tech_leave_data[$emp]['to_date']; ?></td>
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

<script>
/*$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});*/
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

</body>
</html>
