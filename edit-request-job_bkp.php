<?php 
include('inc/header.php');

$user_id = $_SESSION['user_id'];

function googlatlang($address)
{
	$url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=AIzaSyCMj-azrdqWrM3CypbBoVobpSg7XkUMKHA";
	
	//AIzaSyCl3Ipc_2M0wZ6kTP4Z7Z0Q1xa2rukzt6E
		
	// Make the HTTP request
	$data = @file_get_contents($url);

	// Parse the json response
	$jsondata = json_decode($data,true);

	$lat = $jsondata["results"][0]["geometry"]["location"]["lat"];
	$lng = $jsondata["results"][0]["geometry"]["location"]["lng"];
	/*echo "<script type='text/javascript'>alert('".$lat."');</script>";*/
  return $lat."@".$lng;
  
}

function send_notification_android2($tokens,$message,$androidkey)
{
  $url = 'https://fcm.googleapis.com/fcm/send';
  $fields = array(
	'registration_ids' => $tokens,
	'data' => $message
   );
  $headers = array(
   'Authorization:key = '.$androidkey,
   'Content-Type: application/json'
   );   
   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_POST, true);
	   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	   $result = curl_exec($ch);           
	   if ($result === FALSE) {
		   die('Curl failed: ' . curl_error($ch));
	   }
	   curl_close($ch);
	   return $result;
}

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

$req_id = base64_decode($_REQUEST['id']);


$get_edit_recd = select_query("SELECT * FROM $employee_track.request WHERE id='".$req_id."' and login_id='".$_SESSION['user_id']."' ");
//echo "<pre>";print_r($get_edit_recd);die;

