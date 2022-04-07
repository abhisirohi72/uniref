<?php
class master{
var $data;
	function getVehilceData($UserId="",$todo="",$showBYkeyword=""){
        
			if($UserId==""){
				$whereCondition= " 1=2 ";
			}
			else{
				$whereCondition= " sys_user_id = ".$UserId;
			}


			
 
	
			if($UserId=="3795" && $todo=="mail"){
				 
			$ExtraQuery= " and id not in ( select services.id as id from services
												join latest_telemetry on latest_telemetry.sys_service_id=services.id
												join devices on devices.id=services.sys_device_id
												join mobile_simcards on mobile_simcards.id=devices.sys_simcard
												 where services.id in 
																(select distinct sys_service_id from group_services where active=true and sys_group_id in (
																select sys_group_id from group_users where sys_user_id=(".$UserId."))) and adddate(latest_telemetry.gps_time,INTERVAL 19800 second)< adddate(now(),interval -240 hour))";

																 
			}
			else
		{
				$ExtraQuery="";
				$todo="";
		}

		if($showBYkeyword=="")
			{
				$keyword="";
			}
			else
			{
				  $keyword= " and route_no  = '".$showBYkeyword."' ";
				// echo "select id,veh_reg,veh_icon_1,veh_destination from services where id in 
				//(select distinct sys_service_id from group_services where active=true and sys_group_id in (
				//select sys_group_id from group_users where ".$whereCondition." ))".$ExtraQuery .$keyword;
 
			}

if($UserId!="3658" ){
		$this->data=select_query("select id,veh_reg,veh_icon_1,veh_destination from services where id in 
				(select distinct sys_service_id from group_services where active=true and sys_group_id in (
				select sys_group_id from group_users where ".$whereCondition." ))".$ExtraQuery .$keyword);

}
else
		{

	$this->data=select_query("select id,veh_reg,veh_icon_1,veh_destination from services where id in (select distinct(veh_id) from `vehicle_container` left join group_services on `vehicle_container`.veh_id= group_services.sys_service_id where `Show`=1 and group_services.active=true)");
		}
			return $this->data;



	}

