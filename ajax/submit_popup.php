<?php 
//Author - Sanjana
error_reporting(0);
require("../conn/config.php");
require("../function.php");



if ( ( isset($_POST['resolved']) ) && ( $_POST['resolved'] == '1' ) ) { 

$contactIdVal = $_POST['contactIdVal'];
$hourPopup = $_POST['hourPopup'];
$veh_no = $_POST['veh_no'];
$sys_service_id = $_POST['sys_service_id'];
$sys_group_id = $_POST['sys_group_id'];
$location = $_POST['location'];
$submit_type = $_POST['submit_type'];
$insert_time = $_POST['insert_time'];
$update_time = $_POST['update_time'];
$distance_from = $_POST['distance_from'];
$comment_by = $_POST['comment_by'];
$is_active = $_POST['is_active'];
$notificationId = $_POST['notificationId'];

$get_popup = mysql_query("SELECT * FROM popup WHERE contact_id = '".$contactIdVal."' ");

$tableName = 'popup';
$historyTableName = 'idle_notification_history';

$notification_data = mysql_query("UPDATE idle_notification SET is_active = '0' WHERE id = '".$notificationId."'");

$form_data = array(
	'contact_id' => $contactIdVal,
	'prev_hour' => $hourPopup,
	'resolved' => $_POST['resolved'],
    'date' => date('Y-m-d')
);


$history = array(
    'veh_no' => $veh_no,
    'sys_service_id' => $sys_service_id,
	'sys_group_id' => $sys_group_id,
	'idle_hr' => $hourPopup,
	'location' => $location,
    'comment' => mysql_real_escape_string($reason_msg),
	'submit_type' => $submit_type,
	'insert_time' => $insert_time,
    'update_time' => $update_time,
	'distance_from' => $distance_from,
	'comment_by' => $comment_by,
	'is_active' => $is_active	
);
//print_r($history);
if(mysql_num_rows($get_popup)>0) { 

	$update = mysql_query("UPDATE popup SET prev_hour = '".$hourPopup."', resolved = '".$_POST['resolved']."', date = '".date('Y-m-d')."' WHERE contact_id = '".$contactIdVal."'");
	
}
else {
	//echo "INSERT INTO popup SET prev_hour = '".$hourPopup."', resolved = '".$_POST['resolved']."', date = '".date('Y-m-d')."', contact_id = '".$contactIdVal."'";
	//die;

	$update = mysql_query("INSERT INTO popup SET prev_hour = '".$hourPopup."', resolved = '".$_POST['resolved']."', date = '".date('Y-m-d')."', contact_id = '".$contactIdVal."'");
	//dbRowInsert($tableName,$form_data);
}
dbRowInsert($historyTableName,$history);
echo "true";

}

else {
 
$hours = $_POST['hours'];
$reason = $_POST['reason'];
$reason_msg = $_POST['reason_msg'];
$contactIdVal = $_POST['contactIdVal'];
$hourPopup = $_POST['hourPopup'];
$veh_no = $_POST['veh_no'];
$sys_service_id = $_POST['sys_service_id'];
$sys_group_id = $_POST['sys_group_id'];
$location = $_POST['location'];
$submit_type = $_POST['submit_type'];
$insert_time = $_POST['insert_time'];
$update_time = $_POST['update_time'];
$distance_from = $_POST['distance_from'];
$comment_by = $_POST['comment_by'];
$is_active = $_POST['is_active'];

$get_popup = mysql_query("SELECT * FROM popup WHERE contact_id = '".$contactIdVal."' ");

$tableName = 'popup';
$historyTableName = 'idle_notification_history';
$form_data = array(
    'hours' => $hours,
    'reason' => $reason,
    'specified_reason' => mysql_real_escape_string($reason_msg),
	'contact_id' => $contactIdVal,
	'prev_hour' => $hourPopup,
    'date' => date('Y-m-d')
);


$history = array(
    'veh_no' => $veh_no,
    'sys_service_id' => $sys_service_id,
	'sys_group_id' => $sys_group_id,
	'idle_hr' => $hourPopup,
	'location' => $location,
    'comment' => mysql_real_escape_string($reason_msg),
	'submit_type' => $submit_type,
	'insert_time' => $insert_time,
    'update_time' => $update_time,
	'distance_from' => $distance_from,
	'comment_by' => $comment_by,
	'is_active' => $is_active	
);
//print_r($history);
if(mysql_num_rows($get_popup)>0) { 

	$update = mysql_query("UPDATE popup SET hours = '".$hours."', reason = '".$reason."',  specified_reason = '".mysql_real_escape_string($reason_msg)."',  prev_hour = '".$hourPopup."', date = '".date('Y-m-d')."' WHERE contact_id = '".$contactIdVal."'");
	
}
else {

	dbRowInsert($tableName,$form_data);
	
}
dbRowInsert($historyTableName,$history);
echo "true";

}
//dbRowDelete($tableName,"WHERE id = '2'");
//dbRowUpdate($tableName, );
?>