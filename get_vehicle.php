<?php

require('conn/configu.php');


// $dbhost = '203.115.101.54';
// $dbuser = 'pintu';
// $dbpass = '123456';
// $db = 'controlroom';

// $conn = mysqli_connect($dbhost, $dbuser, $dbpass,$db);
// // $conn = mysql_connect($dbhost, $dbuser, $dbpass);

// // $selected = mysql_select_db($db,$conn) or die("Could not connect to database");

$vehcat = $_GET['vehcat'];

//echo "<script>alert(".$name.");</script>";

//die();

if($vehcat=='All'){

$get_people = mysqli_query($connu,"SELECT * FROM categories WHERE  user_id='".$_SESSION['user_id']."' and status='1' "); 


} else {


$get_people = mysqli_query($connu,"SELECT * FROM categories WHERE  category='".$vehcat."'  and user_id='".$_SESSION['user_id']."' and status='1' "); 

}


// $ab=mysqli_num_rows($get_people);





// $msg= '<table border="0" style="width:50%;">
// 			<tr><td>All</td>
// 			<td><input type="checkbox" name="all_check" id="all_check" onchange="CheckUncheck('.$ab.');" style="width=20px;"/></td></tr><tr>';
	
// 	while($fetch_vehicle = mysqli_fetch_assoc($get_people))
// 	//for($veh=0;$veh<count($get_people);$veh++)
// 	{
// 		$veh=1;
// 		if($veh%3==0) {
// 			$msg .="</tr><tr>";
// 		}
// 		$msg .='<td>'.$fetch_vehicle['vehicle_no'].'-'.$fetch_vehicle['vehicle_type'].'</td><td><input type="checkbox" name="id_"'.$veh.' id='.$veh.' value='.$fetch_vehicle['vehicle_no'].' style="width=20px;"/></td>' ;

// 		$veh++;
// 	}
	
// 	$msg .="</tr></table>";
	
	//echo $msg;


?>
	

	<div class="widget-box">
          
          <div class="widget-content nopadding">



            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th><input type="checkbox" onClick="selectall(this)"/>Select All<br/></th>
                  <th>Vehicle No</th>
                  <th>Vehicle Type</th>
                  <th>Vehicle Make</th>
                  <th>Vehicle Model</th>
                  
                </tr>
              </thead>
              <tbody>
			  	<?php while($fetch_category = mysqli_fetch_assoc($get_people)) { ?>
                <tr class="gradeX">
                  <td><input type="checkbox" name="check[]" id="chek" value="<?php echo $fetch_category['vehicle_no']; ?>" /></td>
                  <td><?php echo $fetch_category['vehicle_no']; ?></td>
                  <td><?php echo $fetch_category['vehicle_type']; ?></td>
                  <td><?php echo $fetch_category['vehicle_make']; ?></td>
                  <td><?php echo $fetch_category['vehicle_model']; ?></td>
                  
                </tr>
                <?php } ?>         
              </tbody>
            </table>





            
          </div>
        </div>