<?php
date_default_timezone_set ("Asia/Calcutta");

class micar
{
  

	function getRFIDS($UserId)
	{ 

	$this->data=select_query("select rfid_GtracNumber from micar_rfidmappinguser where  user_id=".$UserId." and active_status=1 and is_blocked=0");

	return $this->data;

	}

	function getvehicle_byrfid($rfid)
	{ 

	$vehicle_num=select_query("select veh_id as vehicle_number from micar_mapreservationvehicle   where activation_status=1 and rfid_number='".$rfid."'");
 
$myarray = array();
 for($v=0;$v<count($vehicle_num);$v++)
				{ 
	 array_push($myarray,$vehicle_num[$v]["vehicle_number"]);
				 
				}

				 

				//$vehiclelistJson = substr( $vhl_list,0,strlen( $vhl_list)-1);

$vehiclelistJson =$vehiclelistJson."]}";
//{
   //"vehicles": ["DL1CD543",  "DL1CD545","DL1CD544"]
 // }


	return $myarray;

	}



	function Is_validRFID($UserId,$rfid)
	{ 

	$this->data=select_query("select rfid_GtracNumber from micar_rfidmappinguser where  user_id=".$UserId." and active_status=1 and is_blocked=0 and rfid_GtracNumber in ('".$rfid."')");

	 $count=count($this->data);
	 if($count>0)
		{
		 return true;
		}
		else
		{
			return false;
		}

	}

	
	function Is_validreservation($reservationid)
	{ 

	 

	return $this->data=select_query("select * from micar_mapreservationvehicle left join micar_memberbookingmaster on micar_mapreservationvehicle.reservation_id =micar_memberbookingmaster.reservation_id where micar_mapreservationvehicle.reservation_id='".$reservationid."'");

	 $count=count($this->data);
	 if($count>0)
		{
		 return true;
		}
		else
		{
			return false;
		}

	}

	


	function Is_multiplevalidRFID($UserId,$rfid)
	{ 
 
    

	$this->data=select_query("select rfid_GtracNumber from micar_rfidmappinguser where  user_id=".$UserId." and active_status=1 and is_blocked=0 and rfid_GtracNumber in (".$rfid.")");

	 //return $count=count($this->data);

	  return $count=$this->data;
	  

	}




