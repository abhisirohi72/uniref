<?php 
session_start();
include('inc/header.php'); 

$id_roles = $_SESSION['id_roles'];


function dateDifference($date1, $date2)
{ 

    $days1 = date('d', strtotime($date1));        

    $ts1 = strtotime($date1);        
    $ts2 = strtotime($date2);
    
    $year1 = date('Y', $ts1);
    $year2 = date('Y', $ts2);
    
    $month1 = date('m', $ts1);
    $month2 = date('m', $ts2);
    
    if($days1 > 15)        
    {
        $months = (($year2 - $year1) * 12) + ($month2 - $month1);
    }
    else if($days1 < 16)        
    {
        $months = ((($year2 - $year1) * 12) + ($month2 - $month1))+1;
    }
        
   return $months;

}

$get_customer = select_query("SELECT * FROM $db_name.customer_details WHERE  is_active='1' order by id desc ");
	
//echo "<pre>";print_r($get_customer);die;
?>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 id="mySmallModalLabel" class="modal-title"> Customers Details
          
        </h4>
      </div>
      <div class="modal-body" id="innercontent"> </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view_customer.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">All Customers</a> </div>
    
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
            <h5>All Customers</h5>
             <form name="report" action="customer_data_excel.php" id="customer_job_report" method="post" target="_blank">
                <div class="col-md-7" > </div>
                <div class="col-md-1" > 
                    <input type="hidden" name="login_id" id="login_id" value="<?=$_SESSION['user_id'];?>" />
                    <button type="submit" name="submit" class="btn-harish btn-info-harish" style="float:right" value="CustomerDataExcel"><i class="fa fa-download"></i> Export Excel </button>
                </div>
              </form>
			  <?php //if($_SESSION['id_roles']=="1"){?>
				<a href="add_customer.php" style="float:right; margin:3px;" class="btn-harish btn-info-harish">Add Customers</a>
			  <?php //}?>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table table-responsive-lg" id="filtertable">
              <thead>
                <tr>
                  <th>SNo</th>
                  <th>Name/ID</th>
                  <th>Mobile No</th>
                  <th>Organisation Name</th>
                  <th>No of Service</th>
                  <th>Total Amount</th>
                  <th>Advance Amount</th>
                  <th>Address</th>
                  <th>Date of Installation</th>
                  <th>Handing over date</th>
                  <th>Warranty In Months</th>
                  <th>Next AMC Month</th>
                  <th>Details</th>
				  <?php if($_SESSION['id_roles']=="1"){?>
				  <th>Actions</th>
				  <?php }?>
                </tr>
              </thead>
              <tbody>
			  	<?php 
				$todaydate = date('Y-m-d');
				
				for($emp=0;$emp<count($get_customer);$emp++) { 
				
				if($get_customer[$emp]['date_of_installation'] != '0000-00-00' && $get_customer[$emp]['date_of_installation'] != '')
				{
					$installationDate = date("d F Y",strtotime($get_customer[$emp]['date_of_installation'])); 
				
				} else {
					$installationDate = '';
				}
				
				if($get_customer[$emp]['handover_warranty'] != '0000-00-00' && $get_customer[$emp]['handover_warranty'] != '')
				{
					$handover_warranty = date("d F Y",strtotime($get_customer[$emp]['handover_warranty'])); 
				
				} else {
					$handover_warranty = '';
				}
				
				$no_of_month = 12;
				
				$amc_no_of_service = $get_customer[$emp]['amc_no_of_service'];
				
				$amc_month = $no_of_month/$amc_no_of_service;
				
				if($installationDate != "")
				{
					
					$monthdiff = dateDifference($get_customer[$emp]['date_of_installation'], $todaydate);
					
					if($monthdiff >= 0 && $monthdiff < $amc_month){ $addmonth = $amc_month;}
					else if($monthdiff >= $amc_month && $monthdiff < ($amc_month*2)){ $addmonth = ($amc_month*2);}
					else if($monthdiff >= ($amc_month*2) && $monthdiff < ($amc_month*3)){ $addmonth = ($amc_month*3);}
					else if($monthdiff >= ($amc_month*3) && $monthdiff < ($amc_month*4)){ $addmonth = ($amc_month*4);}
					else if($monthdiff >= ($amc_month*4) && $monthdiff < ($amc_month*5)){ $addmonth = ($amc_month*5);}
					else if($monthdiff >= ($amc_month*5) && $monthdiff < ($amc_month*6)){ $addmonth = ($amc_month*6);}
					
					$effectiveDate = date('Y-m-d', strtotime("+".$addmonth." months", strtotime($get_customer[$emp]['date_of_installation'])));
					
					$nextAMCDate = date('F Y', strtotime("-1 days", strtotime($effectiveDate)));
				}
				?>
                <tr class="gradeX">
                  <td><?php echo $emp+1; ?></td>
                  <td><?php echo $get_customer[$emp]['name'].'/'.$get_customer[$emp]['cust_id']; ?></td>
                  <td><?php echo $get_customer[$emp]['phone_no']; ?></td>
                  <td><?php echo $get_customer[$emp]['company_name']; ?></td>
                  <td><?php echo $get_customer[$emp]['amc_no_of_service']; ?></td>
                  <td><?php echo $get_customer[$emp]['total_purchase_amount']; ?></td>
                  <td><?php echo $get_customer[$emp]['amount_recd_advance']; ?></td>
                  <td><?php echo $get_customer[$emp]['home_address']; ?></td>
                  <td><?php echo $installationDate;?></td> 
                  <td><?php echo $handover_warranty;?></td> 
                  <td><?php echo $get_customer[$emp]['warranty_month'].' Months'; ?></td> 
                  <td><?php echo $nextAMCDate;?></td>                                      
                  
                  <td> <?php if($get_customer[$emp]['is_active']==1){?>
                    <a onclick="Show_info('GetCustomerDetails','<?=$get_customer[$emp]['id'];?>');" class="btn-harish btn-info-harish" data-toggle="modal" data-target=".bs-example-modal-sm">View </a>
                  	<?php } ?>
                  </td>
                  <?php if($_SESSION['id_roles']=="1"){?>
				  <td> <?php if($get_customer[$emp]['is_active']==1){?>
            		<?php if($get_customer[$emp]['model_purchased'] == ""){?>
                    <a class="btn-harish btn-info-harish" href="edit_customer.php?action=editapp&id=<?php echo base64_encode($get_customer[$emp]['id']);?>">Edit</a>
                    <? } else { ?>
                    <a class="btn-harish btn-info-harish" href="edit_customer.php?action=edit&id=<?php echo base64_encode($get_customer[$emp]['id']);?>">Edit</a>
                    <? } ?>
                    <!--</br></br>-->
                   <a class="btn-harish btn-info-harish" onclick="deleteCustomer('<?php echo base64_encode($get_customer[$emp]["id"])?>')">Delete</a>
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

<script>
/*$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});*/

function deleteCustomer(id){

	var txt;
	if (confirm("Are You Sure Do you want to Delete Customer!")) {
		txt = "You pressed OK!";
		
		window.location.href='close-request-job.php?id='+id+'&action=deleteCustomer';
	} else {
		txt = "You pressed Cancel!";
	}
}

function Show_info(action,rid)
{
    $.ajax({
            type:"GET",
            url:"show_view_info.php?action="+action,

            data:"rid="+rid,
            success:function(msg){

            document.getElementById("innercontent").innerHTML = msg;

            }
    });
}

</script>

</body>
</html>
