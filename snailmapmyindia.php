<?php
session_start();
include_once('config.php');

$id = isset($_GET['vid']) ? $_GET['vid'] : "";

$dateStart = isset($_GET['startTime']) ? $_GET['startTime'] : "";

$dateEnd = isset($_GET['Endtime']) ? $_GET['Endtime'] : "";

//echo "<pre>";print_r($_GET);die;

if($id!="" && $dateStart !="" && $dateEnd!="")
{
	$rows = array(); 
		
	$getEmpTripData = select_query("select * from $employee_track.emp_today_tracking where trip_id='".$id."' and created_datetime>='".$dateStart."' and 
						created_datetime<='".$dateEnd."' and is_active='1' and login_id='".$_SESSION['user_id']."' order by id");
						
	//echo "<pre>";print_r($getEmpTripData);die;
	
	if(count($getEmpTripData)>0)
	{
	
		$latitudearray="var latitudeArr = [";
		for($latlogShow=0;$latlogShow<count($getEmpTripData);$latlogShow++)
		{
			$latitudearray.=$getEmpTripData[$latlogShow]["gps_latitude"].",";
	
		}
		$latitudearray=substr($latitudearray,0,strlen($latitudearray)-1);
											
		$latitudearray.="];";
	
		$longitudearray="var longitudeArr = [";
	
		for($latlogShow=0;$latlogShow<count($getEmpTripData);$latlogShow++)
		{
			$longitudearray.=$getEmpTripData[$latlogShow]["gps_longitude"].",";
	
		}
		$longitudearray=substr($longitudearray,0,strlen($longitudearray)-1);
		$longitudearray.="];";
	
	
		$combo="var pp = [";
		for($latlogShow=0;$latlogShow<count($getEmpTripData);$latlogShow++)
			{
			$combo.="[".$rows[$latlogShow]["gps_latitude"].",".$rows[$latlogShow]["gps_longitude"]."],";
		
			}
			$combo=substr($combo,0,strlen($combo)-1);
		$combo.="];";
		
	}
	else
	{
		echo "There is no Trip data!";die;
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


        
        /*var latitudeArr = [28.692766190,28.692766190,28.692792892,28.692699432,28.692440033,28.692155838,28.691877365,28.691600800,28.691446304,28.691215515,28.691051483,28.690843582,28.690544128,28.690229416,28.689990997,28.689779282,28.689540863,28.689277649,28.689069748,28.688846588,28.688426971,28.688282013,28.688106537,28.687891006,28.687740326,28.687931061,28.688100815,28.688533783,28.688695908,28.689069748,28.689249039,28.689424515,28.689664841,28.689847946,28.689941406,28.689994812,28.689994812,28.689994812,28.690080643,28.690271378,28.690544128,28.690896988,28.691295624,28.691629410,28.691936493,28.692199707,28.692445755,28.692724228,28.692882538,28.692821503,28.692766190,28.692766190,28.692684174,28.692684174];
            var longitudeArr = [77.058746338,77.058746338,77.058639526,77.058135986,77.057846069,77.057563782,77.057281494,77.057022095,77.056861877,77.056625366,77.056419373,77.056236267,77.055938721,77.055610657,77.055229187,77.054939270,77.054557800,77.054115295,77.053833008,77.053535461,77.053001404,77.052841187,77.052658081,77.052436829,77.052291870,77.052520752,77.052696228,77.053199768,77.053398132,77.053833008,77.054069519,77.054367065,77.054725647,77.055007935,77.055168152,77.055282593,77.055282593,77.055282593,77.055374146,77.055625916,77.055938721,77.056289673,77.056694031,77.057075500,77.057380676,77.057655334,77.057891846,77.058197021,77.058334351,77.058624268,77.058799744,77.058799744,77.058746338,77.058746338];

        var pp = [ [28.692766190,77.058746338], [28.692766190,77.058746338], [28.692792892,77.058639526], [28.692699432,77.058135986], [28.692440033,77.057846069], [28.692155838,77.057563782], [28.691877365,77.057281494], [28.691600800,77.057022095], [28.691446304,77.056861877], [28.691215515,77.056625366], [28.691051483,77.056419373], [28.690843582,77.056236267], [28.690544128,77.055938721], [28.690229416,77.055610657], [28.689990997,77.055229187], [28.689779282,77.054939270], [28.689540863,77.054557800], [28.689277649,77.054115295], [28.689069748,77.053833008], [28.688846588,77.053535461], [28.688426971,77.053001404], [28.688282013,77.052841187], [28.688106537,77.052658081], [28.687891006,77.052436829], [28.687740326,77.052291870], [28.687931061,77.052520752], [28.688100815,77.052696228], [28.688533783,77.053199768], [28.688695908,77.053398132], [28.689069748,77.053833008], [28.689249039,77.054069519], [28.689424515,77.054367065], [28.689664841,77.054725647], [28.689847946,77.055007935], [28.689941406,77.055168152], [28.689994812,77.055282593], [28.689994812,77.055282593], [28.689994812,77.055282593], [28.690080643,77.055374146], [28.690271378,77.055625916], [28.690544128,77.055938721], [28.690896988,77.056289673], [28.691295624,77.056694031], [28.691629410,77.057075500], [28.691936493,77.057380676], [28.692199707,77.057655334], [28.692445755,77.057891846], [28.692724228,77.058197021], [28.692882538,77.058334351], [28.692821503,77.058624268], [28.692766190,77.058799744], [28.692766190,77.058799744], [28.692684174,77.058746338], [28.692684174,77.058746338]];*/

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
<?if(count($rows)>5)
            {?>

            drawArrowOnPolyline();
            <?}?>
            mapmyindia_multiple_markers();
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