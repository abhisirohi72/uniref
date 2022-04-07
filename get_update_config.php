<?php 

session_start();



$dbhost = '203.115.101.54';
$dbuser = 'pintu';
$dbpass = '123456';
$db = 'controlroom';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$db);
//$selected = mysql_select_db($db,$conn) or die("Could not connect to database");



$_SESSION['user_id'] = '1';
$_SESSION['group_id'] = '7646';
$_SESSION['loggedin_user_id'] = '1';
$current_date = date('Y-m-d');
date_default_timezone_set('Asia/Kolkata');


?>





