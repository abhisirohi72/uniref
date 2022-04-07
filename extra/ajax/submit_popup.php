<?php 
//Author - Sanjana

require("../conn/config.php");
require("../function.php");

$hours = $_POST['hours'];
$reason = $_POST['reason'];
$reason_msg = $_POST['reason_msg'];
$tableName = 'popup';

$form_data = array(
    'hours' => $hours,
    'reason' => $reason,
    'specified_reason' => mysql_real_escape_string($reason_msg),
    'date' => date('Y-m-d')
);
dbRowInsert($tableName,$form_data);
//dbRowDelete($tableName,"WHERE id = '2'");
//dbRowUpdate($tableName, );
?>