<?php
class VehicleJourneySummary
{
var	$start_date;
var $CheckStartLocationforPoi;
var $start_local_time;
var $end_local_time;
var	$sys_msg_type;
var	$date;
var	$start_lat;
var	$start_long;
var	$start_street;
var	$start_town;
var	$start_country;
var	$start_poi;
	
var	$end_date;
var	$end_lat;
var	$end_long;
var	$end_street;
var	$end_town;
var	$end_country;
var	$end_poi;

var	$reported_max_speed;
var	$distance;
var	$idle_time;
var	$duration;
var	$start_postcode;
var	$end_postcode;
}


class VehicleAcSummary
{
var	$start_date;
var	$sys_msg_type;
var	$date;
var	$start_street;
var	$start_town;
var	$start_country;
var $start_id;

var	$end_date;
var $end_id;

var	$end_lat;
var	$start_lat;
var	$start_long;
var	$end_long;
var	$end_street;
var	$end_town;
var	$end_country;


var	$time_difference;
var	$duration;
var	$start_postcode;
var	$end_postcode;
}


class TripSummary
{
var $location_id;
var $reach_time;
var $lat;
var $lng;
var $order;
var $time_from_last_location;
}

class CustomizedJourney
{
var $totalJourney;
var $total_duration;
var $total_distance;
}

?>