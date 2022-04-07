<?php 

include('inc/header.php');


$dbhost = '203.115.101.54';
$dbuser = 'pintu';
$dbpass = '123456';
$db = 'controlroom';

$cown = mysqli_connect($dbhost, $dbuser, $dbpass,$db);



//$user_id = $_SESSION['user_id'];
$_SESSION['user_id']=79393;

if(isset($_POST["upload_export"]))
{

        $filename="csv/".$_FILES["file"]["name"] ;
        $path_info = pathinfo($filename);

        if($path_info['extension']=="csv" || $path_info['extension']=="CSV")
        {
            move_uploaded_file($_FILES['file']['tmp_name'],"$filename");
            $handle = fopen("$filename", "r");

            $Error=false;
            $msgshow = false;
            $count=0;

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
            {

                if(($count == 0 && $data[0] == "") || ($count == 0 && $data[1] == "") || ($count == 0 && $data[2] == "") || ($count == 0 && $data[3] == "") || ($count == 0 && $data[4] == ""))
                {
                    $Error=true;
                    $msg ="File Field Not Complete. Please Check Your Excel File.<br/>";
                }


                //if($count>0 && $count<=1 && $Error==false)
        if($count>0 && $Error==false)
                {
                    
                        if($data[0]!="")
                        {

                            $category = $data[0];
                            $vehicle_no = $data[1];
                            $vehicle_type = $data[2];
                            $vehicle_make = $data[3];
                            $vehicle_model = $data[4];
                            
                            
              

             $sql = mysqli_query($cown,"INSERT INTO categories SET category = '".$category."', vehicle_no = '".$vehicle_no."', vehicle_type = '".$vehicle_type."', vehicle_make = '".$vehicle_make."',  vehicle_model = '".$vehicle_model."', user_id = '".$user_id."', status = '1', date = '".$current_date."'");
                            
                            
                            //echo $count;
                            //die();
                            // $Vehicle_details = db__select("select services.id as id,gps_latitude,gps_longitude,veh_Reg from latest_telemetry left join services on latest_telemetry.sys_Service_id=services.id  where veh_reg='".$vehicle."' ", $condition);
                            
                            
                            //     $importQuery = "INSERT INTO matrix.telemetry_temperature (sys_service_id, `sys_proc_time`, `sys_proc_host`, `gps_orientation`, `gps_date`, `gps_time`, `gps_latitude`, `gps_longitude`,tel_temperature,`gps_speed`) VALUES ('".$Vehicle_details[0]['id']."', now(),'priyamcoldex',0.0,'".$new_date."','".$data[2]."','".$Vehicle_details[0]['gps_latitude']."','".$Vehicle_details[0]['gps_longitude']."','".$data[3]."','".$data[4]."')";
               //echo "<br/>";

               

                            //$Excute_trip = $mysql->query($importQuery);                                                            
                           // $Excute_trip = db__select_staging($importQuery, $condition);
                                                
                        }
                        else
                        {
                            //$Error=true;
                            $notexist .= $vehicle.',';
                            
                            $msgshow = true;
                            $msg = $notexist." Vehicle number are Not Exist.";
                        }


                }


                $count++;
    
            }


        if($Error==false && $msgshow == false)
        {
            $msg="Sheet file imported successfully";
        }

    }
    else
    {
        $Error=true;
        $msg .="Oops! your uploading file is not csv file. Please check.<br/>";
    }


}










?>
<div id="content">
  <div id="content-header">
    
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Add New Category</h5>
          </div>
          <div class="widget-content nopadding">
            <form enctype="multipart/form-data" method="post" role="form" class="form-horizontal">
              <div class="alert alert-error error_display" style="display:none">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span id="print_err"></span>
			  </div>
			  
			  <?php if(isset($_SESSION['success_msg'])) {  
				 echo '<div class="alert alert-success success_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Success!</strong><span> Succesfully added. Click <a href="view-category.php">here</a> to View</span>
			  </div>' ;



			   } 
			  unset($_SESSION['success_msg']);


			  ?>





        
<!-- <div class="form-group">
<label for="exampleInputFile">File Upload</label>
<input type="file" name="file" id="file" size="150">
<p class="help-block">Only Excel/CSV File Import.</p>
</div>
<button type="submit" class="btn btn-default" name="upload_export" value="submit">Upload</button> -->






            <div class="control-group">
                <label class="control-label">File Upload:</label>
                <div class="controls">
                  <input type="file" name="file" id="file" >
                  <!--<input type="text" name="vehicle_no" id="vehicle_no" class="mandatory" placeholder="Vehicle No *" />-->
                  <p class="help-block">Only CSV File Import.</p>
                  <span id="branch_error"></span> </div>
              </div>

			  
				      


              

              


              

              



 
              <div class="form-actions">
                <button type="submit" class="btn btn-success save_step_1" name="upload_export">Upload</button>
               <button class="btn btn-danger"><a href="view-category.php" style="color: #fff;">Cancel</a></button><a  class="btn btn-danger" href="csv/data.csv" target="_blank" style="color:yellow;margin-left:4px;font-weight: bold;">Download Format</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<script>
	  
$( document ).ready(function(){


////////////////////////// Validation ////////////////////////
	
    $('.save_step_1').click(function(e) {
	
		var branch_name = $("#category_name").val();
		
		if( branch_name == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please enter your category name.");
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