	function GetVehProximityHonda($lat,$lng,$vehListId=""){
			
			if($lat=="" or $lng==""){
				//echo "Please Pass Lat Long as Parametere";
				//exit;
				return;
			}        
			if($vehListId==""){   
				//echo "Vehilce List Can not be Blank in this function";
				return;
			}

		$this->data="";     //geo_postcode as postcode,
		$this->data=select_query("select services.id as id,services.veh_reg as veh_reg,round(gps_speed*1.609,0) as speed,
		latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,( 3959 * acos( cos( radians(".$lat.") ) * cos( radians( latest_telemetry.gps_latitude ) ) * cos( radians( latest_telemetry.gps_longitude ) - radians(".$lng.") ) + sin( radians(".$lat.") ) * sin( radians( latest_telemetry.gps_latitude ) ) ) )  AS distance
		from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id  where services.id in (".$vehListId.") order by distance asc");
		return $this->data;
	}

 

function getloading_safeway($vehListId=""){
			if($vehListId==""){   
				//echo "Vehilce List Can not be Blank in this function";
				return;
			}

		$this->data="";  
		$this->data=select_query("select  services.id as id,services.veh_reg,services.veh_driver_name,services.veh_phone_number,services.veh_startloc,services.card_number,services.veh_departuredate,services.veh_destination,services.veh_arrivaldate,
						latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,veh_driver_name as driver,veh_phone_number as phone,veh_destination as destination, 
						tel_temperature  as 'temperature',latest_telemetry.tel_voltage,case when tel_poweralert=true then true else false end as poweroff
						from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id where services.id in (".$vehListId.") ");


		return $this->data;
	}


	 function getServiceId($Veh_reg)
			{

				if($Veh_reg!="")
				{
				$this->data=select_query("select services.id,services.veh_reg,devices.imei from services left join devices on services.sys_device_id= devices.id where veh_reg ='".$Veh_reg."'");
				//return "select services.id,services.veh_reg,devices.sys_device_id,devices.imei from services left join devices on services.sys_device_id= devices.id where veh_reg ='".$Veh_reg."'";
				return $this->data;				 
				}
				else
				{
					return;
				}
		
			}



	 function CheckUserByVehid($serveice_id,$userid)
	{ 
		 if($serveice_id!="")
				{

				$this->data=select_query("select * from `group_users` left join `group_services` on `group_users`.sys_group_id
					=`group_services`.sys_group_id where sys_user_id=".$userid." and sys_service_id=".$serveice_id);

					 return $this->data;				 
				}
				else
				{
					return;
				}
		
	}

 function getuserVloc($Mobilenum)
	{

				if($Mobilenum!="")
				{
				$this->data=select_query("select * from `vloc_user` where phone_num like '%".$Mobilenum."%'");
				return $this->data;				 
				}
				else
				{
					return;
				}
		
	}
	
			 function getVehicle_type($Veh_id)
			{

				if($Veh_id!="")
				{
				$this->data=select_query("select veh_type,veh_reg from services where id ='".$Veh_id."'");
				return $this->data;				 
				}
				else
				{
					return;
				}
		
			}
			function Get_group_id($user_id)
			{

				if($user_id!="")
				{
				$this->data=select_query("select sys_group_id from group_users where sys_user_id='".$user_id."'");
				return $this->data;				 
				}
				else
				{
					return;
				}
		
			}
	//getVehilceLatestData_doaba

	function getVehilceLatestDataForProximity($lat,$lng,$vehListId=""){
			
			if($lat=="" or $lng==""){
				echo "Please Pass Lat Long as Parametere";
				exit;
			}        
			if($vehListId==""){   
				//echo "Vehilce List Can not be Blank in this function";
				return;
			}

		$this->data="";     //geo_postcode as postcode,
		$this->data=select_query("select services.id as id,services.veh_reg as veh_reg,round(gps_speed*1.609,0) as speed,round(gps_orientation,1) as bearing,
		veh_icon_1 as icon,gps_time as lastcontact,
		case when tel_ignition=true and services.veh_type=0 then true else false end as aconoff ,
		geo_street as street,gps_fix,
		geo_town as town,geo_country as country,veh_reg as reg,
		latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,( 3959 * acos( cos( radians(".$lat.") ) * cos( radians( latest_telemetry.gps_latitude ) ) * cos( radians( latest_telemetry.gps_longitude ) - radians(".$lng.") ) + sin( radians(".$lat.") ) * sin( radians( latest_telemetry.gps_latitude ) ) ) )  AS distance
		from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id  where services.id in (".$vehListId.") order by distance asc");
		return $this->data;
	}





	function getPoiData($UserId=""){

			if($UserId==""){
				$whereCondition= " 1=1 ";
			}
			else{
				$whereCondition= " sys_user_id = ".$UserId;
			}
	// status=1 and
			$this->data=select_query("select id,name,icon,typeId,gps_radius,gps_latitude,gps_longitude,geo_street,
			geo_town,geo_country,geo_postcode from pois where id in (select distinct sys_poi_id from group_pois where active=true and sys_group_id in (select sys_group_id from group_users where ".$whereCondition." ) )  order by name ASC");
			return $this->data;
	}

		function getVehilceLatestData($vehListId=""){
			if($vehListId==""){   
				//echo "Vehilce List Can not be Blank in this function";
				return;
			}

		$this->data="";     //geo_postcode as postcode,


			// Hack Added By Anuj sir Specially for Demo User to display Lastest Vehciles
			// Hack Added By Gary Fro OTPC Account Id 3111
			if($_SESSION['UserName']=="demotest1" or $_SESSION['UserName']=="demotravel"){

						
						/*$this->data=select_query("select services.id as id,services.veh_reg as veh_reg,round(gps_speed*1.609,0) as speed,round(gps_orientation,1) as bearing,
						veh_icon_1 as icon,gps_time as lastcontact,
						case when tel_ignition=true then true else false end as aconoff ,
						geo_street as street,gps_fix,veh_destination as destination,
						geo_town as town,geo_country as country,veh_reg as reg,
						latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,veh_driver_name as driver,veh_phone_number as phone,veh_destination as destination, 
						(tel_temperature*-1) as 'temperature',case when tel_poweralert=true then true else false end as poweroff
						from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id  where services.id in (".$vehListId.") and date_add(gps_time,interval 330 minute)>date_add(now(),interval -10 minute) order by gps_time desc");
						//services.driver_contact_no,services.thana_contact_no,services.thana_name,services.veh_type_name
						*/


						$this->data=select_query("select services.sys_device_id as sys_device_id,services.route_no as route_no, services.veh_type_name as  veh_type_name,services.veh_extfeature as veh_extfeature,services.id as id,services.veh_reg as veh_reg,round(gps_speed*1.609,0) as speed,round(gps_orientation,1)as bearing,tel_odometer,
						veh_icon_1 as icon,gps_time as lastcontact,case when tel_panic=true then true else false end as tel_panic,
						case when tel_ignition=true and services.veh_type=0 then true else false end as aconoff  ,
						geo_street as street,gps_fix,case when tel_input_1=true then true else false end as tel_input_1,case when tel_input_0=true then true else false end as tel_input_0,case when tel_input_2=true then true else false end as tel_input_2,veh_destination as destination,
						geo_town as town,geo_country as country,veh_reg as reg,
						latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,latest_telemetry.tel_poweralert as tel_poweralert,veh_driver_name as driver,veh_phone_number as phone,veh_destination as destination, 
						tel_temperature  as 'temperature',latest_telemetry.tel_voltage,case when tel_poweralert=true then true else false end as poweroff
						from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id join devices on devices.id=services.sys_device_id where services.id in (".$vehListId.") and date_add(gps_time,interval 330 minute)>date_add(now(),interval -10 minute) order by gps_time desc");



			}
			elseif($_SESSION['UserId']=="3096" ){
				// or $_SESSION['UserId']=="3111"
					 $this->data=select_query("select services.sys_device_id as sys_device_id,services.veh_type_name as  veh_type_name,services.id as id,services.veh_reg as veh_reg,round(gps_speed*1.609,0) as speed,round(gps_orientation,1)as bearing,tel_odometer,
						veh_icon_1 as icon,gps_time as lastcontact,case when tel_panic=true then true else false end as tel_panic,
						case when tel_ignition=true and services.veh_type=0 then true else false end as aconoff  ,
						geo_street as street,gps_fix,case when tel_input_1=true then true else false end as tel_input_1,case when tel_input_0=true then true else false end as tel_input_0,case when tel_input_2=true then true else false end as tel_input_2,services.card_number as card_number,
						geo_town as town,geo_country as country,veh_reg as reg,
						latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,latest_telemetry.tel_poweralert as tel_poweralert,veh_driver_name as driver,veh_phone_number as phone,veh_destination as destination,proximity as proximity,
						(tel_temperature*-1) as 'temperature',latest_telemetry.tel_voltage,case when tel_poweralert=true then true else false end as poweroff
						from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id join devices on devices.id=services.sys_device_id where services.id in (".$vehListId.") and date_add(gps_time,interval 330 minute)>date_add(now(),interval -120 hour) order by gps_time desc"); 

						/*$this->data=select_query("select  services.veh_extfeature as veh_extfeature,services.id as id,services.veh_reg as veh_reg,round(gps_speed*1.609,0) as speed,round(gps_orientation,1) as bearing,
						veh_icon_1 as icon,gps_time as lastcontact,
						case when tel_ignition=true then true else false end as aconoff ,
						geo_street as street,gps_fix,
						geo_town as town,geo_country as country,veh_reg as reg,
						latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,veh_driver_name as driver,veh_phone_number as phone,veh_destination as destination,proximity as proximity,
						(tel_temperature*-1) as 'temperature',case when tel_poweralert=true then true else false end as poweroff
						from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id  where services.id in (".$vehListId.")   order by gps_time desc");*/

			}
			elseif($_SESSION['UserId']=="3968" || $_SESSION['UserId']=="3969"  ){
				// or $_SESSION['UserId']=="3111"
				//3968,3969
					 $this->data=select_query("select services.sys_device_id as sys_device_id,services.veh_type_name as  veh_type_name,services.id as id,services.veh_reg as veh_reg,round(gps_speed*1.609,0) as speed,round(gps_orientation,1)as bearing,tel_odometer,
						veh_icon_1 as icon,gps_time as lastcontact,case when tel_panic=true then true else false end as tel_panic,
						case when tel_ignition=true and services.veh_type=0 then true else false end as aconoff  ,
						geo_street as street,gps_fix,case when tel_input_1=true then true else false end as tel_input_1,case when tel_input_0=true then true else false end as tel_input_0,case when tel_input_2=true then true else false end as tel_input_2,
						geo_town as town,geo_country as country,veh_reg as reg,
						latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,latest_telemetry.tel_poweralert as tel_poweralert,veh_driver_name as driver,veh_phone_number as phone,veh_destination as destination,proximity as proximity,
						(tel_temperature*-1) as 'temperature',latest_telemetry.tel_voltage,case when tel_poweralert=true then true else false end as poweroff
						from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id join devices on devices.id=services.sys_device_id where services.id in (".$vehListId.") and date_add(gps_time,interval 330 minute)>date_add(now(),interval -168 hour) order by gps_time desc"); 

						/*$this->data=select_query("select  services.veh_extfeature as veh_extfeature,services.id as id,services.veh_reg as veh_reg,round(gps_speed*1.609,0) as speed,round(gps_orientation,1) as bearing,
						veh_icon_1 as icon,gps_time as lastcontact,
						case when tel_ignition=true then true else false end as aconoff ,
						geo_street as street,gps_fix,
						geo_town as town,geo_country as country,veh_reg as reg,
						latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,veh_driver_name as driver,veh_phone_number as phone,veh_destination as destination,proximity as proximity,
						(tel_temperature*-1) as 'temperature',case when tel_poweralert=true then true else false end as poweroff
						from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id  where services.id in (".$vehListId.")   order by gps_time desc");*/

			}
			elseif($_SESSION['UserId']=="4318"){
				// or $_SESSION['UserId']=="3111"
				//3968,3969
					 $this->data=select_query("select services.sys_device_id as sys_device_id,services.veh_type_name as  veh_type_name,services.id as id,services.veh_reg as veh_reg,round(gps_speed*1.609,0) as speed,round(gps_orientation,1)as bearing,tel_odometer,
						veh_icon_1 as icon,gps_time as lastcontact,case when tel_panic=true then true else false end as tel_panic,
						case when tel_ignition=true and services.veh_type=0 then true else false end as aconoff  ,
						geo_street as street,gps_fix,case when tel_input_1=true then true else false end as tel_input_1,case when tel_input_0=true then true else false end as tel_input_0,case when tel_input_2=true then true else false end as tel_input_2,
						geo_town as town,geo_country as country,veh_reg as reg,
						latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,latest_telemetry.tel_poweralert as tel_poweralert,veh_driver_name as driver,veh_phone_number as phone,veh_destination as destination,proximity as proximity,
						(tel_temperature*-1) as 'temperature',latest_telemetry.tel_voltage,case when tel_poweralert=true then true else false end as poweroff
						from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id join devices on devices.id=services.sys_device_id where services.id in (".$vehListId.") and date_add(gps_time,interval 330 minute)>date_add(now(),interval -240 hour) order by gps_time desc"); 

						/*$this->data=select_query("select  services.veh_extfeature as veh_extfeature,services.id as id,services.veh_reg as veh_reg,round(gps_speed*1.609,0) as speed,round(gps_orientation,1) as bearing,
						veh_icon_1 as icon,gps_time as lastcontact,
						case when tel_ignition=true then true else false end as aconoff ,
						geo_street as street,gps_fix,
						geo_town as town,geo_country as country,veh_reg as reg,
						latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,veh_driver_name as driver,veh_phone_number as phone,veh_destination as destination,proximity as proximity,
						(tel_temperature*-1) as 'temperature',case when tel_poweralert=true then true else false end as poweroff
						from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id  where services.id in (".$vehListId.")   order by gps_time desc");*/

			}
			elseif($_SESSION['UserId']=="30601" ){
				// or $_SESSION['UserId']=="3111"
						$this->data=select_query("select services.sys_device_id as sys_device_id, services.veh_type_name as  veh_type_name,services.veh_extfeature as veh_extfeature, services.id as id,services.veh_reg as veh_reg,services.veh_status as veh_status, services.veh_endloc as veh_endloc, services.veh_startloc as veh_startloc,services.card_number as card_number,services.veh_arrivaldate as veh_arrivaldate, services.veh_departuredate as veh_departuredate, services.veh_destination as veh_destination, services.veh_phone_number as veh_phone_number, services.veh_driver_name as veh_driver_name,round(gps_speed*1.609,0) as speed,round(gps_orientation,1)as bearing,tel_odometer,
		veh_icon_1 as icon,gps_time as lastcontact,case when tel_panic=true then true else false end as tel_panic,
		case when tel_ignition=true and services.veh_type=0 then true else false end as aconoff  ,
		geo_street as street,gps_fix,case when tel_input_1=true then true else false end as tel_input_1,case when tel_input_0=true then true else false end as tel_input_0,case when tel_input_2=true then true else false end as tel_input_2,
		geo_town as town,geo_country as country,veh_reg as reg,services.disel_datetime as disel_datetime,services.riwari_in_datetime as riwari_in_datetime, services.riwari_out_datetime as riwari_out_datetime, 
		latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,latest_telemetry.tel_poweralert as tel_poweralert,veh_driver_name as driver,veh_phone_number as phone,veh_destination as destination,proximity as proximity,
		tel_temperature  as 'temperature',latest_telemetry.tel_voltage,case when tel_poweralert=true then true else false end as poweroff
		from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id join devices on devices.id=services.sys_device_id  where services.id in (".$vehListId.") and round(gps_speed*1.609,0)>5 order by gps_time desc");

			}
			
			else{


		/*$this->data=select_query("select  services.id as id,services.veh_reg as veh_reg,services.veh_status as veh_status, services.veh_endloc as veh_endloc, services.veh_startloc as veh_startloc,services.card_number as card_number,services.veh_arrivaldate as veh_arrivaldate, services.veh_departuredate as veh_departuredate, services.veh_destination as veh_destination, services.veh_phone_number as veh_phone_number, services.veh_driver_name as veh_driver_name,round(gps_speed*1.609,0) as speed,round(gps_orientation,1) as bearing,
		veh_icon_1 as icon,gps_time as lastcontact,
		case when tel_ignition=true then true else false end as aconoff ,
		geo_street as street,gps_fix,
		geo_town as town,geo_country as country,veh_reg as reg,
		latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,veh_driver_name as driver,veh_phone_number as phone,veh_destination as destination,proximity as proximity,
		(tel_temperature*-1) as 'temperature',case when tel_poweralert=true then true else false end as poweroff
		from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id  where services.id in (".$vehListId.") order by gps_time desc");*/
 if($_SESSION['UserId']=="3404" || $_SESSION['UserId']=="3165" || $_SESSION['UserId']=="3856"  || $_SESSION['UserId']=="3805"  || $_SESSION['UserId']=="3151"  || $_SESSION['UserId']=="4468"|| $_SESSION['UserId']=="3852")
				{
				$orderBy=" order by veh_reg asc";
	 //order by gps_time desc
				}
				else
				{
				$orderBy=" order by gps_time desc";
				}

		$this->data=select_query("select  services.sys_device_id as sys_device_id,services.route_no as route_no, services.veh_type_name as  veh_type_name,services.driver_contact_no,services.thana_contact_no,services.thana_name,services.veh_type_name,services.veh_extfeature as veh_extfeature,services.id as id,services.veh_reg as veh_reg,services.veh_status as veh_status, services.veh_endloc as veh_endloc, services.veh_startloc as veh_startloc,services.card_number as card_number, services.veh_extfeature as veh_extfeature,services.veh_arrivaldate as veh_arrivaldate, services.veh_departuredate as veh_departuredate, services.veh_destination as veh_destination,services.veh_chasis as veh_chasis, services.veh_phone_number as veh_phone_number, services.veh_driver_name as veh_driver_name,services.veh_chasis as veh_chasis,round(gps_speed*1.609,0) as speed,round(gps_orientation,1) as bearing,tel_odometer,
		veh_icon_1 as icon,gps_time as lastcontact,case when tel_panic=true then true else false end as tel_panic,
		case when tel_ignition=true and services.veh_type=0 then true else false end as aconoff ,
		geo_street as street,gps_fix,case when tel_input_1=true then true else false end as tel_input_1,case when tel_input_0=true then true else false end as tel_input_0,case when tel_input_2=true then true else false end as tel_input_2,tel_temperature,tel_fuel,
		geo_town as town,geo_country as country,veh_reg as reg,services.disel_datetime as disel_datetime,services.riwari_in_datetime as riwari_in_datetime, services.riwari_out_datetime as riwari_out_datetime, 
		latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,case when latest_telemetry.tel_poweralert=true then true else false end as tel_poweralert,veh_driver_name as driver,veh_phone_number as phone,veh_destination as destination,proximity as proximity,sys_renewal_due,
		tel_temperature  as 'temperature',latest_telemetry.tel_voltage,case when tel_poweralert=true then true else false end as poweroff
		from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id join devices on devices.id=services.sys_device_id where services.id in (".$vehListId.") ".$orderBy);

		   



			}
			// End of If For Demo Test


		return $this->data;
	}



	function getVehilceLatestData_doaba($vehListId="")
{

		if($vehListId=="")
		{   
			//echo "Vehilce List Can not be Blank in this function";
			return;
		}

		$this->data="";     //geo_postcode as postcode,

		$orderBy=" order by gps_time desc";
			 

		$this->data=select_query("select  services.id as id,services.veh_reg as veh_reg,services.veh_status as veh_status, services.veh_endloc as veh_endloc, services.veh_startloc as veh_startloc,services.card_number as card_number,services.veh_arrivaldate as veh_arrivaldate, services.veh_departuredate as veh_departuredate, services.veh_destination as veh_destination, services.veh_phone_number as veh_phone_number, services.veh_driver_name as veh_driver_name,round(gps_speed*1.609,0) as speed,round(gps_orientation,1) as bearing,
		veh_icon_1 as icon,gps_time as lastcontact,
		case when tel_ignition=true and services.veh_type=0 then true else false end as aconoff   ,
		geo_street as street,gps_fix,
		geo_town as town,geo_country as country,veh_reg as reg,services.disel_datetime as disel_datetime,services.riwari_in_datetime as riwari_in_datetime, services.riwari_out_datetime as riwari_out_datetime, 
		latest_telemetry.gps_latitude as lat,latest_telemetry.gps_longitude as lng,veh_driver_name as driver,veh_phone_number as phone,veh_destination as destination,proximity as proximity,
		tel_temperature  as 'temperature',latest_telemetry.tel_voltage,case when tel_poweralert=true then true else false end as poweroff
		from services left join latest_telemetry on services.id=latest_telemetry.sys_service_id  where services.id in (".$vehListId.") ".$orderBy);

		    


		return $this->data;
	}
    
	   function getPoiLatestData($poiListId=""){

			if($poiListId==""){
				//echo "Poi List Can be Blank in this function";
				//exit;
                return;
			}
		$this->data="";
		$this->data=select_query("select id,name,geo_street,gps_latitude,gps_longitude,geo_town,round(gps_radius,0) as gps_radius from pois where id  in (".$poiListId.")");
			return $this->data;
		}


		function registerVehiclesinSession(){

				if($_SESSION['vehId']==""){
			
				$data=$this->getVehilceData($_SESSION["UserId"]);
                //printarray($data);
					if(is_array($data)){
						foreach($data as $key=>$value){
							$_SESSION['vehId'].=$value['id'].",";
							$_SESSION['vehDetails'].=$value['id'].",".$value['veh_reg'].",".$value['veh_icon_1']."#$#";
						}
					
						$_SESSION['vehDetails']=substr($_SESSION['vehDetails'],0,strlen($_SESSION['vehDetails'])-3);
						$_SESSION['vehId']=substr($_SESSION['vehId'],0,strlen($_SESSION['vehId'])-1);
					 }
				}     
		}


		function Gettracking_lubrizol()
{
 
			 

		$this->data=select_query("select * from lubrizol_tracking");

		    


		return $this->data;
	}


	function SendSms($MobileNum,$MSG)
	{

		$ch = curl_init();
		$user="gary@itglobalconsulting.com:itgc@123";
		$receipientno=$MobileNum;
		$senderID="GTRACK";
		$msgtxt=$MSG;
		curl_setopt($ch,CURLOPT_URL,  "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
		$buffer = curl_exec($ch);
		if(empty ($buffer))
		{ echo " buffer is empty "; }
		else
		{ echo $buffer; }
		curl_close($ch);

		 

	}

	

		 function getServiceIdbyGrno($Gr_number)
			{

				if($Gr_number!="")
				{
				$this->data=select_query("select * from hero_tripwithgr_number left join hero_delaer_master on hero_delaer_master.customer_id=hero_tripwithgr_number.dealer_id  left join hero_transporter_master on hero_tripwithgr_number.transporter=hero_transporter_master.vendor_id where grnumber ='".$Gr_number."'");
				//return "select services.id,services.veh_reg,devices.sys_device_id,devices.imei from services left join devices on services.sys_device_id= devices.id where veh_reg ='".$Veh_reg."'";
				return $this->data;				 
				}
				else
				{
					return;
				}
		
			}
	

}





	
		

?>