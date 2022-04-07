<?php

class TripReport{

var $start_time;
var $end_time;
var $service_id;
var $group_id;
var $start_location_id;
var $end_location_id;
var $trip_id;
var $TempArray;

var $Row;
var $FinalTripReport;

	function CreatTripReport($dateStart,$dateEnd)
	{

 
		$data=select_query("select trip_report_telemetry.*,time_from_last_location,`order` from trip_report_telemetry left join trip_report_trip_locations on trip_report_telemetry.location_id=trip_report_trip_locations.location_id where sys_service_id=".$this->service_id." and sys_gps_time >= '".convertToGMT($dateStart,$_SESSION['TimeZoneDiff'])."' and sys_gps_time <= '".convertToGMT($dateEnd,$_SESSION['TimeZoneDiff'])."'  and trip_report_trip_locations.location_id in (select location_id from trip_report_trip_locations where trip_id=".$this->trip_id.") and trip_id=".$this->trip_id." order by id");


 
//		printarray($data);
		
		
		// Flag : Searhc Start Location Only : SO
		// Flasg : Search Start and Last Locaion : SE
		$flag="SO";

		$Row=-1;
		$order=0;

		$j=$start_location_id;

		$countData=count($data);



			for($i=0;$i<$countData;$i++){



				// Search For Start Location Only
				if($flag=="SO"){

					unset($TempArray);
					if($data[$i]["location_id"]==$this->start_location_id){

							$Row=$Row+1;
							$flag="SE";
							
							$order=$data[$i]["order"];
							$j=$data[$i]["location_id"];
							
							$TempArray[$Row][$data[$i]["location_id"]]= new TripSummary();
							$TempArray[$Row][$data[$i]["location_id"]]->location_id=$data[$i]["location_id"];
							$TempArray[$Row][$data[$i]["location_id"]]->reach_time=$data[$i]["sys_gps_time"];
							$TempArray[$Row][$data[$i]["location_id"]]->lat=$data[$i]["gps_lat"];
							$TempArray[$Row][$data[$i]["location_id"]]->lng=$data[$i]["gps_lng"];
							$TempArray[$Row][$data[$i]["location_id"]]->order=$data[$i]["order"];
							$TempArray[$Row][$data[$i]["location_id"]]->time_from_last_location=$data[$i]["time_from_last_location"];
							

					}



				}
				elseif($flag=="SE"){
				// Search either For Start or End
					
					if($data[$i]["location_id"]==$this->start_location_id){
						// Override Starting Point and Start Trip Position again

						$flag="SE";
							unset($TempArray[$Row][$j]);
							$j=$data[$i]["location_id"];
							$TempArray[$Row][$j]= new TripSummary();
							$TempArray[$Row][$j]->location_id=$data[$i]["location_id"];
							$TempArray[$Row][$j]->reach_time=$data[$i]["sys_gps_time"];
							$TempArray[$Row][$j]->lat=$data[$i]["gps_lat"];
							$TempArray[$Row][$j]->lng=$data[$i]["gps_lng"];
							$TempArray[$Row][$j]->order=$data[$i]["order"];
							$TempArray[$Row][$j]->time_from_last_location=$data[$i]["time_from_last_location"];


					}
					else if($data[$i]["location_id"]==$this->end_location_id){
						// End Trip Here

							$order=$data[$i]["order"];
							$j=$data[$i]["location_id"];

							$TempArray[$Row][$j]= new TripSummary();
							$TempArray[$Row][$j]->location_id=$data[$i]["location_id"];
							$TempArray[$Row][$j]->reach_time=$data[$i]["sys_gps_time"];
							$TempArray[$Row][$j]->lat=$data[$i]["gps_lat"];
							$TempArray[$Row][$j]->lng=$data[$i]["gps_lng"];
							$TempArray[$Row][$j]->order=$data[$i]["order"];
							$TempArray[$Row][$j]->time_from_last_location=$data[$i]["time_from_last_location"];


							$flag=="SO";

					}
					else{

							if($data[$i]["order"]>$order){

									$j=$data[$i]["location_id"];
									$TempArray[$Row][$j]= new TripSummary();
									$TempArray[$Row][$j]->location_id=$data[$i]["location_id"];
									$TempArray[$Row][$j]->reach_time=$data[$i]["sys_gps_time"];
									$TempArray[$Row][$j]->lat=$data[$i]["gps_lat"];
									$TempArray[$Row][$j]->lng=$data[$i]["gps_lng"];
									$TempArray[$Row][$j]->order=$data[$i]["order"];
									$TempArray[$Row][$j]->time_from_last_location=$data[$i]["time_from_last_location"];
							}
					}
				}
			}

return $TempArray;
	}

}

?>