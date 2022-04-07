<?php 
session_start();
error_reporting(0);
require("config.php");
 

echo "Loading.........";

function array_msort($array, $cols)
{
	$colarr = array();
	foreach ($cols as $col => $order) {
		$colarr[$col] = array();
		foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
	}

	$eval = 'array_multisort(';
	foreach ($cols as $col => $order) {
		$eval .= '$colarr[\''.$col.'\'],'.$order.',';
	}

	$eval = substr($eval,0,-1).');';
	eval($eval);
	$ret = array();
	 
	foreach ($colarr as $col => $arr) {
	  foreach ($arr as $k => $v) {
		   $k = substr($k,1);
		   if (!isset($ret[$k])) $ret[$k] = $array[$k];
		   $ret[$k][$col] = $array[$k][$col];
		  }
	}
	
	return $ret;
}
//echo "<pre>";print_r($_GET);die;
if( $_GET['action'] == 'deleteTech' ) {
	
	
	$update_array = array('is_active' => '0');
	
	$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'loginid' => $_SESSION['user_id']);            
	$result = update_query($db_name.'.technicians_login_details', $update_array, $condition);
			 
	if( $result == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view_technicians.php'</script>";
	}
}

if( $_GET['action'] == 'leaveApprove' ) {
	
	
	$update_array = array('is_status' => '1');
	
	$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'loginid' => $_SESSION['user_id']);            
	$result = update_query($db_name.'.leave_request', $update_array, $condition);
			 
	if( $result == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='technicians_leave_request.php'</script>";
	}
}

if( $_GET['action'] == 'leaveReject' ) {
	
	
	$update_array = array('is_status' => '2');
	
	$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'loginid' => $_SESSION['user_id']);            
	$result = update_query($db_name.'.leave_request', $update_array, $condition);
			 
	if( $result == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='technicians_leave_request.php'</script>";
	}
}

if( $_GET['action'] == 'deleteCustomer' ) {
	
	
	$update_array = array('is_active' => '0');
	
	$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'loginid' => $_SESSION['user_id']);            
	$result = update_query($db_name.'.customer_details', $update_array, $condition);
			 
	if( $result == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view_customer.php'</script>";
	}
}

if( $_GET['action'] == 'closeEnquiry' ) {
	
	
	$update_array = array('is_active' => 0, 'ta_query_close_time' => date("Y-m-d H:i:s"));
	
	$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
	$result = update_query($db_name.'.extra_expense_claim_tbl', $update_array, $condition);
			 
	if( $result == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-ta-enquiry-record.php'</script>";
	}
}

if( $_GET['action'] == 'closeTicket' ) {
	
	
	$update_array = array('job_close_time' => date("Y-m-d H:i:s"), 'is_active' => '0', 'closed_by' => 'Backend System');
	
	$condition = array('id' => base64_decode($_GET['id']), 'is_active' => '1', 'loginid' => $_SESSION['user_id']);            
	$result = update_query($db_name.'.all_job_details', $update_array, $condition);
			 
	if( $result == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-request-job.php'</script>";
	}
}

if( $_GET['action'] == 'expanseApprove' ) {
	
	
	$update_array = array('approve_status' => '1');
	
	$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
	$result = update_query($db_name.'.extra_expense_claim_tbl', $update_array, $condition);
			 
	if( $result == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='technicians_extra_expense.php'</script>";
	}
}

if( $_GET['action'] == 'expanseReject' ) {
	
	
	$update_array = array('approve_status' => '2');
	
	$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
	$result = update_query($db_name.'.extra_expense_claim_tbl', $update_array, $condition);
			 
	if( $result == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='technicians_extra_expense.php'</script>";
	}
}

