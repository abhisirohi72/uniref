<?php
require('conn/configu.php');

// $dbhost = '203.115.101.54';
// $dbuser = 'pintu';
// $dbpass = '123456';
// $db = 'controlroom';
// $conn = mysqli_connect($dbhost, $dbuser, $dbpass,$db);
//$selected = mysqli_select_db($db,$conn) or die("Could not connect to database");

$exception =$_GET['exception'];

$get_exception = mysqli_query($connu,"SELECT sub_category FROM exception_sub_type WHERE exception_type = '".$exception."'  and user_id='".$_SESSION['user_id']."' ");


//$get_exception = mysqli_query("SELECT sub_category FROM exception_sub_type WHERE exception_type = '".$exception."' and status='0' ");


 $data = array( );
//$i=1;

//echo mysqli_num_rows($noti_results)."<br>";
if (mysqli_num_rows($get_exception) > 0) {

	$i=1;
			
  while($row = mysqli_fetch_array($get_exception)) {


  //	echo "<input type='checkbox' name=".'"vehicle"'.$i." value=".Bike">I have a bike

  //	echo '<input type="checkbox" name="vehicle'.$i.'" value="'.$row['sub_category'].'"> '. $row['sub_category'].' Hours <br>';
  	  	echo '<input type="checkbox" name="vehicle[]" value="'.$row['sub_category'].'"> '. $row['sub_category'].' Hours <br>';



  	$i++;
  			//echo "<li>".$row['sub_category']." Hours"."</li>";
  		
  	

		}

}


//print_r($data);
//echo json_encode($data);