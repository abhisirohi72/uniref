<?php
class calculate_distance extends DbManager{
	var $start_date;
	var $end_date;
	var $veh_id;
	var $radius;
	var $pi;
	var $deg_per_rad;
	var $temp_distance;
	var $point_array;


	function calculate(){
			$this->start_date=convertToGMT($this->start_date,$_SESSION['TimeZoneDiff']);
			$this->end_date=convertToGMT($this->end_date,$_SESSION['TimeZoneDiff']);
			


							// Manage Database Years
//							$dbArray=$this->returnDbArray($this->start_date,$this->end_date,1);
							$dbArray=$this->returnDbArray($this->start_date,$this->end_date);
							// Manage Database


											for($countYear=0;$countYear<count($dbArray);$countYear++){

													// Updated sys_msg_type=1 from vimal on 05- Feb
													// Add "Or 7" by Vineet Patidar on 24 March 
													$newQry="select id,gps_time,geo_street,round(gps_speed*1.609,2) as gps_speed,geo_town,geo_country,gps_latitude,gps_longitude,round(gps_orientation,2)  as gps_orientation from ".$dbArray[$countYear].".telemetry where sys_service_id=".$this->veh_id." and gps_time>='".$this->start_date."' and gps_time<='".$this->end_date."' and (sys_msg_type=1 or sys_msg_type=7 )";
													$navData[]=select_query($newQry);
											}

									
									$data=array();
									for($countNavData=0;$countNavData<count($navData);$countNavData++){
											if(is_array($navData[$countNavData])){
											$data=array_merge($data,$navData[$countNavData]);
											}
									}


					   for($i=0;$i<count($data);$i++){
								$this->point_array[$i]['lat']=$data[$i]['gps_latitude'];
								$this->point_array[$i]['long']=$data[$i]['gps_longitude'];
								
					   }
					return $this->calc_distance($this->point_array);
	}


		function calc_distance($point_array)
		{
			$this->radius      = 6372.797;      // Earth's radius (miles)
			$this->pi          = 3.1415926;
			$this->deg_per_rad = 57.29578;  // Number of degrees/radian (for conversion)
			$this->temp_distance =0;
			$this->distance=0;

					/*$DistanceIncreaseInReports=5;
					if($_SESSION['UserId']=="3853" or $_SESSION['UserId']=="3964"  or $_SESSION['UserId']=="4008")
					{
						$DistanceIncreaseInReports=9;
					}
					elseif($_SESSION['UserId']=="3853")
					{
						$DistanceIncreaseInReports=5;
					}
					if($_SESSION['UserId']=="4019" or $_SESSION['UserId']=="4034"  or $_SESSION['UserId']=="4038"  or $_SESSION['UserId']=="4038"  or $_SESSION['UserId']=="4041" or $_SESSION['UserId']=="4058" or $_SESSION['UserId']=="4059" or $_SESSION['UserId']=="3957" or $_SESSION['UserId']=="4161" )
					{ 
						$DistanceIncreaseInReports=0;
					}*/
					
					$DistanceIncreaseInReports=$_SESSION['KM_for'];
					
					for($i=1;$i<count($point_array);$i++){
						
						$point1['lat']=$point_array[$i-1]['lat'];
						$point1['long']=$point_array[$i-1]['long'];

						$point2['lat']=$point_array[$i]['lat'];
						$point2['long']=$point_array[$i]['long'];
				
									$A = $point1['lat']/57.29577951; 
									$B = $point1['long']/57.29577951; 
									$C = $point2['lat']/57.29577951; 
									$D = $point2['long']/57.29577951; 
									//convert all to radians: degree/57.29577951 
									 
								   if ($A == $C && $B == $D ){ 
									 $dist = 0; 
								   } 
								   else if ( (sin($A)* sin($C)+ cos($A)* cos($C)* cos($B-$D)) > 1)
									{
									   
								   $dist = 3963.1* acos(1);// solved a prob I ran into.  I haven't fully analyzed it yet    

								    } 				 
								   else
									 { 
								
										$dist = 3963.1* acos(sin($A)*sin($C)+ cos($A)* cos($C)* cos($B-$D)); 
									}
							$distance=$dist;
							$temp_distance=$temp_distance+$distance;
					}

					//$temp_distance = ($temp_distance / 100) * 9;
			$DistanceInKm= round(($temp_distance*1.609344),2);

			$Perdistance = ($DistanceInKm / 100) * $DistanceIncreaseInReports;

			$Increaseddistance=$DistanceInKm + $Perdistance; 
			return round($Increaseddistance,2);
			// Returned using the units used for $radius.
		}

		function SetDistance($DistanceInKm)
		{
			 	
			$DistanceIncreaseInReports=$_SESSION['KM_for'];

			$Perdistance = ($DistanceInKm / 100) * $DistanceIncreaseInReports;

			$Increaseddistance=$DistanceInKm + $Perdistance; 
			return round($Increaseddistance,2);
			 
		}

} // end Class
			

?>