$get_emp_recd = select_query("SELECT id,mobile_no,emp_name,day_start_end FROM $employee_track.login_emp_details WHERE is_active='1' and 
 loginid='".$_SESSION['user_id']."' and mobile_no='".$get_edit_recd[0]['phone_no']."' and id='".$get_edit_recd[0]['emp_id']."' ");

$get_phone_no = $get_edit_recd[0]['phone_no'].'##'.$get_edit_recd[0]['emp_id'].'##'.$get_emp_recd[0]['day_start_end'];

if (isset($_POST['save_people'])) {
	
	//echo "<pre>";print_r($_POST);die;
	$phone_empid = $_POST['phone_number'];
	$emp_phone=explode("##",$phone_empid);
		
	$phone=$emp_phone[0];
	$emp_id=$emp_phone[1];
	$day_status=$emp_phone[2];
	
	$location = $_POST['location'];
	$client_name = $_POST['client_name'];
	$contact_person = $_POST['contact_person'];
	$contact_person_mobile = $_POST['contact_person_mobile'];
	
	if($_POST['fromtime']!='' && $_POST['totime']!='')
	{
		$fromtime = $_POST['fromtime'];
		$totime = $_POST['totime'];
	}
	else if($_POST['fromtime']!='' && $_POST['totime']=='')
	{
		$fromtime = $_POST['fromtime'];
		$totime = date("Y-m-d",strtotime($_POST['fromtime']))." 23:59:59";
	}
	else if($_POST['fromtime']=='' && $_POST['totime']!='')
	{
		$fromtime = date("Y-m-d",strtotime($_POST['totime']))." 00:00:00";
		$totime = $_POST['totime'];
	}
	else if($_POST['fromtime']=='' && $_POST['totime']=='')
	{
		$fromtime = date("Y-m-d")." 00:00:00";
		$totime = date("Y-m-d")." 23:59:59";
	}
	
	/*if($_POST['fromtime']==''){$fromtime = date("Y-m-d")." 00:00:00";}else{$fromtime = $_POST['fromtime'];}
	if($_POST['totime']==''){$totime = date("Y-m-d")." 23:59:59";}else{$totime = $_POST['totime'];}*/
	
	/*$fromtime = $_POST['fromtime'];
	$totime = $_POST['totime'];*/
	$req_id = $_POST['req_id'];
	
	$todayDate = date("Y-m-d");
	
	/*if(date("Y-m-d",strtotime($fromtime)) == $todayDate && $day_status != 1) 
	{
		$_SESSION['jobnotcreate_msg'] = 'set';
	
	} else {*/
	
		$locationcheck = select_query("SELECT * from $employee_track.location WHERE location like '".$location."%' LIMIT 1");
		//print_r($locationcheck);die;
		//echo $locationcheck[0]->latitude;die;
		
		if(count($locationcheck) > 0){
			
			$lat = $locationcheck[0]['latitude'];
			$lng = $locationcheck[0]['longitude'];
		
		} else{
			
			$address=str_replace(' ', '%20',$location);
			
			$latlng = googlatlang($address);
			//echo $latlng;die;
			$splitlatlng = explode("@", $latlng);
	
			$lat = (float)$splitlatlng[0];
			$lng = (float)$splitlatlng[1];
			
			$insert_lat_long = array('latitude' => $lat , 'longitude' => $lng, 'location' => $location, 'phone_no' => $phone);
			$insert_loc = insert_query($employee_track.'.location', $insert_lat_long);
			
			/*$lat = '00.0000000';
			$lng = '00.0000000';*/
			
		}
		
		$cur_date= date('Y-m-d 00:00:00');
		
		$duplicate_chk = select_query("SELECT * FROM $employee_track.request WHERE phone_no='".$phone."' and emp_id='".$emp_id."' and id='".$req_id."'  and login_id='".$_SESSION['user_id']."' and current_record!='1' ");  // check data exist of not
		//echo "<pre>";print_r($duplicate_chk);die;
		
		if(count($duplicate_chk)>0)
		{
			$chk_sameData = select_query("SELECT * FROM $employee_track.request WHERE phone_no='".$phone."' and emp_id='".$emp_id."' and id='".$req_id."'  and login_id='".$_SESSION['user_id']."' and date(fromtime)='".date("Y-m-d", strtotime($fromtime))."' and date(totime)='".date("Y-m-d", strtotime($totime))."' and current_record!='1' "); // check same employee data with same date
			
			if(count($chk_sameData)>0)
			{
				if((date("H:i:s", strtotime($fromtime)) == '00:00:00' || date("H:i", strtotime($fromtime)) == '00:00')  && (date("H:i:s", strtotime($totime)) == '23:59:59' || date("H:i", strtotime($totime)) == '23:59')) //full day time condition
				{
					$update_array = array('client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
					
					$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
					$result = update_query($employee_track.'.request', $update_array, $condition);
				}
				else if((date("H:i:s", strtotime($fromtime)) > '00:00:00' || date("H:i", strtotime($fromtime)) > '00:00')  && (date("H:i:s", strtotime($totime)) < '23:59:59' || date("H:i", strtotime($totime)) < '23:59')) // specific time condition
				{
					$check_emp_data = select_query("SELECT * FROM $employee_track.request where phone_no='".$phone."' and emp_id='".$emp_id."'  
					and current_record=0 and ( date(fromtime)='".date("Y-m-d", strtotime($fromtime))."' or  
					date(totime)='".date("Y-m-d", strtotime($fromtime))."' ) and id!='".$req_id."' order by id "); 
					// check employee submit date data
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
						
						$sql2 = "UPDATE $employee_track.request SET `sequence_no` = (CASE id ";
				
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
						
						$result = select_query($sql2); // change all data sequence no using this query
						
						foreach ($arrTemp as $k => $element) {
							if ($k==count($arrTemp)-1)
								$seqId .= $element;
							else
								$seqId .= $element.", ";
						}
						
						$job_sequence = select_query("select max(sequence_no) as sequence_no from $employee_track.request where 
							phone_no='".$phone."' and emp_id='".$emp_id."' and sequence_date='".date("Y-m-d", strtotime($fromtime))."' 
							and id not IN($seqId) and id!='".$req_id."'"); //get sequence no order
							
						//echo "<pre>";print_r($job_sequence);die;
					  
						if($job_sequence[0]['sequence_no']!='') {
							$sequence_no = $job_sequence[0]['sequence_no']+1;
						} else {
							$sequence_no = 0+1;
						}
						
						$check_data = select_query("SELECT * FROM $employee_track.request where phone_no='".$phone."' and emp_id='".$emp_id."'
						 and job_type='1'  and current_record=0 and ( date(fromtime)='".date("Y-m-d", strtotime($fromtime))."' or  		
	 					 date(totime)='".date("Y-m-d", strtotime($fromtime))."' ) and id!='".$req_id."'");
					
						 //echo "<pre>";print_r($check_data);die;
						 
						if(count($check_data)>0){
						
						$update_array = array('client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 2, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
					
						$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
						$result = update_query($employee_track.'.request', $update_array, $condition);
						
						}else{
							
							$update_array = array('client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 1, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
					
							$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
							$result = update_query($employee_track.'.request', $update_array, $condition);
						}
						
					}
					else
					{
						$update_array = array('client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
					
						$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
						$result = update_query($employee_track.'.request', $update_array, $condition);
					}
					
				}
				
			}
			else
			{
				if((date("H:i:s", strtotime($fromtime)) == '00:00:00' || date("H:i", strtotime($fromtime)) == '00:00')  && (date("H:i:s", strtotime($totime)) == '23:59:59' || date("H:i", strtotime($totime)) == '23:59'))
				{
					$job_sequence = select_query("select max(sequence_no) as sequence_no from $employee_track.request where 
							phone_no='".$phone."' and  emp_id='".$emp_id."' and sequence_date='".date("Y-m-d", strtotime($fromtime))."'");
					
					if($job_sequence[0]['sequence_no']!='') {
						$sequence_no = $job_sequence[0]['sequence_no']+1;
					} else {
						$sequence_no = 0+1;
					}
					
					$check_data = select_query("SELECT * FROM $employee_track.request where phone_no='".$phone."' and emp_id='".$emp_id."' 
					and job_type='1'  and current_record=0 and date(fromtime)='".date("Y-m-d", strtotime($fromtime))."' ");
				
					//echo "<pre>";print_r($check_data);die;
				
					if(count($check_data)>0){	 
									
						$update_array = array('emp_id' => $emp_id, 'phone_no' => $phone, 'client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 2, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
					
						$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
						$result = update_query($employee_track.'.request', $update_array, $condition);
									
					}else{
									
						$update_array = array('emp_id' => $emp_id, 'phone_no' => $phone, 'client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 1, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
					
						$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
						$result = update_query($employee_track.'.request', $update_array, $condition);
									
					}

				}
				else if((date("H:i:s", strtotime($fromtime)) > '00:00:00' || date("H:i", strtotime($fromtime)) > '00:00')  && (date("H:i:s", strtotime($totime)) < '23:59:59' || date("H:i", strtotime($totime)) < '23:59')) // specific time condition
				{
					$check_emp_data = select_query("SELECT * FROM $employee_track.request where phone_no='".$phone."' and emp_id='".$emp_id."'  
					and current_record=0 and ( date(fromtime)='".date("Y-m-d", strtotime($fromtime))."' or  
					date(totime)='".date("Y-m-d", strtotime($fromtime))."' ) and id!='".$req_id."' order by id "); 
					// check employee submit date data
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
						
						$sql2 = "UPDATE $employee_track.request SET `sequence_no` = (CASE id ";
				
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
						
						$result = select_query($sql2); // change all data sequence no using this query
						
						foreach ($arrTemp as $k => $element) {
							if ($k==count($arrTemp)-1)
								$seqId .= $element;
							else
								$seqId .= $element.", ";
						}
						
						$job_sequence = select_query("select max(sequence_no) as sequence_no from $employee_track.request where 
							phone_no='".$phone."' and emp_id='".$emp_id."' and sequence_date='".date("Y-m-d", strtotime($fromtime))."' 
							and id not IN($seqId) and id!='".$req_id."'"); //get sequence no order
							
						//echo "<pre>";print_r($job_sequence);die;
					  
						if($job_sequence[0]['sequence_no']!='') {
							$sequence_no = $job_sequence[0]['sequence_no']+1;
						} else {
							$sequence_no = 0+1;
						}
						
						$check_data = select_query("SELECT * FROM $employee_track.request where phone_no='".$phone."' and emp_id='".$emp_id."'
						 and job_type='1'  and current_record=0 and ( date(fromtime)='".date("Y-m-d", strtotime($fromtime))."' or  		
	 					 date(totime)='".date("Y-m-d", strtotime($fromtime))."' ) and id!='".$req_id."'");
					
						 //echo "<pre>";print_r($check_data);die;
						 
						if(count($check_data)>0){
						
						$update_array = array('client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 2, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
					
						$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
						$result = update_query($employee_track.'.request', $update_array, $condition);
						
						}else{
							
							$update_array = array('client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 1, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
					
							$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
							$result = update_query($employee_track.'.request', $update_array, $condition);
						}
						
					}
					else
					{
						$job_sequence = select_query("select max(sequence_no) as sequence_no from $employee_track.request where 
							phone_no='".$phone."' and  emp_id='".$emp_id."' and sequence_date='".date("Y-m-d", strtotime($fromtime))."'");
					
						if($job_sequence[0]['sequence_no']!='') {
							$sequence_no = $job_sequence[0]['sequence_no']+1;
						} else {
							$sequence_no = 0+1;
						}
						
						$check_data = select_query("SELECT * FROM $employee_track.request where phone_no='".$phone."' and emp_id='".$emp_id."' 
						and job_type='1'  and current_record=0 and date(fromtime)='".date("Y-m-d", strtotime($fromtime))."' ");
					
						//echo "<pre>";print_r($check_data);die;
					
						if(count($check_data)>0){	 
										
							$update_array = array('emp_id' => $emp_id, 'phone_no' => $phone, 'client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 2, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
						
							$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
							$result = update_query($employee_track.'.request', $update_array, $condition);
										
						}else{
										
							$update_array = array('emp_id' => $emp_id, 'phone_no' => $phone, 'client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 1, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
						
							$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
							$result = update_query($employee_track.'.request', $update_array, $condition);
										
						}
						
					}
					
					
				}
				
			
			}
			
			echo "<script>window.location.href='view-request-job.php'</script>";
			
		}
		else 
		{
			if((date("H:i:s", strtotime($fromtime)) == '00:00:00' || date("H:i", strtotime($fromtime)) == '00:00')  && (date("H:i:s", strtotime($totime)) == '23:59:59' || date("H:i", strtotime($totime)) == '23:59'))
			{
				$job_sequence = select_query("select max(sequence_no) as sequence_no from $employee_track.request where phone_no='".$phone."' and 
				emp_id='".$emp_id."' and sequence_date='".date("Y-m-d", strtotime($fromtime))."'");
				 
				 //echo "<pre>";print_r($job_sequence);die;
				  
				if($job_sequence[0]['sequence_no']!='') {
					$sequence_no = $job_sequence[0]['sequence_no']+1;
				} else {
					$sequence_no = 0+1;
				}
				
				$check_data = select_query("SELECT * FROM $employee_track.request where phone_no='".$phone."' and emp_id='".$emp_id."' and 
							job_type='1' and current_record=0 and date(fromtime)='".date("Y-m-d", strtotime($fromtime))."' ");
				
				 //echo "<pre>";print_r($check_data);die;
				
				if(count($check_data)>0){	 
								
					$update_array = array('emp_id' => $emp_id, 'phone_no' => $phone, 'client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 2, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
				
					$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
					$result = update_query($employee_track.'.request', $update_array, $condition);
								
				}else{
								
					$update_array = array('emp_id' => $emp_id, 'phone_no' => $phone, 'client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 1, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
				
					$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
					$result = update_query($employee_track.'.request', $update_array, $condition);
								
				}
				
				
				$tokenResult = select_query("SELECT device_key as token FROM $employee_track.installer_app_verify where phone_no='".$phone."' 
								and is_active='1' order by id desc limit 0,1");
				
				if(count($tokenResult)>0)
				{
					$tokens[] = $tokenResult[0]['token'];
			
					$Notificato_msg = array("data" => "New Job Allocated. Please Refresh Application");
			 
					$androidkey = "AAAAhDM80OU:APA91bFTrEQU9iC9_tuI11NFyFh87mjQGq2GvYEflDZJdQlbkXmylofJFuUhlux0R78ZParyGVrn9f41IawZa8hDAUUJZ59DmeSdJogUPilC4vrqYVVufFAs7bSSQ3LTrxvt_wgjCqnm";
			
					$message_status = send_notification_android2($tokens,$Notificato_msg,$androidkey);		
				}
				
				if($result) {
		
					echo "<script>window.location.href='view-request-job.php'</script>";
			
				
					$_SESSION['success_msg'] = 'set';
				
				}
			
			}
			else if((date("H:i:s", strtotime($fromtime)) > '00:00:00' || date("H:i", strtotime($fromtime)) > '00:00')  && (date("H:i:s", strtotime($totime)) < '23:59:59' || date("H:i", strtotime($totime)) < '23:59'))
			{
				$check_emp_data = select_query("SELECT * FROM $employee_track.request where phone_no='".$phone."' and emp_id='".$emp_id."'  and 
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
					
					$sql2 = "UPDATE $employee_track.request SET `sequence_no` = (CASE id ";
			
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
					
					if($seqId!='')
					{
					$job_sequence = select_query("select max(sequence_no) as sequence_no from $employee_track.request where phone_no='".$phone."' 
						and emp_id='".$emp_id."' and sequence_date='".date("Y-m-d", strtotime($fromtime))."' and id not IN($seqId) ");
					}
					else
					{
					$job_sequence = select_query("select max(sequence_no) as sequence_no from $employee_track.request where 
						phone_no='".$phone."' and emp_id='".$emp_id."' and sequence_date='".date("Y-m-d", strtotime($fromtime))."' ");
					}
					 //echo "<pre>";print_r($job_sequence);die;
					  
					if($job_sequence[0]['sequence_no']!='') {
						$sequence_no = $job_sequence[0]['sequence_no']+1;
					} else {
						$sequence_no = 0+1;
					}
					
					$check_data = select_query("SELECT * FROM $employee_track.request where phone_no='".$phone."' and emp_id='".$emp_id."' and 
					job_type='1' and current_record=0 and date(fromtime)='".date("Y-m-d", strtotime($fromtime))."' ");
					
					 //echo "<pre>";print_r($check_data);die;
					
					if(count($check_data)>0){	 
									
						$update_array = array('emp_id' => $emp_id, 'phone_no' => $phone, 'client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 2, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
					
						$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
						$result = update_query($employee_track.'.request', $update_array, $condition);
									
					}else{
									
						$update_array = array('emp_id' => $emp_id, 'phone_no' => $phone, 'client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 1, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
					
					$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
					$result = update_query($employee_track.'.request', $update_array, $condition);
									
					}
					
					
					$tokenResult = select_query("SELECT device_key as token FROM $employee_track.installer_app_verify where phone_no='".$phone."' and is_active='1' order by id desc limit 0,1");
					
					if(count($tokenResult)>0)
					{
						$tokens[] = $tokenResult[0]['token'];
				
						$Notificato_msg = array("data" => "New Job Allocated. Please Refresh Application");
				 
						$androidkey = "AAAAhDM80OU:APA91bFTrEQU9iC9_tuI11NFyFh87mjQGq2GvYEflDZJdQlbkXmylofJFuUhlux0R78ZParyGVrn9f41IawZa8hDAUUJZ59DmeSdJogUPilC4vrqYVVufFAs7bSSQ3LTrxvt_wgjCqnm";
				
						$message_status = send_notification_android2($tokens,$Notificato_msg,$androidkey);		
					}
					
					if($result) {
			
						echo "<script>window.location.href='view-request-job.php'</script>";
				
					
						$_SESSION['success_msg'] = 'set';
					
					}
					
				}
				else
				{
					$job_sequence = select_query("select max(sequence_no) as sequence_no from $employee_track.request where phone_no='".$phone."'
					 and  emp_id='".$emp_id."' and sequence_date='".date("Y-m-d", strtotime($fromtime))."'");
					 
					 //echo "<pre>";print_r($job_sequence);die;
					  
					if($job_sequence[0]['sequence_no']!='') {
						$sequence_no = $job_sequence[0]['sequence_no']+1;
					} else {
						$sequence_no = 0+1;
					}
					
					$check_data = select_query("SELECT * FROM $employee_track.request where phone_no='".$phone."' and emp_id='".$emp_id."' 
					and job_type='1' and current_record=0 and date(fromtime)='".date("Y-m-d", strtotime($fromtime))."' ");
					
					 //echo "<pre>";print_r($check_data);die;
					
					if(count($check_data)>0){	 
									
						$update_array = array('emp_id' => $emp_id, 'phone_no' => $phone, 'client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 2, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
					
						$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
						$result = update_query($employee_track.'.request', $update_array, $condition);
									
					}else{
									
						$update_array = array('emp_id' => $emp_id, 'phone_no' => $phone, 'client_name' => $client_name, 'job_location' => $location, 'latitude' => $lat, 'longtitude' => $lng, 'contactname' => $contact_person, 'contactno' => $contact_person_mobile, 'fromtime' => $fromtime, 'totime' => $totime, 'jobassigndate' => date("Y-m-d H:i:s"), 'current_record' => 0, 'job_type' => 1, 'sequence_no' => $sequence_no, 'sequence_date' => date("Y-m-d", strtotime($fromtime)));
					
					$condition = array('id' => $req_id, 'is_active' => 1, 'login_id' => $_SESSION['user_id']);            
					$result = update_query($employee_track.'.request', $update_array, $condition);
									
					}
					
					
					$tokenResult = select_query("SELECT device_key as token FROM $employee_track.installer_app_verify where phone_no='".$phone."' and is_active='1' order by id desc limit 0,1");
					
					if(count($tokenResult)>0)
					{
						$tokens[] = $tokenResult[0]['token'];
				
						$Notificato_msg = array("data" => "New Job Allocated. Please Refresh Application");
				 
						$androidkey = "AAAAhDM80OU:APA91bFTrEQU9iC9_tuI11NFyFh87mjQGq2GvYEflDZJdQlbkXmylofJFuUhlux0R78ZParyGVrn9f41IawZa8hDAUUJZ59DmeSdJogUPilC4vrqYVVufFAs7bSSQ3LTrxvt_wgjCqnm";
				
						$message_status = send_notification_android2($tokens,$Notificato_msg,$androidkey);		
					}
					
					if($result) {
			
						echo "<script>window.location.href='view-request-job.php'</script>";
				
					
						$_SESSION['success_msg'] = 'set';
					
					}
				
				}
				
			}
		
		}
	
	/*}*/

}
?>

<link rel="stylesheet" href="<? echo __SITE_URL?>/css/bootstrap-select.css">

 
<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="view-request-job.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Edit Job Request</a> </div>
    
  </div>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
            <h5>Edit Job Request</h5>
          </div>
          <div class="widget-content nopadding">
            <form name="myForm" id="myForm" action="" method="post" class="form-horizontal" autocomplete="off">
              <div class="alert alert-error error_display" style="display:none">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span id="print_err"></span>
			  </div>
			  
			  <?php if(isset($_SESSION['success_msg'])) {  ?>
				<div class="alert alert-success success_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Success!</strong><span> Succesfully added. Click <a href="view-request-job.php">here</a> to View</span>
			  </div>
			  <?php } else if(isset($_SESSION['unsuccess_msg'])) {  ?>
				<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> This Job Already Exist. </span>
			  </div>
              <?php } else if(isset($_SESSION['jobnotcreate_msg'])) {  ?>
				<div class="alert alert-error error_display">
                <button class="close" data-dismiss="alert">x</button>
                <strong class="error_submission">Error!</strong><span> Can't Create Today Job Due to Day Not Start by Employee. </span>
			  </div>
			  <?php } 
			  unset($_SESSION['success_msg']);
			  unset($_SESSION['unsuccess_msg']);
			  unset($_SESSION['jobnotcreate_msg']);
			  
			  /*$get_emp_recd = select_query("SELECT id,concat(mobile_no,' - ',emp_name) as emp_name,concat(mobile_no,'##',id) as emp_details FROM $employee_track.login_app WHERE is_active='1' and loginid='".$_SESSION['user_id']."' ");*/
			  
			  $get_emp_recd = select_query("SELECT id,concat(mobile_no,' - ',emp_name) as emp_name,concat(mobile_no,'##',id) as emp_details,
			  day_start_end FROM $employee_track.login_emp_details WHERE is_active='1' and loginid='".$_SESSION['user_id']."'");
			  ?>
			  	<input type="hidden" name="req_id" id="req_id" value="<?php echo $req_id;?>"/>
				<div class="control-group">
                <label class="control-label">Phone Number:</label>
                <div class="controls"> 
                    <div class="col-sm-3">
                        <select class="selectpicker pull-left sepratesize" data-live-search="true" title="Select Phone Number" name="phone_number" id="phone_number">
                          <!--<option value="">Select Phone Number</option>-->
                        <?php for($rq=0;$rq<count($get_emp_recd);$rq++) { ?>
                          <option value="<?=$get_emp_recd[$rq]['emp_details'].'##'.$get_emp_recd[$rq]['day_start_end'];?>" <? if($get_phone_no==$get_emp_recd[$rq]['emp_details'].'##'.$get_emp_recd[$rq]['day_start_end']) {?> selected="selected" <? } ?>><?=$get_emp_recd[$rq]['emp_name'];?></option>
                          <!--<input type="text" name="number" maxlength="10" id="number"  class="mandatory" placeholder="Number *" />-->
                        <? } ?>
                        </select>
                        <span id="branch_error"></span> </div>
                  </div>
              </div>
              
               <div class="control-group">
                <label class="control-label">Location:</label>
                <div class="controls">
                  <input type="text" name="location" id="autocomplete" class="mandatory" placeholder="Location *" value="<?php echo $get_edit_recd[0]['job_location'];?>" />
                  <span id="branch_error"></span> </div>
              </div>
 			  
              <div class="control-group">
                <label class="control-label">From time:</label>
                <div class="controls date form_datetime" data-date="" data-date-format="yyyy-mm-dd hh:ii" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii">
                  <input class="mandatory date-picker" name="fromtime" id="dateStart" type="text" value="<?php echo $get_edit_recd[0]['fromtime'];?>" placeholder="From Time" readonly>
                  <span class="add-on"><i class="icon-th"></i></span>
                </div>
                <!--<div class="controls">
                  <input type="text" name="client_name" maxlength="10" id="client_name"  class="mandatory" placeholder="Client Name *" />
                  <span id="branch_error"></span> </div>-->
              </div>
              
              <div class="control-group">
                <label class="control-label">To time:</label>
                <div class="controls date form_datetime" data-date="" data-date-format="yyyy-mm-dd hh:ii" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii">
                   <input class="mandatory date-picker" name="totime" id="dateEnd" type="text" value="<?php echo $get_edit_recd[0]['totime'];?>"  placeholder="To Time" readonly>
                   <span class="add-on"><i class="icon-th"></i></span>
               </div>
                <!--<div class="controls">
                  <input type="text" name="client_name" maxlength="10" id="client_name"  class="mandatory" placeholder="Client Name *" />
                  <span id="branch_error"></span> </div>-->
              </div>
              			
			  <div class="control-group">
                <label class="control-label">Client Name:</label>
                <div class="controls">
                  <input type="text" name="client_name" id="client_name"  class="mandatory" placeholder="Client Name" value="<?php echo $get_edit_recd[0]['client_name'];?>" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Contact Person:</label>
                <div class="controls">
                  <input type="text" name="contact_person" id="contact_person"  class="mandatory" placeholder="Contact Person" value="<?php echo $get_edit_recd[0]['contactname'];?>" />
                  <span id="branch_error"></span> </div>
              </div>
              
              <div class="control-group">
                <label class="control-label">Contact Person No:</label>
                <div class="controls">
                  <input type="text" name="contact_person_mobile" maxlength="10" id="contact_person_mobile"  class="mandatory" placeholder="Contact Person No" value="<?php echo $get_edit_recd[0]['contactno'];?>" />
                  <span id="branch_error"></span> </div>
              </div>
			  
              <div class="form-actions">
                <button type="submit" class="btn-harish btn-info-harish save_step_1" name="save_people">Save</button>
                <a  class="btn-harish btn-info-harish" href="view-request-job.php" style="color: #fff;">Cancel</a>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<!--<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/libs/jquery/jquery.min.js"></script> -->
<script src="<? echo __SITE_URL?>/js/bootstrap-select.js"></script>   
<script type="text/javascript" src="<?php echo __SITE_URL;?>/js/libs/bootstrap/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script>

function getDateTime() {
	var now     = new Date(); 
	var year    = now.getFullYear();
	var month   = now.getMonth()+1; 
	var day     = now.getDate();
	/*var hour    = now.getHours();
	var minute  = now.getMinutes();
	var second  = now.getSeconds();*/ 
	
	if(month.toString().length == 1) {
		 month = '0'+month;
	}
	if(day.toString().length == 1) {
		 day = '0'+day;
	}   
	/*if(hour.toString().length == 1) {
		 hour = '0'+hour;
	}
	if(minute.toString().length == 1) {
		 minute = '0'+minute;
	}
	if(second.toString().length == 1) {
		 second = '0'+second;
	}   
	var dateTime = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second; */  
	
	var dateTime = year+'-'+month+'-'+day; 
	
	return dateTime;
}
	  
$( document ).ready(function(){

////////////////////////// Validation ////////////////////////
	
    $('.save_step_1').click(function(e) {
	
		var phone_number = $("#phone_number").val();
		if( phone_number == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Select Phone No.");
			return false;
		}
		
		var location = $("#autocomplete").val();
		if( location == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Location.");
			return false;
		}
		
		var fields = phone_number.split('##');
		var day_status = fields[2];
		//alert(day_status);
		var currentTime = getDateTime();
		//alert(currentTime);
		
		var fromTime = $("#dateStart").val();
		var toTime = $("#dateEnd").val();
		
		var breaktime = fromTime.split(' ');
		var getDateVal = breaktime[0];
		//alert(getDateVal);
		
		/*if(fromTime == '' && toTime == '' && day_status != 1) {
			
			    $(".error_display").css("display","block");
				$("#print_err").html(" Can't Create Today Job Due to Day Not Start by Employee.");
				return false;
			
		}
		else if(getDateVal == currentTime && day_status != 1) {
			
			    $(".error_display").css("display","block");
				$("#print_err").html(" Can't Create Today Date Job Due to Day Not Start by Employee.");
				return false;
			
		}*/
		
		if((fromTime != '' && toTime != '') && (fromTime != null && toTime != null)) {
			
			if(fromTime>=toTime)
			{
			    $(".error_display").css("display","block");
				$("#print_err").html(" To Time always greater then From Time.");
				return false;
			}
			
		}
				
		if((fromTime != '' && toTime == '' && fromTime != null)) {
			
			    $(".error_display").css("display","block");
				$("#print_err").html(" Place Select To Time.");
				return false;
			
		}
		
		if((fromTime == '' && toTime != '' && toTime != null)) {
			
			    $(".error_display").css("display","block");
				$("#print_err").html(" Place Select From Time.");
				return false;
			
		}
		
		/*var client_name = $("#client_name").val();
		if( client_name == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please enter Client Name.");
			return false;
		}
		
		var contact_person = $("#contact_person").val();
		if( contact_person == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Contact Person.");
			return false;
		}
		
		var p_name = $("#contact_person_mobile").val();
		if( p_name == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter Contact Person No.");
			return false;
		}
		if(p_name!="")
		{
			var lenth=p_name.length;
			
			if(lenth < 10 || lenth > 12 || p_name.search(/[^0-9\-()+]/g) != -1 )
			{
				$(".error_display").css("display","block");
				$("#print_err").html(" Please enter valid Phone number.");
				return false;
			}
		}
		
		var dateStart = $("#dateStart").val();
		if( dateStart == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter From Time.");
			return false;
		}
		
		
		var dateEnd = $("#dateEnd").val();
		if( dateEnd == '' ) {
			$(".error_display").css("display","block");
			$("#print_err").html(" Please Enter To Time.");
			return false;
		}*/
		else {
			$("#print_err").html("");
			$(".error_display").css("display","none");
		}	   
	   
    });
	
	$('#myForm').on('keyup keypress', function(e) {
	  var keyCode = e.keyCode || e.which;
	  if (keyCode === 13) { 
		e.preventDefault();
		return false;
	  }
	});
	
	 $("#contact_person_mobile").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

////////////////////////// Validation ////////////////////////
	
});

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMj-azrdqWrM3CypbBoVobpSg7XkUMKHA&signed_in=true&libraries=places&callback=initAutocomplete" async defer></script>
      
<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMj-azrdqWrM3CypbBoVobpSg7XkUMKHA&libraries=places&callback=initAutocomplete" async defer></script> -->
 <!--AIzaSyCl3Ipc_2M0wZ6kTP4Z7Z0Q1xa2rukzt6E -->      
<script>
// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

var placeSearch, autocomplete;
var componentForm = {
street_number: 'short_name',
route: 'long_name',
locality: 'long_name',
administrative_area_level_1: 'short_name',
country: 'long_name',
postal_code: 'short_name'
};

function initAutocomplete() {
// Create the autocomplete object, restricting the search to geographical
// location types.
autocomplete = new google.maps.places.SearchBox(
	/** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
	{types: ['geocode']});

// When the user selects an address from the dropdown, populate the address
// fields in the form.
autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
// Get the place details from the autocomplete object.
var place = autocomplete.getPlace();

}

</script>
<link href="<?php echo __SITE_URL;?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
		minuteStep: 1,
        todayHighlight: true,
		startDate: new Date(),
    });
    $('.form_date').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
		startDate: new Date(),
    });
    $('.form_time').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });

</script>
<?php include('inc/footer.php');?>
