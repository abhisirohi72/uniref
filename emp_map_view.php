<?php 
session_start();
error_reporting(0);
require("config.php");

$id_roles=$_SESSION['id_roles'];

$tech_Id = select_query("select id from $db_name.technicians_login_details where loginid='".$_SESSION['user_id']."' and is_active='1' group by id");
//echo "<pre>";print_r($tech_Id);die;
for($ecl=0;$ecl<count($tech_Id);$ecl++)
{
	$tech_currentId .= $tech_Id[$ecl]['id'].",";
}
$tech_currentId = substr($tech_currentId,0,strlen($tech_currentId)-1);

//$currentdate = date('Y-m-d', strtotime('-1 days'));
$currentdate = date('Y-m-d');

$get_tech_loc = select_query("SELECT * FROM $db_name.installer_attendence_tbl WHERE inst_id IN ($tech_currentId) and req_date='".$currentdate."'
and is_active='1' and login_id='".$_SESSION['user_id']."'");
		
//echo "<pre>";print_r($get_tech_loc);die;


$latitude=array();
$logitude=array();
$addr=array();
$id=array();
$emp=array();
$phoneNo=array();

for($lp=0;$lp<count($get_tech_loc);$lp++)
{
	$getTechDetails = select_query("select id,mobile_no,emp_name from $db_name.technicians_login_details where id='".$get_tech_loc[$lp]['inst_id']."' ");
	//echo "<pre>";print_r($getTechDetails);die;
	array_push($latitude,$get_tech_loc[$lp]['day_start_latitude']);
	array_push($logitude,$get_tech_loc[$lp]['day_start_longitude']);
	array_push($addr,$get_tech_loc[$lp]['start_location']);
	array_push($id,$get_tech_loc[$lp]['id']);
	array_push($emp,$getTechDetails[0]['emp_name']);
	array_push($phoneNo,$getTechDetails[0]['mobile_no']);
}

//echo "<pre>";print_r($id);die;

$lat=implode(',', $latitude);
$long=implode(',', $logitude);
$row=implode(',', $id);

//echo "<pre>";print_r($lat);die;
?>

<style type="text/css">
#map_area{
		float:left;
		width:99%;
		height:80%;
}
#map{
		margin: auto;
		/*width: 1000px;*/
		height: 550px;
		border:2px double #ececec;
		padding:10px;
}
.map_marker{
	position:relative;width:34px;height:48px
}
/*marker text span css*/
.my-div-span{
	position: absolute;left:0.5em;right: 0.5em;top:3.4em;bottom:0.5em;font-size:12px;font-weight:bold;width:2px;color:#000000;
	/*background-color:#F9F3B5;border:1px #000000 solid;*/
}
</style>

<script src="https://apis.mapmyindia.com/advancedmaps/v1/65z8s3bowtwlxrko6gy4kl2ic5dv5kca/map_load?v=0.1"></script>

<script>
var map = null;
var marker = [];


var latitudeArr=[<?php echo str_replace('"','',json_encode($lat));?>];
//alert(latitudeArr.length);
var longitudeArr=[<?php echo str_replace('"','',json_encode($long));?>];
var addr=<?php echo json_encode($addr);?>;
var emp=<?php echo json_encode($emp);?>;
var id=[<?php echo str_replace('"','',json_encode($row));?>];
//console.log(latitudeArr);
//console.log(latitudeArr);
var pth = window.location.href;/*get path of image folder*/
var full_path = pth.replace(pth.substr(pth.lastIndexOf('/') + 1), '');

