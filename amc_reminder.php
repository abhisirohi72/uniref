<?php 
session_start();
include('inc/header.php'); 

$id_roles=$_SESSION['id_roles'];

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

$get_Latest_data = select_query("SELECT * FROM $db_name.cust_push_notification WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by to_date desc,create_time desc limit 8");
	
//echo "<pre>";print_r($get_Latest_data);die;

$get_Complaints_data = select_query("SELECT * FROM $db_name.push_notification WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by to_date desc,create_time desc limit 10");
//echo "<pre>";print_r($get_Complaints_data);die;

$get_customer = select_query("SELECT * FROM $db_name.customer_details WHERE loginid='".$_SESSION['user_id']."' and is_active='1' order by id desc ");
//echo "<pre>";print_r($get_customer);die;
?>

<!--tab-->
<style type="text/css">
/* ul {margin: 0;padding: 10px;list-style: circle;font-size: 15px;overflow-y: scroll;}

 ul li{margin-left: 10px;padding-bottom: 10px}*/
</style>
<!--tab-->

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="amc_reminder.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">AMC Reminder</a> </div>

  
    <div class="container" style="width: 100%!important;height: 100%!important;padding: 0 0 0 120px;margin-top: 50px">
        <div class="row-fluid">
            <div class="col-lg-5" style="border: 1px #dee2e6 solid; height: 350px;background: #fff;margin: 10px">
                <h3 style="color: #2f5597">Customer Notification</h3>
                <ul class="list-unstyled" style="height: 240px;">
					<? for($emp=0;$emp<count($get_Latest_data);$emp++) { ?>
                    <li><?php echo $get_Latest_data[$emp]['message']; ?></li>
                    <!--<li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
                    <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
                    <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
    
                    <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>
    
                    <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>-->
                    <? } ?>
				</ul>
            </div>
            
            <!-- Map Tab -->
            <div class="col-lg-5" style="border: 1px #dee2e6 solid; height: 350px;background: #fff;margin: 10px">
                 <iframe src="<? echo __SITE_URL?>/emp_map_view.php" width="100%" height="350" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
            </div>
            <!-- End Map Tab -->
            
            <div class="col-lg-5" style="border: 1px #dee2e6 solid; height: 350px;background: #fff;margin: 10px">
                <h3 style="color: #2f5597">Technician Notification</h3>
                <ul class="list-unstyled" style="height: 240px;">
					<? for($cp=0;$cp<count($get_Complaints_data);$cp++) { ?>
                    <li><?php echo $get_Complaints_data[$cp]['message']; ?></li>
                    <!--<p style="font-size: 16px">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    <p style="font-size: 16px">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                    <p style="font-size: 16px">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>-->
                    <? } ?>
                </ul>
            </div>
            <div class="col-lg-5" style="border: 1px #dee2e6 solid; height: 350px;background: #fff;margin: 10px">
                <h3 style="color: #2f5597">AMC Reminder's for this month</h3>
                	<ul class="list-unstyled" style="height: 240px;">
                    <table class="table table-bordered table-responsive-lg">
                      <thead>
                        <tr>
                          <th>SNo</th>
                          <!--<th>Name/ID</th>
                          <th>Mobile No</th>-->
                          <th>Organisation Name</th>
                          <th>Model Purchased</th>
                          <th>Serial No</th>
                          <!--<th>Date of Installation</th>-->
                          <th>Next AMC Month</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $todaydate = date('Y-m-d');
                        
                        for($amc=0;$amc<count($get_customer);$amc++) { 
                        
                        if($get_customer[$amc]['date_of_installation'] != '0000-00-00' && $get_customer[$amc]['date_of_installation'] != '')
                        {
                            $installationDate = date("d/m/Y",strtotime($get_customer[$amc]['date_of_installation'])); 
                        
                        } else {
                            $installationDate = '';
                        }
                        
                        $no_of_month = 12;
                        
                        $amc_no_of_service = $get_customer[$amc]['amc_no_of_service'];
                        
                        $amc_month = $no_of_month/$amc_no_of_service;
                        
                        if($installationDate != "")
                        {
                            
                            $monthdiff = dateDifference($get_customer[$amc]['date_of_installation'], $todaydate);
                            
                            if($monthdiff >= 0 && $monthdiff < $amc_month){ $addmonth = $amc_month;}
                            else if($monthdiff >= $amc_month && $monthdiff < ($amc_month*2)){ $addmonth = ($amc_month*2);}
                            else if($monthdiff >= ($amc_month*2) && $monthdiff < ($amc_month*3)){ $addmonth = ($amc_month*3);}
                            else if($monthdiff >= ($amc_month*3) && $monthdiff < ($amc_month*4)){ $addmonth = ($amc_month*4);}
                            else if($monthdiff >= ($amc_month*4) && $monthdiff < ($amc_month*5)){ $addmonth = ($amc_month*5);}
                            else if($monthdiff >= ($amc_month*5) && $monthdiff < ($amc_month*6)){ $addmonth = ($amc_month*6);}
                            
                            $effectiveDate = date('Y-m-d', strtotime("+".$addmonth." months", strtotime($get_customer[$amc]['date_of_installation'])));
                            
                            $nextAMCDate = date('F Y', strtotime("-1 days", strtotime($effectiveDate)));
                        }
                        ?>
                        <tr class="gradeX">
                          <td><?php echo $amc+1; ?></td>
                          <!--<td><?php echo $get_customer[$amc]['name'].'/'.$get_customer[$amc]['cust_id']; ?></td>
                          <td><?php echo $get_customer[$amc]['phone_no']; ?></td>-->
                          <td><?php echo $get_customer[$amc]['company_name']; ?></td>
                          <td><?php echo $get_customer[$amc]['model_purchased']; ?></td>
                          <td><?php echo $get_customer[$amc]['serial_no']; ?></td>
                          <!--<td><?php echo $installationDate;?></td> -->
                          <td><?php echo $nextAMCDate;?></td>                                      
                        </tr>
                        <?php } ?>         
                      </tbody>
                    </table>
					</ul>
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
</script>


<script type="text/javascript">
        $(document).ready(function(){
            
            var multisidetabs=(function(){
                 var opt,parentid,
              vars={
                listsub:'.list-sub',
                showclass:'mg-show'
              },
              test=function(){
                console.log(parentid);
              },
              events = function(){
                $(parentid).find('a').on('click',function(ev){
                  ev.preventDefault();
                  var atag = $(this), childsub = atag.next(vars.listsub);
                  //console.log(atag.text());
                  if(childsub && opt.multipletab == true){
                    if(childsub.hasClass(vars.showclass)){
                      childsub.removeClass(vars.showclass).slideUp(500);
                    }else{
                      childsub.addClass(vars.showclass).slideDown(500);
                    }
                  }
                  if(childsub && opt.multipletab == false){
                   childsub.siblings(vars.listsub).removeClass(vars.showclass).slideUp(500);
                   if(childsub.hasClass(vars.showclass)){
                     childsub.removeClass(vars.showclass).slideUp(500);
                   }else{
                     childsub.addClass(vars.showclass).slideDown(500);
                   }
                  }
                });
              },
              init=function(options){//initials
                if(options){
                  opt = options;
                  parentid = '#'+options.id;
                  //test();
                  events();
                }else{ alert('no options'); }
              }
              
                return {init:init};
            })();
            
            multisidetabs.init({
                "id":"mg-multisidetabs",
              "multipletab":false
            });
            
          })
    </script>

</body>
</html>
