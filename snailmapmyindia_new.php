<?php
session_start();
include_once('config.php');

$id_roles=$_SESSION['id_roles'];

$id = isset($_GET['vid']) ? $_GET['vid'] : "";
$requestTime = isset($_GET['requestTime']) ? $_GET['requestTime'] : "";
$branchid = isset($_GET['branchid']) ? $_GET['branchid'] : "";
//echo "<pre>";print_r($_GET);die;

function dateDifferenceSecond($date1, $date2)
{ 
	$datetime1 = strtotime($date1);
	$datetime2 = strtotime( $date2);
	$interval  = abs($datetime2 - $datetime1);
	$minutes   = $interval / 60;
	return $minutes*(60);

}

function dateDifference($date1, $date2)
{ 
	$datetime1 = strtotime($date1);
	$datetime2 = strtotime( $date2);
	$interval  = abs($datetime2 - $datetime1);
	$minutes   = $interval / 60;
	return $minutes;

}

function minDifferenceForJourney($Seconds)
{ 
	$mins=$Seconds/60;
	$diff = $mins;
	$hour = $diff/60; // in day

	 $hourFix = floor($hour);
	 $hourPen = $hour - $hourFix;
	 if($hourPen > 0)
	 {
		  $min = $hourPen*(60); // in hour (1 hour = 60 min)
		  $minFix = floor($min);
		  $minPen = $min - $minFix;
		  if($minPen > 0)
		  {
			  $sec = $minPen*(60); // in sec (1 min = 60 sec)
			  $secFix = floor($sec);
		  }
	 }

	 if($hourFix > 0)
	 {
		 $str.= $hourFix.":";
	 }
	 else
	 {
		 $str.= "0:";
	 }
	
	 if($minFix > 0)
	 {
		 $str.= $minFix.":";
	 }
	 else
	 {
		 $str.= "0:";
	 }
	
	 if($secFix > 0)
	 {
		 $str.= $secFix;
	 }
	 else
	 {
		 $str.= "0";
	 }
	 return $str;

}