window.onload = function () {
	map = new MapmyIndia.Map('map', {center: [28.549948, 77.268241], zoomControl: true, hybrid: true});
	/***
	 1. Create a MapmyIndia Map by simply calling new MapmyIndia.Map() and passing it a html div id, all other parameters are optional...
	 2. All leaflet mapping functions can be called simply by using "L" object.
	 3. In future, MapmyIndia may extend the customised/forked Leaflet object to enhance mapping functionality for developers, 
	 which will be clearly documented in the MapmyIndia API documentation section.
	 ***/

	/***map-events****/
	map.on("dblclick", function (e) {
		var title = "Text marker sample!";
		marker.push(addMarker(e.latlng, "", title));
	});
};
function addMarker(position, icon, title, draggable) {
	/* position must be instance of L.LatLng that replaces current WGS position of this object. Will always return current WGS position.*/
	var event_div = document.getElementById("event-log");
	if (icon == '') {
		var mk = new L.Marker(position, {draggable: draggable, title: title});/*marker with a default icon and optional param draggable, title */
		mk.bindPopup(title);
	} else {
		var mk = new L.Marker(position, {icon: icon, draggable: draggable, title: title});/*marker with a custom icon */
		mk.bindPopup(title);
	}
	map.addLayer(mk);/*add the marker to the map*/
	/* marker events:*/
	mk.on("click", function (e) {
		//event_div.innerHTML = "Marker clicked<br>" + event_div.innerHTML;
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

/*function to remove  markers from map*/
function mapmyindia_removeMarker() {
	var markerlength = marker.length;
	if (markerlength > 0) {
		for (var i = 0; i < markerlength; i++) {
			map.removeLayer(marker[i]);/* deletion of marker object from the map */
		}
	}
	delete marker;
	marker = [];
	document.getElementById("event-log").innerHTML = "";
}
/*function to add default marker*/
function mapmyindia_sample_marker() {
	mapmyindia_removeMarker();/*Remove marker if exists on map*/
	var postion = new L.LatLng(28.5628, 77.6856);/*The WGS location object*/
	var title = "Sample marker!";
	var mk = addMarker(postion, "", title, false);/*call the add marker function woith the position and title*/
	marker.push(mk);
	map.setView(mk.getLatLng(), 8);/*function that modifies both center map position and zoom level.*/
}
/*function to add custom userdefined marker at a given path*/
function mapmyindia_custom_marker() {
	mapmyindia_removeMarker();
	var icon = L.icon({
		iconUrl: full_path + '/img/4.png',
		iconRetinaUrl: full_path + '/img/4.png',
		iconSize: [30, 30],
		popupAnchor: [-3, -15]
	});
	var postion = new L.LatLng(28.5628, 77.6856);/*WGS location object*/
	var mk = addMarker(postion, icon, "Custom marker sample!", false);
	marker.push(mk);
	map.setView(mk.getLatLng());
}

function mapmyindia_multiple_markers() {
	mapmyindia_removeMarker();
	//event_div.innerHTML=latitudeArr.length;
	//print(latitudeArr.length);
	
	for (var i = 0; i < latitudeArr.length; i++) {
		var icon = L.divIcon({
			className: 'my-div-icon',
			html: "<img class='map_marker'  src=" + full_path + "img/4.png>" + '<span class="my-div-span" >' + emp[i] + '</span>',
			iconSize: [10, 10],
			popupAnchor: [12, -10]
		});/*function that creates a div over a icon and display content on the div*/
		var postion = new L.LatLng(latitudeArr[i], longitudeArr[i]);/*WGS location object*/
		marker.push(addMarker(postion, icon, "Employee:"+emp[i]+"<br/>"+"Lat,Lng:"+latitudeArr[i]+","+longitudeArr[i]+"<br/>"+"Address:"+addr[i]));
	}
	mapmyindia_fit_markers_into_bound();
 	var event_div = document.getElementById("num_marker");
	event_div.innerHTML = "Total Employee:"+latitudeArr.length;
}

/*function to make number appear on marker*/
function mapmyindia_number_on_marker() {
	mapmyindia_removeMarker();
	for (var i = 0; i < latitudeArr.length; i++) {
		var title = "Number marker Sample!";
		var icon = L.divIcon({
			className: 'my-div-icon',
			html: "<img class='map_marker'  src=" + full_path + "'img/4.png'>" + '<span class="my-div-span">' + (i + 1) + '</span>',
			iconSize: [10, 10],
			popupAnchor: [12, -10]
		});/*function that creates a div over a icon and display content on the div*/
		var postion = new L.LatLng(latitudeArr[i], longitudeArr[i]);/*WGS location object*/
		marker.push(addMarker(postion, icon, title));
	}
	mapmyindia_fit_markers_into_bound();
}

/*function to make text appear on marker*/
function mapmyindia_text_on_marker() {
	mapmyindia_removeMarker();
	for (var i = 0; i < latitudeArr.length; i++) {
		var title = "Text marker sample!";
		var icon = L.divIcon({
			className: 'my-div-icon',
			html: "<img class='map_marker' src=" + full_path + "'img/4.png'>" + '<span class="my-div-span">' + 'M' + '</span>',
			//html: "<img style='position:relative;width:34px;height:48px' src=" + "'https://maps.mapmyindia.com/images/4.png'>" + '<span style="position: absolute;left:1.5em;right: 1em;top:0.9em;bottom:3em; font-size:9px;font-weight:bold; width: 4px; color:black;" class="my-div-span">' + 'M' + '</span>',
			iconSize: [10, 10],
			popupAnchor: [12, -10]
		});/*function that creates a div over a icon and display content on the div*/
		var postion = new L.LatLng(latitudeArr[i], longitudeArr[i]);/*WGS location object*/
		marker.push(addMarker(postion, icon, title));
	}
	mapmyindia_fit_markers_into_bound();
}
//*function to add custom userdefined marker say a arrow at a given angle*/
function mapmyindia_Arrow_marker() {
	mapmyindia_removeMarker();
	var angle = 45;
	var icon = L.icon({
		iconUrl: full_path + '/images/arrow.png',
		iconRetinaUrl: full_path + '/images/MarkerIcon.png',
		iconSize: [30, 30],
		popupAnchor: [-3, -15]
	});
	var m = L.marker(new L.LatLng(28.551738, 77.269022), {
		icon: icon,
		draggable: true,
		rotationAngle: angle
	}).addTo(map);
	marker.push(m);
	map.setView(m.getLatLng());/*get the wgs locaton from marker and set the location into center*/
	var event_div = document.getElementById("event-log");
	event_div.innerHTML = "Arrow marker at an angle:" + angle;

}

/*function to make  marker draggable*/
function mapmyindia_draggable_marker() {
	mapmyindia_removeMarker();
	var postion = new L.LatLng(28.5628, 77.6856);/*WGS location object*/
	var mk = addMarker(postion, '', "Draggable marker sample", true);/*call the add marker function*/
	var event_div = document.getElementById("event-log");
	event_div.innerHTML = "Draggable Marker created, drag the marker to the new position.";
	/* following events can be assigned handler (for every instance of draggable marker(s))*/
	mk.on("dragstart", function (e) {
		event_div.innerHTML = "Marker drag start<br>" + event_div.innerHTML;
	});
	mk.on("dragend", function (e) {
		var pt = e.target._latlng;/*event returns lat lng of dragged position*/
		mk.setLatLng(pt);/*set marker position to dragged position*/
		event_div.innerHTML = "Draggable:</br> lat:" + pt.lat + "</br>lng:" + pt.lng + "</br>";
	});
	marker.push(mk);
	map.setView(mk.getLatLng());/*get the wgs locaton from marker and set the location into center*/
}



</script>
     
<div id="content">
  
  <div class="container-fluid">
    <div class="row-fluid">
      <!--<div class="span12">		
		<div class="widget-box">-->
          <!--<div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Employees Location</h5>			
          </div>-->
          
          <!--<div class="widget-content nopadding">-->
            <div id="result">            
            
                <div class="btn-div"><button onclick="mapmyindia_multiple_markers()" >Click To Show Employee Location</button></div>
                <div id="num_marker"></div>
                <div id="event-log"></div>
            </div>
            <div id="map_area">
                    <div id="map"  ></div>
            </div>
		            
          <!--</div>-->
        <!--</div>
      </div>-->
    </div>
  </div>
</div>


<!--end-Footer-part-->
<script src="js/jquery.min.js"></script> 
<script src="js/jquery.ui.custom.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/jquery.uniform.js"></script> 
<script src="js/select2.min.js"></script> 
<script src="js/jquery.dataTables.min.js"></script> 
<script src="js/matrix.js"></script> 
<script src="js/matrix.tables.js"></script>

