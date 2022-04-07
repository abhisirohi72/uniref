<?php
 
session_start();
$dbhost = '203.115.101.54';
$dbuser = 'visiontek11000';
$dbpass = '123456';
$db = 'employee_track';


$conn = @mysql_connect($dbhost, $dbuser, $dbpass);
echo $conn;
$selected = @mysql_select_db($db,$conn) or die("Could not select examples");


$get= @mysql_query("SELECT * FROM device LIMIT 5");
while($gets = @mysql_fetch_assoc($get)){
echo $gets['device_id'];
}  
  
?>