function calc_distance($point_array)
{
	$radius      = 6372.797;      // Earth's radius (miles)
	$pi          = 3.1415926;
	$deg_per_rad = 57.29578;  // Number of degrees/radian (for conversion)
	$temp_distance = 0;
	$distance	= 0;
	
	//echo "<pre>";print_r($point_array);die;
			
	$DistanceIncreaseInReports=0;
	
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

if($id_roles==1)
{
	$login_id = "login_id='".$branchid."' ";
} else {
	$login_id = "login_id='".$branchid."' ";
}

if($id!="" && $requestTime !="")
{
	$rows = array();
		
	$getEmpTripData = select_query("select * from $db_name.technicians_tracking where tech_id='".$id."' and Date_of_journey='".$requestTime."'
	and is_active='1' and $login_id group by gps_latitude,gps_longitude order by location_time");
	
	//echo "<pre>";print_r($getEmpTripData);die;	
	
	$data=array();
	$distance = 0;
	$totalDistance = 0;
	$Duration = 0;
	if(count($getEmpTripData)>0)
	{
		for($final=0;$final<count($getEmpTripData);$final++)
		{
			$PointArray[0]['lat']  = $getEmpTripData[$final]['gps_latitude'];
			$PointArray[0]['long'] = $getEmpTripData[$final]['gps_longitude'];
	
			$PointArray[1]['lat']  = $getEmpTripData[$final - 1]['gps_latitude'];
			$PointArray[1]['long'] = $getEmpTripData[$final - 1]['gps_longitude'];
			
			if ($PointArray[1]['lat'] != "") {
	
				$distance = calc_distance($PointArray);
				//echo "Distance -".$distance;echo "<br/>";
				//echo "Lat -".$getEmpTripData[$final]['gps_latitude']."Long -".$getEmpTripData[$final]['gps_longitude'];echo "<br/>";
				
				//$Duration= dateDifference($getEmpTripData[$final]['location_time'],$getEmpTripData[$final-1]['location_time']);  
				//echo "Duration -".$Duration;echo "<br/>";
				
				//$Duration=$Duration;
				//echo "Duration Plus-".$Duration;echo "<br/>";		echo "<br/>";	
				###### Sum of distance############
				/*if ($distance >= $Duration ) {
					 $distance = 0;
					// $totalDistance = $totalDistance + $distance;					 
				} else {*/
					//$totalDistance = $totalDistance + $distance;
					$arr=array (
								'id' => $getEmpTripData[$final]['id'],
								'emp_id' => $getEmpTripData[$final]['tech_id'],
								'phone_no' => $getEmpTripData[$final]['phone_no'],
								'job_location' => $getEmpTripData[$final]['job_location'],
								'gps_latitude' => $getEmpTripData[$final]['gps_latitude'],
								'gps_longitude' => $getEmpTripData[$final]['gps_longitude'],
								'location_time' => $getEmpTripData[$final]['location_time'],
								'Date_of_journey' => $getEmpTripData[$final]['Date_of_journey'],
								) ;
					array_push($data,$arr);
				//}
			}
		}
		//echo "<pre>";print_r($data);die;
	}
	else
	{
		echo "There is no Tracking data!";die;
	}
	
	
	if(count($data)>0)
	{
			
		$latitudearray="var latitudeArr = [";
		$longitudearray="var longitudeArr = [";
		$combo="var pp = [";
		
		for($latlogShow=0;$latlogShow<count($data);$latlogShow++)
		{
			$latitudearray.=$data[$latlogShow]["gps_latitude"].",";
			$longitudearray.=$data[$latlogShow]["gps_longitude"].",";
			$combo.="[".$data[$latlogShow]["gps_latitude"].",".$data[$latlogShow]["gps_longitude"]."],";
		}
		
		$latitudearray=substr($latitudearray,0,strlen($latitudearray)-1);
		
		$latitudearray.="];";
		
		$longitudearray=substr($longitudearray,0,strlen($longitudearray)-1);
		
		$longitudearray.="];";
		
		$combo=substr($combo,0,strlen($combo)-1);
		
		$combo.="];";
		
		/*for($latlogShow=0;$latlogShow<count($data);$latlogShow++)
		{
			$longitudearray.=$data[$latlogShow]["gps_longitude"].",";
		
		}
		$longitudearray=substr($longitudearray,0,strlen($longitudearray)-1);
		
		$longitudearray.="];";
		*/
		
			
		
		/*for($latlogShow=0;$latlogShow<count($data);$latlogShow++)
		{
			$combo.="[".$data[$latlogShow]["gps_latitude"].",".$data[$latlogShow]["gps_longitude"]."],";
		
		}
		$combo=substr($combo,0,strlen($combo)-1);
		$combo.="];";*/
	
	}
	
 

 }
 
 
 ?>
 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
    <title>Trip on Map</title>
    <link rel="icon" href="http://mapmyindia.com/images/favicon.ico" type="image/x-icon" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        html {
            height: 100%;
        }
        body {
            height: 100%;
            font-family:Verdana,sans-serif,Arial;
            color:#000;
            margin: 0;
            font-size:14px;
            padding: 0;
        }
        #map{
            position: absolute;
            left: 10px; top: 10px;
            right: 2px; bottom: 2px;
            border: 1px solid #cccccc; }
         
    </style>
    <!--put your map api javascript url with key here-->
    <script src="https://apis.mapmyindia.com/advancedmaps/v1/65z8s3bowtwlxrko6gy4kl2ic5dv5kca/map_load?v=0.1"></script>
    <script src="../js/leaflet.polylineDecorator.js"></script>
    <script src="../js/leaflet.rotatedMarker.js"></script>
</head>
<body>
   
   
    <div id="map"></div>
    <script>
        var map = null;
        var poly = [];
            var marker = [];
        var decorator;
        var line;
        var center = new L.LatLng(28.691600800,77.057022095);
        var interval = 0;


       
        

        <?
       
echo $latitudearray;
echo $longitudearray;
echo $combo;
       
        ?>


        window.onload = function () {

            map = new MapmyIndia.Map('map', {
                center: center,
                editable: true,
                zoomControl: true,
                hybrid: true
            });

            //draw polyline
            //drawCarMarkerOnPolyline();

            mapmyindia_multiple_markers();
<?if(count($getEmpTripData)>5)
            {?>

            drawArrowOnPolyline();
            <?}?>
        }

 function mapmyindia_multiple_markers() {
                mapmyindia_removeMarker();
                for (var i = 0; i < latitudeArr.length; i++) {
                    var postion = new L.LatLng(latitudeArr[i], longitudeArr[i]);/*WGS location object*/
                    var icon = L.icon({iconUrl: 'https://gtrac.in/newtracking/' + '/images/running.PNG', iconRetinaUrl: '', iconSize: [10, 10], popupAnchor: [-3, -15]});
               
                    marker.push(addMarker(postion, icon, ""));
                }
                mapmyindia_fit_markers_into_bound();
            }
            function addMarker(position, icon, title, draggable) {
                /* position must be instance of L.LatLng that replaces current WGS position of this object. Will always return current WGS position.*/
                var event_div = document.getElementById("event-log");
                if (icon==''){
                    var mk = new L.Marker(position,{draggable: draggable,title:title});/*marker with a default icon and optional param draggable, title */
                    mk.bindPopup(title);
                }
                else{
                    var mk = new L.Marker(position,{icon: icon, draggable: draggable, title:title});/*marker with a custom icon */
                    mk.bindPopup(title);
                }
                map.addLayer(mk);/*add the marker to the map*/
                /* marker events:*/
                mk.on("click", function(e) {
                    //event_div.innerHTML = "Marker clicked<br>"+event_div.innerHTML ;
                });
               
                return mk;
            }
function mapmyindia_fit_markers_into_bound() {
                var maxlat = Math.max.apply(null, latitudeArr);
                var maxlon = Math.max.apply(null, longitudeArr);
                var minlat = Math.min.apply(null, latitudeArr);
                var minlon = Math.min.apply(null, longitudeArr);
                var southWest = L.latLng(maxlat, maxlon);/*south-west WGS location object*/
                var northEast = L.latLng(minlat, minlon);/*north-east WGS location object*/
                var bounds = L.latLngBounds(southWest, northEast);/*This class represents bounds on the Earth sphere, defined by south-west and north-east corners*/
                map.fitBounds(bounds);/*Sets the center map position and level so that all markers is the area of the map that is displayed in the map area*/
            }
            function mapmyindia_removeMarker() {
                var markerlength = marker.length;
                if (markerlength > 0) {
                    for (var i = 0; i < markerlength; i++) {
                        map.removeLayer(marker[i]);/* deletion of marker object from the map */
                    }
                }
                delete marker;
                marker = [];
                //document.getElementById("event-log").innerHTML = "";
               
            }

        function drawCarMarkerOnPolyline() {
            removePolyline();
            var offset = 0; //intial offset value
            var w = 14, h = 33;
            //Polyline css
            var linecss = {
                color: '#234FB6',
                weight: 3,
                opacity: 1
            };
            line = L.polyline(pp, linecss).addTo(map); //add polyline on map
            decorator = L.polylineDecorator(line).addTo(map); //create a polyline decorator instance.

            //offset and repeat can be each defined as a number,in pixels,or in percentage of the line's length,as a string
            interval = window.setInterval(function () {
                decorator.setPatterns([{
                        offset: offset + '%', //Offset value for first pattern symbol,from the start point of the line. Default is 0.
                        repeat: 0, //repeat pattern at every x offset. 0 means no repeat.
                        //Symbol type.
                        symbol: L.Symbol.marker({
                            rotate: true, //move marker along the line. false value may cause the custom marker to shift away from a curved polyline. Default is false.
                            markerOptions: {
                                icon: L.icon({
                                    iconUrl: 'images/truck.png',
                                    iconAnchor: [w / 2, h / 2], //Handles the marker anchor point. For a correct anchor point [ImageWidth/2,ImageHeight/2]
                                    iconSize: [14, 33]
                                })
                            }
                        })
                    }
                ]);
                if ((offset += 0.03) > 100) //Sets offset. Smaller the value smoother the movement.
                    offset = 0;
            }, 10); //Time in ms. Increases/decreases the speed of the marker movement on decrement/increment of 1 respectively. values should not be less than 1.
            poly.push(line);
            poly.push(decorator);
            map.fitBounds(line.getBounds());
        }
        function drawArrowOnPolyline() {
            removePolyline();
            var offset = 0; //intial offset value

            //Polyline css
            var linecss = {
                color: '#fd4000',
                weight: 3,
                opacity: 1
            };
            line = L.polyline(pp, linecss).addTo(map); //add polyline on map
            decorator = L.polylineDecorator(line).addTo(map); //create a polyline decorator instance.
            //offset and repeat can be each defined as a number,in pixels,or in percentage of the line's length,as a string
            interval = window.setInterval(function () {
                decorator.setPatterns([{
                        offset: offset + "%", //Start first marker from x offset.
                        repeat: 0, //repeat market at every x offset. 0 means no repeat.
                        symbol: L.Symbol.arrowHead({
                            pixelSize: 15, //Size of arrow image
                            headAngle: 50, //Increases/decreases arrow angel. Default is 60.
                            polygon: true, //if set to false an arrow is added else a triangle shape arrow is added. Default is true.
                            pathOptions: {
                                color: '#303030', //arrow color
                                fillOpacity: 0, //0 for no fill
                                weight: 4 // arrow line width
                            }
                        })
                    }
                ]);
                if ((offset += 0.03) > 100) //Sets offset. Smaller the value smoother the movement.
                    offset = 0;
            }, 10); //Time in ms. Increases/decreases the speed of the marker movement on decrement/increment of 1 respectively. values should not be less than 1.
            poly.push(line);
            poly.push(decorator);
            map.fitBounds(line.getBounds());
        }
         

        var removePolyline = function () {
            var polylength = poly.length;
            if (polylength > 0) {
                for (var i = 0; i < polylength; i++) {
                    if (poly[i] !== undefined) {
                        map.removeLayer(poly[i]);
                    }
                }
                poly = new Array();
                window.clearInterval(interval);
            }
        }
    </script>
</body>
<div style="padding:6px 12px 6px 38px;color: #777; font-size: 12px; line-height: 22px;" id="event-log"></div>
</html>