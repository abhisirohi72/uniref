<?php
class GetLocation{
	
	
	var $veh_id;
	var $veh_reg;
	var $tempHTML;
	var $isInsidePOI;
	var $obj_GeoChecker;
	var $distanceBetweenPoints;
	var $distanceBetweenPointsForChandigar;
	var $distanceBetweenPointsForMumbai;
	var $ThisLatLng;


function __construct(){
	$this->isInsidePOI=1; // 0 means inside POI // 1 means outside
	$this->obj_GeoChecker=new GeoChecker();
}

function checkForHighWays($AddressLocation=""){

global $UserPoiData;


																// If We are getting blank data.. try once more 
																

				if((strstr($AddressLocation,"National Highway") or strstr($AddressLocation,"State Highway")) && count($UserPoiData)>0 ){
					return round(($UserPoiData[0]['distance']/1000),2). " KM From " . $UserPoiData[0]['name'];
				}
				else{
				return $AddressLocation;
				}
}


	function findLocation($latlng){
	######################################
	## Constructor Stars Here

				$this->ThisLatLng=$latlng;

				$this->tempHTML='';
				$latlng=explode(",",$latlng);
				$latlng[0]=safe($latlng[0]);
				$latlng[1]=safe($latlng[1]);
				
				if($latlng[0]=="" && $latlng[1]==""){
					exit;
				}
				
				if($this->veh_id!=""){
						$this->tempHTML.="<b>".$this->veh_reg."</b><br>";
				}

				
				// For Delhi
				$this->distanceBetweenPoints = $this->obj_GeoChecker->calculateDistanceBetweenLatLong(28.545925723233477,77.24349975585938,$latlng[0],$latlng[0]);
				$this->distanceBetweenPoints=($this->distanceBetweenPoints*1.609)*1000; 

				// For Chandigarh
				$this->distanceBetweenPointsForChandigar = $this->obj_GeoChecker->calculateDistanceBetweenLatLong(30.668039322,76.886962891,$latlng[0],$latlng[0]);
				$this->distanceBetweenPointsForChandigar=($this->distanceBetweenPointsForChandigar*1.609)*1000; 

				// For Chandigarh
				$this->distanceBetweenPointsForMumbai = $this->obj_GeoChecker->calculateDistanceBetweenLatLong(30.668039322,76.886962891,$latlng[0],$latlng[0]);
				$this->distanceBetweenPointsForMumbai=($this->distanceBetweenPointsForMumbai*1.609)*1000; 

								if($this->distanceBetweenPoints<30000 or $this->distanceBetweenPointsForChandigar<30000 or $this->distanceBetweenPointsForMumbai<30000 or $_SESSION['UserId']=="3116" or $_SESSION['UserId']=="3116" or $_SESSION['ParentId']=="3116"){
									####### Inside Delhi                 ######## Inside Chandigarh  ##### Only For User AMLF

									 

										/*	$data=select_query("SELECT geo_street,geo_town,geo_country,( 3959 * acos( cos( radians(".$latlng[0].") ) * cos( radians( gps_latitude ) ) * cos( radians( gps_longitude ) - radians(".$latlng[1].") )
											 + sin( radians(".$latlng[0].") ) * sin( radians( gps_latitude ) ) ) ) 
											 AS distance FROM livetrack.tbl_geodata_itgc  ORDER BY distance asc  LIMIT  1");


													if($data[0]['distance']<0.5){


															if($data[0]['geo_street']!=""){
																$this->tempHTML.= $data[0]['geo_street'];
															}
															if($data[0]['geo_town']!=""){
																$this->tempHTML.= ", ".$data[0]['geo_town'];
															}
															if($data[0]['geo_country']!=""){
																$this->tempHTML.= ", ".$data[0]['geo_country'];
															}
															return $this->tempHTML;
															exit;
													}*/


																$ch = curl_init();
																//$url[0]="http://www.networksensex.com/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
																$url[0]="http://www.financeleadhouse.com/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
																$url[1]="http://www.bid-4it.co.uk//tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
																$url[2]="http://www.totalbathroomdesign.co.uk/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
																$url[3]="http://www.adttransport.co.uk/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
																$url[4]="http://skwiix.com/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
																$url[5]="http://www.g-trac.in/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
																//$url[6]="http://www.financeleadhouse.com/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
																
																curl_setopt($ch, CURLOPT_URL,$url[rand(0,count($url)-1)]);
																curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
																curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0 );
																//echo strstr($html,"delhi");
																$this->tempHTML .=curl_exec ($ch);													
																curl_close ($ch);
																//$qry="insert into tbl_geodata_itgc values()";

																return $this->checkForHighWays($this->tempHTML)."";
																exit;

													//} // Check In 0.5 KM End Here

								} // Check Inside Delhi End Here

								
				$data=select_query("SELECT City,Region4,Region3,Region2,Region1,( 3959 * acos( cos( radians(".$latlng[0].") ) * cos( radians( Lat ) ) * cos( radians( Lng ) - radians(".$latlng[1].") )
						 + sin( radians(".$latlng[0].") ) * sin( radians( Lat ) ) ) ) 
						 AS distance FROM livetrack.geopc_in  ORDER BY distance asc  LIMIT  1");
						 	
							if($data[0]['City']!=""){
								$this->tempHTML.= $data[0]['City'];
							}
							if($data[0]['Region4']!=""){
								$this->tempHTML.= ", ".$data[0]['Region4'];
							}
							if($data[0]['Region3']!=""){
								$this->tempHTML.= ", ".$data[0]['Region3'];
							}
							if($data[0]['Region1']!=""){
								$this->tempHTML.= ", ".$data[0]['Region1'];
							}
							return $this->tempHTML;




										/*						$ch = curl_init();
										$url="http://vibegraphics.co.uk/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];

										curl_setopt($ch, CURLOPT_URL,$url);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
										//echo strstr($html,"delhi");
										$html = $temp=curl_exec ($ch);
													
										if(stripos($temp,"delhi")!="" or stripos($temp,"delhi")>0 or stripos($temp,"Gurgaon" or stripos($temp,"noida"))>0){
											$this->tempHTML.=$html;

											curl_close ($ch);
											return $this->tempHTML;
											exit;
										}*/
	
	######################################
	## Constructor End Here
	}
	
}

?>