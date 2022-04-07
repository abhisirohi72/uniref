<?php
ob_start();
ini_set('max_execution_time', 50000);
include("C:/xampp/htdocs/employee-Track/config.php");

$data = select_query("SELECT * FROM $employee_track.contact_us_tbl order by id desc"); 
?>
<table border="1" cellpadding="5" cellspacing="5">
	<tr>
    	<td>Sr No</td>
        <td>Req Date</td>
        <td>Company Name</td>
        <td>Contact Person</td>
        <td>Contact Number</td>
        <td>Email Id</td>
        <td>Tracker Number</td>
        <td>Tracker Name</td>
    </tr>
<?
	for($i=0;$i<count($data);$i++){
?>    
   <tr>
    	<td><?=$i+1;?></td>
        <td><?=$data[$i]['current_time'];?></td>
        <td><?=$data[$i]['company_name'];?></td>
        <td><?=$data[$i]['contact_person'];?></td>
        <td><?=$data[$i]['contact_no'];?></td>
        <td><?=$data[$i]['email_id'];?></td>
        <td><?=$data[$i]['tracker_no'];?></td>
        <td><?=$data[$i]['tracker_name'];?></td>
    </tr> 
    
<?  } ?>   
</table>