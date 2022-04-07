<?php

class GeoChecker{

var $distance;
var $delta_lon;
	# Spherical Law of Cosines
			function calculateDistanceBetweenLatLong($lat1, $lon1, $lat2, $lon2) {

			//Old Code

/*
			  $this->delta_lon = $lon_2 - $lon_1;

			  $this->distance  = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($this->delta_lon)) ;
			  $this->distance  = acos($this->distance);
			  $this->distance  = rad2deg($this->distance);
			  $this->distance  = $this->distance * 60 * 1.1515;
			  echo "Dist ".$this->distance."Dist ";
			  $this->distance  = round($this->distance, 4);

*/

//New Function By Vineet Patidar

$theta = $lon1 - $lon2; 
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
  $dist = acos($dist); 
  $dist = rad2deg($dist); 
  $miles = $dist * 60 * 1.1515;
  $miles  = round($miles, 4);
  //for unit conversation
 /*
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344); 
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }

*/
			  return $miles;  
              
			}

}







/* exmple*/
/*
$lat_1 = "28.703239441";
$lon_1 = "77.132606506";
$lat_2 = "28.634229660";
$lon_2 = "77.109664917";

$obj_GeoChecker=new GeoChecker();

echo $slc_distance = $obj_GeoChecker->calculateDistanceBetweenLatLong($lat_1, $lon_1, $lat_2, $lon_2);
*/
?>