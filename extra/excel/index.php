<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$db = 'controlroom';

$connew = mysql_connect($dbhost,$dbuser,$dbpass);
$dbnew = mysql_select_db($db,$connew);

$currentdate  = date('Y-m-d',(strtotime(date('Y-m-d'))));

if(isset($_POST["submit"]))
{

	$filename="uploads/".$_FILES["file"]["name"] ;
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
				
							$trip_location = trim($data[0]);
							$trip_number = $data[1];
							$source_destination = $data[2];
							$dispatch_date = $data[3];
							$journey_hour = $data[4];
							$driver_name = $data[5];
							$driver_number = $data[6];	   
						   $query = "insert into trip(trip_location,trip_number,source_destination,dispatch_date,journey_hour,driver_name,driver_number,date) values('".$trip_location."','".$trip_number."', '".$source_destination."', '".$dispatch_date."', '".$journey_hour."', '".$driver_name."', '".$driver_number."', '".$currentdate."' )";
						   
						   mysql_query($query);
							             
	
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
        $msg ="Oops! your uploading file is not csv file. Please check.<br/>";
    }


}

?>


            <form method="post" enctype="multipart/form-data">

              

                <input type="file" name="file" class="btn btn-default btn-file" style="width: 100%; text-align: left;" />

                <input value="Submit" name="submit" type="submit">
              
            </form>
        

       
        <?php if($msg != ""){?>
   			<?php if(isset($msg)){ echo $msg; } ?> 
        <?php } ?>