	function Is_validVehicle($Group_id,$Veh_reg)
	{ 

 
	$this->data=select_query("select services.veh_reg,services.id,devices.imei from group_services left join services on  group_services.sys_service_id=services.id  left join devices on 
services.sys_device_id=devices.id where sys_group_id in(".$Group_id.") and veh_reg in (".$Veh_reg.")");
 
	 return $this->data;
		  
		 
	}


	function Block_rfid($UserId,$rfid)
	{ 

	$result = mysql_query("update  micar_rfidmappinguser set is_blocked=1 where user_id=".$UserId." and active_status=1  and rfid_GtracNumber=".$rfid);

	   return mysql_affected_rows();
		 

	}

	function UnBlock_rfid($UserId,$rfid)
	{ 

	$result = mysql_query("update  micar_rfidmappinguser set is_blocked=0 where user_id=".$UserId." and active_status=1  and rfid_GtracNumber=".$rfid);

	   return mysql_affected_rows();
		 

	}

	function Insert_employeeID($UserId,$rfid)
	{ 

	$result = mysql_query("update  micar_rfidmappinguser set is_blocked=0 where user_id=".$UserId." and active_status=1  and rfid_GtracNumber=".$rfid);

	   return mysql_affected_rows();
		 

	}
 


	function Insert_EmpmapvehicleRFID($GroupId,$UserId,$rfids,$employee_id,$cntvehicle_list,$start_time,$end_time,$vehicleNUms)
	{ 
		 
	  $countveh=$cntvehicle_list;
	 
	     $validVehicle=$this->Is_validVehicle($GroupId,$vehicleNUms);
	     $countValidVhl=count($validVehicle);

		

 	if($countveh==$countValidVhl)
		{
		$rfid=explode(",",$rfids);
		  if( $end_time=="")
			{
			$maininsert = mysql_query("INSERT INTO micar_employeebookingmaster (gtrac_user_id, employee_id, BookingStartDateTime, BookingEndDateTime, activation_status, is_closed, is_booking_closed, Remarks) VALUES ('".$UserId."','".$employee_id."','".$start_time."',null,1,0,0,'')");
			}
			else
			{
				$maininsert = mysql_query("INSERT INTO micar_employeebookingmaster (gtrac_user_id, employee_id, BookingStartDateTime, BookingEndDateTime, activation_status, is_closed, is_booking_closed, Remarks) VALUES ('".$UserId."','".$employee_id."','".$start_time."','".$end_time."',1,0,0,'')");
			}
			 
			 
 
   $inserted_id=mysql_insert_id();
   if($inserted_id>0)
			{
		for($r=0;$r<count($rfid);$r++)
			
		{
			 
			for($v=0;$v<count($validVehicle);$v++)
				{ 

				 $service_id=$validVehicle[$v]["id"];
						   $Device_imei=$validVehicle[$v]["imei"];

				$result = mysql_query("INSERT INTO micar_mapreservationvehicle (reservation_id, employee_id, veh_id, rfid_number, activation_status,masterInsert_id) VALUES (0,'".$employee_id."','".$validVehicle[$v]["veh_reg"]."','".$rfid[$r]."',1,".$inserted_id.")");
$inserted_map=mysql_insert_id();
							if($inserted_map>0)
{ 
 								
								$rfid=str_replace("'",$rfid[$r]);
								$newbooking_start_time = date("Y-m-d H:i:s");
								
								$block_no_query = mysql_fetch_array(mysql_query("select block_no from micar_devicemappingrfid where `device_imei`='".$Device_imei."' AND is_active=1 order by `block_no` desc"));
																
								$block_no_value = $block_no_query["block_no"]+1;
								
								if($block_no_value <= 30){
									
									$block_no = $block_no_value;
									
								}
								else{
									
									$block_no_query1 = mysql_fetch_array(mysql_query("select block_no from micar_devicemappingrfid where `device_imei`='".$Device_imei."' and `is_active`=0 and `block_activestatus`=0 order by `block_no` asc"));
									
									$block_no = $block_no_query1["block_no"];

								}

																
								$insert_query = mysql_query("INSERT INTO `micar_devicemappingrfid` (`block_no` ,`device_imei` ,`rfid_key` ,`is_active` ,`block_activestatus`) VALUES('".$block_no."', ".$Device_imei.", '".$rfid."', '1', '1')");
								

								  switch ($block_no){
									  
								   case 1:
									$x =$rfid;
									$y = substr($x,0,6);
									$z = substr($x,-6,6);

									   
 
									  	

										for($i=1;$i<=4;$i++){
											
											if($i%2)
											{
												$rfid=$y;
											}
											else
											{
												$rfid=$z;
											}
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
									  
									  break;
									
									case 2:
										
										for($i=5;$i<=6;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;	
									
									case 3:
										
										for($i=7;$i<=8;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 4:
										
										for($i=9;$i<=10;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 5:
										$x =$rfid;
										$y = substr($x,0,4);
										$z = substr($x,-8,8);
										for($i=11;$i<=14;$i++){

											
											if($i==11)
											{
												$rfid=$y;
											}
											elseif($i==12)
											{
												$rfid=$z;
											}
											if($i==13)
											{
												$rfid=$y;
											}
											elseif($i==14)
											{
												$rfid=$z;
											}
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 6:
										
										for($i=15;$i<=16;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 7:
										
										for($i=17;$i<=18;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 8:
										
										for($i=19;$i<=20;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 9:
										
										for($i=21;$i<=22;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 10:
										$x =$rfid;
										$y = substr($x,0,6);
										$z = substr($x,-6,6);
											
										for($i=23;$i<=26;$i++){
											if($i==23)
											{
												$rfid=$y;
											}
											elseif($i==24)
											{
												$rfid=$z;
											}
											if($i==25)
											{
												$rfid=$y;
											}
											elseif($i==26)
											{
												$rfid=$z;
											}
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;	
									
									case 11:
										
										for($i=27;$i<=28;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 12:
										
										for($i=29;$i<=30;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 13:
										$x =$rfid;
										$y = substr($x,0,4);
										$z = substr($x,-8,8);
											
										
										for($i=31;$i<=34;$i++){
											if($i==31)
											{
												$rfid=$y;
											}
											elseif($i==32)
											{
												$rfid=$z;
											}
											if($i==33)
											{
												$rfid=$y;
											}
											elseif($i==34)
											{
												$rfid=$z;
											}
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 14:
										
										for($i=35;$i<=36;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 15:
										
										for($i=37;$i<=38;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 16:
										
										for($i=39;$i<=40;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 17:
										
										for($i=41;$i<=42;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 18:
										$x =$rfid;
										$y = substr($x,0,6);
										$z = substr($x,-6,6);
											
										for($i=43;$i<=46;$i++){
											if($i==43)
											{
												$rfid=$y;
											}
											elseif($i==44)
											{
												$rfid=$z;
											}
											if($i==45)
											{
												$rfid=$y;
											}
											elseif($i==46)
											{
												$rfid=$z;
											}
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 19:
										
										for($i=47;$i<=48;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 20:
										
										for($i=49;$i<=50;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 21:
										$x =$rfid;
										$y = substr($x,0,4);
										$z = substr($x,-8,8);
											
										for($i=51;$i<=54;$i++){
											if($i==51)
											{
												$rfid=$y;
											}
											elseif($i==52)
											{
												$rfid=$z;
											}
											if($i==53)
											{
												$rfid=$y;
											}
											elseif($i==54)
											{
												$rfid=$z;
											}
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 22:
										
										for($i=55;$i<=56;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 23:
										
										for($i=57;$i<=58;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 24:
										
										for($i=59;$i<=60;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 25:
										
										for($i=61;$i<=62;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 26:
										$x =$rfid;
										$y = substr($x,0,6);
										$z = substr($x,-6,6);
											
										for($i=63;$i<=66;$i++){
											if($i==63)
											{
												$rfid=$y;
											}
											elseif($i==64)
											{
												$rfid=$z;
											}
											if($i==65)
											{
												$rfid=$y;
											}
											elseif($i==66)
											{
												$rfid=$z;
											}
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 27:
										
										for($i=67;$i<=68;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 28:
										
										for($i=69;$i<=70;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 29:
										$x =$rfid;
										$y = substr($x,0,4);
										$z = substr($x,-8,8);
											
										
										for($i=71;$i<=74;$i++){

											if($i==71)
											{
												$rfid=$y;
											}
											elseif($i==72)
											{
												$rfid=$z;
											}
											if($i==73)
											{
												$rfid=$y;
											}
											elseif($i==74)
											{
												$rfid=$z;
											}
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
									
									case 30:
										
										for($i=75;$i<=76;$i++){
											
											$WriteRFid_withVhl1 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', '".$i."', 5, 1,  '".$newbooking_start_time."', 'micar_api');");
										
										}
										
									break;
										
									default:
									
										$msg = "All Slot are Full";
									
									break;
													
									}
								
								
								//$WriteRFid_withVhl2 = mysql_query("INSERT INTO `pointer_request` ( `sys_service_id`, `imei`, `rfid_key`, `block_number`, `request`, `status`, `request_date`,   `mobile_num`) VALUES ( ".$service_id.", ".$Device_imei.", '".$rfid."', 6, 5, 1,  '".$booking_start_time."', 'micar_api');");
								
								$inserted_rfidWriteTest=mysql_insert_id();

							if($inserted_rfidWriteTest>0)
								{ 
								$arrResult = array("success" => "yes", "message" => "Booking  Successfull", "code" => "200","payload" => "");
								}
								}










				}
		}
		return true;
			}
			else
			{
				return false;
			}
			
 
	 		
		}
		else
		{

			return false;
		}
		  
	}


	
function deactivate_EmpmapvehicleRFID($GroupId,$UserId,$rfid,$employee_id,$vehicle_list,$start_time,$end_time,$vehicleNUms)
	{ 
			 
			//$InsertID=select_query("select masterInsert_id from micar_mapreservationvehicle where veh_id='".$vehicle_list[0]."' and rfid_number=".$rfid[0]);


			//$Updatemicar_employeebookingmaster = mysql_query("update micar_employeebookingmaster set activation_status=0 where id=". $InsertID[0]["masterInsert_id"]);

			//$Updatemicar_mapreservationvehicle = mysql_query("update micar_mapreservationvehicle set activation_status=0 where masterInsert_id=".$InsertID[0]["masterInsert_id"]);
$file = fopen("C:/xampp/htdocs/Micar/employee_dectivaterfid.txt","w");
fwrite($file,"Open");
			for($r=0;$r<count($rfid);$r++)
			{
			 
			for($v=0;$v<count($vehicle_list);$v++)
				{ 

				$Updatemicar_mapreservationvehicle = mysql_query("update micar_mapreservationvehicle set activation_status=0  where veh_id='".$vehicle_list[$v]."' and rfid_number='".$rfid[$r]."' and employee_id='".$employee_id."'");

				
				fwrite($file,"update micar_mapreservationvehicle set activation_status=0  where veh_id='".$vehicle_list[$v]."' and rfid_number='".$rfid[$r]."' and employee_id='".$employee_id."'");

				}

			}
			
		
		/*for($r=0;$r<count($rfid);$r++)
			{
			 
			for($v=0;$v<count($vehicle_list);$v++)
				{
				  
				$result = mysql_query("update micar_mapreservationvehicle set activation_status=0 where veh_id='".$vehicle_list[$v]."' and rf_id=".$rfid[$r]);
				
				}

			}*/
			 
				
 
	 		return true;
		 
		  
	}


 }
 	

?>