if( $_GET['action'] == 'PostPond' ) {
	
	$req_id = base64_decode($_GET['id']);
	
	$get_edit_recd = select_query("SELECT * FROM $db_name.request WHERE id='".$req_id."' and login_id='".$_SESSION['user_id']."' ");
	
	$PostPondDate = $get_edit_recd[0]['postponed_date'];
	$phone = $get_edit_recd[0]['phone_no'];
	$emp_id = $get_edit_recd[0]['emp_id'];
	
	$fromtime = $PostPondDate;
	$totime = $PostPondDate." 23:59:59";
	
	if((date("H:i:s", strtotime($fromtime)) == '00:00:00' || date("H:i", strtotime($fromtime)) == '00:00')  && (date("H:i:s", strtotime($totime)) == '23:59:59' || date("H:i", strtotime($totime)) == '23:59'))
	{
		$job_sequence = select_query("select max(sequence_no) as sequence_no from $db_name.request where phone_no='".$phone."' and 
			emp_id='".$emp_id."' and sequence_date='".date("Y-m-d", strtotime($PostPondDate))."'");
		 
		//echo "<pre>";print_r($job_sequence);die;
		  
		if($job_sequence[0]['sequence_no']!='') {
			$sequence_no = $job_sequence[0]['sequence_no']+1;
		} else {
			$sequence_no = 0+1;
		}
		
		$check_data = select_query("SELECT * FROM $db_name.request where phone_no='".$phone."' and emp_id='".$emp_id."' and job_type='1' 
			and current_record=0 and sequence_date='".date("Y-m-d", strtotime($PostPondDate))."' ");
		 //echo "<pre>";print_r($check_data);die;
		
		if(count($check_data)>0){	 
					
			$update_array = array('current_record' => 0, 'job_type' => 2, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($PostPondDate)), 'fromtime' => $fromtime, 'totime' => $totime );
		
			$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
			$result = update_query($db_name.'.request', $update_array, $condition);
						
		}else{
			
			$update_array = array('current_record' => 0, 'job_type' => 1, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($PostPondDate)), 'fromtime' => $fromtime, 'totime' => $totime );
				
			$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
			$result = update_query($db_name.'.request', $update_array, $condition);
						
		}
		
		if( $result == true ){
			$_SESSION['success_msg'] = 'set';
			echo "<script>window.location.href='view-request-job.php'</script>";
		}
	
	}
	else if((date("H:i:s", strtotime($fromtime)) > '00:00:00' || date("H:i", strtotime($fromtime)) > '00:00')  && (date("H:i:s", strtotime($totime)) <= '23:59:59' || date("H:i", strtotime($totime)) <= '23:59'))
	{
		
		$check_emp_data = select_query("SELECT * FROM $db_name.request where phone_no='".$phone."' and emp_id='".$emp_id."'  and 
		current_record=0 and ( date(fromtime)='".date("Y-m-d", strtotime($fromtime))."' or  
		date(totime)='".date("Y-m-d", strtotime($fromtime))."' ) order by id ");
		//echo "<pre>";print_r($check_emp_data);die;
		
		$data1 = array();
		$datanew = array();	
		if(count($check_emp_data) > 0)
		{
			foreach ($check_emp_data as $emp => $emparry)
			{
				$arrayData = array(
							'id' => $emparry['id'],
							'job_id' => $emparry['job_id'],
							'emp_id' => $emparry['emp_id'],
							'phone_no' => $emparry['phone_no'],
							'fromtime' => $emparry['fromtime'],
							'totime' => $emparry['totime'],
							'sequence_no' => $emparry['sequence_no'],
							'sequence_date' => $emparry['sequence_date'],
							'current_record' => $emparry['current_record'],
							'job_type' => $emparry['job_type']
						);

				array_push($data1, $arrayData);
			}
			
			$data2 = array_msort($data1, array(
				'fromtime' => SORT_ASC
			));
			//echo "<pre>";print_r($data2);die;
			
			foreach ($data2 as $key => $arryval) {
				
				if($fromtime>$arryval['fromtime'] && date("H:i:s", strtotime($arryval['fromtime'])) == '00:00:00')
				{
					$arraynewData = array(
							'id' => $arryval['id'],
							'job_id' => $arryval['job_id'],
							'emp_id' => $arryval['emp_id'],
							'phone_no' => $arryval['phone_no'],
							'fromtime' => $arryval['fromtime'],
							'totime' => $arryval['totime'],
							'sequence_no' => $arryval['sequence_no'],
							'sequence_date' => $arryval['sequence_date'],
							'current_record' => $arryval['current_record'],
							'job_type' => $arryval['job_type']
						);

					array_push($datanew, $arraynewData);
				}
				else if(strtotime($fromtime)>=strtotime($arryval['fromtime']) && date("H:i:s", strtotime($arryval['fromtime'])) != '00:00:00')
				{
					//echo $fromtime.' <-> '.$arryval['fromtime'];die;
				}
				else if($fromtime<$arryval['fromtime'] && date("H:i:s", strtotime($arryval['fromtime'])) != '00:00:00')
				{
					$arraynewData = array(
							'id' => $arryval['id'],
							'job_id' => $arryval['job_id'],
							'emp_id' => $arryval['emp_id'],
							'phone_no' => $arryval['phone_no'],
							'fromtime' => $arryval['fromtime'],
							'totime' => $arryval['totime'],
							'sequence_no' => $arryval['sequence_no'],
							'sequence_date' => $arryval['sequence_date'],
							'current_record' => $arryval['current_record'],
							'job_type' => $arryval['job_type']
						);

					array_push($datanew, $arraynewData);
				}
				
				
			}
			
			//echo "<pre>";print_r($datanew);die;
			
			$sql2 = "UPDATE $db_name.request SET `sequence_no` = (CASE id ";
	
			foreach ($datanew as $array => $newarray) {						
					$sql2 .= " WHEN ";
					$sql2 .= $newarray['id'];
					$sql2 .= " THEN ";
					$sql2 .= intval($newarray['sequence_no']+1);
					$arrTemp[] = $newarray['id'];
			}
			
			$sql2 .= " END), job_type='2' WHERE id IN( ";
			
			foreach ($arrTemp as $k => $element) {
				if ($k==count($arrTemp)-1)
					$sql2 .= $element;
				else
					$sql2 .= $element.", ";
			}
			
			$sql2 .= " ) AND sequence_date='".date("Y-m-d", strtotime($fromtime))."' AND 
			login_id='".$_SESSION['user_id']."' AND phone_no='".$phone."' AND emp_id = ".$emp_id;
			
			
			$result = select_query($sql2);
			
			foreach ($arrTemp as $k => $element) {
				if ($k==count($arrTemp)-1)
					$seqId .= $element;
				else
					$seqId .= $element.", ";
			}
			
			$job_sequence = select_query("select max(sequence_no) as sequence_no from $db_name.request where phone_no='".$phone."' 
				and emp_id='".$emp_id."' and sequence_date='".date("Y-m-d", strtotime($fromtime))."' and id not IN($seqId) ");
		 
			 //echo "<pre>";print_r($job_sequence);die;
			  
			if($job_sequence[0]['sequence_no']!='') {
				$sequence_no = $job_sequence[0]['sequence_no']+1;
			} else {
				$sequence_no = 0+1;
			}
			
			$check_data = select_query("SELECT * FROM $db_name.request where phone_no='".$phone."' and emp_id='".$emp_id."' and 
				job_type='1' and current_record=0 and sequence_date='".date("Y-m-d", strtotime($PostPondDate))."' ");
			   //echo "<pre>";print_r($check_data);die;
			
			if(count($check_data)>0){	 
						
				$update_array = array('current_record' => 0, 'job_type' => 2, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($PostPondDate)), 'fromtime' => $fromtime, 'totime' => $totime );
			
				$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
				$result = update_query($db_name.'.request', $update_array, $condition);
							
			}else{
				
				$update_array = array('current_record' => 0, 'job_type' => 1, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($PostPondDate)), 'fromtime' => $fromtime, 'totime' => $totime );
					
				$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
				$result = update_query($db_name.'.request', $update_array, $condition);
							
			}
			
			if( $result == true ){
				$_SESSION['success_msg'] = 'set';
				echo "<script>window.location.href='view-request-job.php'</script>";
			}
			
			
		}
		else
		{
			$job_sequence = select_query("select max(sequence_no) as sequence_no from $db_name.request where phone_no='".$phone."' and 
			emp_id='".$emp_id."' and sequence_date='".date("Y-m-d", strtotime($PostPondDate))."'");
		 
			//echo "<pre>";print_r($job_sequence);die;
			  
			if($job_sequence[0]['sequence_no']!='') {
				$sequence_no = $job_sequence[0]['sequence_no']+1;
			} else {
				$sequence_no = 0+1;
			}
			
			$check_data = select_query("SELECT * FROM $db_name.request where phone_no='".$phone."' and emp_id='".$emp_id."' and 
				job_type='1'  and current_record=0 and sequence_date='".date("Y-m-d", strtotime($PostPondDate))."' ");
			   //echo "<pre>";print_r($check_data);die;
			
			if(count($check_data)>0){	 
						
				$update_array = array('current_record' => 0, 'job_type' => 2, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($PostPondDate)), 'fromtime' => $fromtime, 'totime' => $totime );
			
				$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
				$result = update_query($db_name.'.request', $update_array, $condition);
							
			}else{
				
				$update_array = array('current_record' => 0, 'job_type' => 1, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($PostPondDate)), 'fromtime' => $fromtime, 'totime' => $totime );
					
				$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
				$result = update_query($db_name.'.request', $update_array, $condition);
							
			}
			
			if( $result == true ){
				$_SESSION['success_msg'] = 'set';
				echo "<script>window.location.href='view-request-job.php'</script>";
			}
			
		}
		
	}
		
}

if( $_GET['action'] == 'Cancel' ) {
	
	
	$req_id = base64_decode($_GET['id']);	
		 		
	$update_array = array('current_record' => 0, 'job_type' => 2 );

	$condition = array('id' => base64_decode($_GET['id']), 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
	$result = update_query($db_name.'.request', $update_array, $condition);
					
			 
	if( $result == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-request-job.php'</script>";
	}
}

/*if( $_GET['action'] == 'category' ) {
 $q = mysql_query("DELETE FROM categories WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-category.php'</script>";
	}
}


if( $_GET['action'] == 'vehicle-category' ) {
 $q = mysql_query("DELETE FROM vehicle_category WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-vehicle-category.php'</script>";
	}
}




if( $_GET['action'] == 'categories' ) {
 $q = mysql_query("DELETE FROM categories WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-category.php'</script>";
	}
}

if( $_GET['action'] == 'business_type' ) {
 $q = mysql_query("DELETE FROM business_type WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-business-type.php'</script>";
	}
}

if( $_GET['action'] == 'material_type' ) {
 $q = mysql_query("DELETE FROM material_type WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-material-type.php'</script>";
	}
}

if( $_GET['action'] == 'exception_type' ) {
 $q = mysql_query("DELETE FROM exception_type_new WHERE id = '".base64_decode($_GET['id'])."'");
  //$q = mysql_query("DELETE FROM exception_type_new WHERE exception_type = '".$_GET['excep']."' and sub_category='".$_GET['hour']."' ");

	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-exception-type.php'</script>";
	}
}


if( $_GET['action'] == 'vehicle_type' ) {
 $q = mysql_query("DELETE FROM vehicle_type WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-vehicle-type.php'</script>";
	}
}



if( $_GET['action'] == 'vehicle_make' ) {
 $q = mysql_query("DELETE FROM vehicle_make WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-vehicle-make.php'</script>";
	}
}


if( $_GET['action'] == 'route' ) {
 $q = mysql_query("DELETE FROM route WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-route.php'</script>";
	}
}


if( $_GET['action'] == 'party' ) {
 $q = mysql_query("DELETE FROM party WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-party.php'</script>";
	}
}


if( $_GET['action'] == 'third_party' ) {
 $q = mysql_query("DELETE FROM third_party WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-third-party.php'</script>";
	}
}

if( $_GET['action'] == 'contact' ) {
 $q = mysql_query("DELETE FROM contacts_info WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-contacts.php'</script>";
	}
}

if( $_GET['action'] == 'people' ) {
 $q = mysql_query("DELETE FROM people WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-people.php'</script>";
	}
}


if( $_GET['action'] == 'level' ) {
 $q = mysql_query("DELETE FROM level WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-level.php'</script>";
	}
}*/
?>