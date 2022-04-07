<?php
include_once(__DOCUMENT_ROOT.'/reports/private/lib/library_common.php'); 

function select_Procedure1234($query){
  


      try {
            $conn = new PDO("mysql:host=203.115.101.62;dbname=livetrack",
                            "inventory", "123456");
            // execute the stored procedure
            //$sql = 'CALL NearestGeoAddress()';
   $sql = $query;
            $q = $conn->query($sql);
            $q->setFetchMode(PDO::FETCH_ASSOC);
        } catch (PDOException $pe) {
            die("Error occurred:" . $pe->getMessage());
        }

   
    $r[] = $q->fetch();
               
 
   return $r;
}

class GetLocation{
	
	
	var $veh_id;
	var $veh_reg;
	var $tempHTML;
	var $isInsidePOI;
	var $obj_GeoChecker;
	var $distanceBetweenPoints;
	var $distanceBetweenPointsForChandigar;
	var $distanceBetweenPointsForMumbai;
	var $distanceBetweenPointsForJaipur;
	var $distanceBetweenPointsForSonipat;
	var $distanceBetweenPointsForKota;
	var $distanceBetweenPointsForBhilwara;
	

function __construct(){
	$this->isInsidePOI=1; // 0 means inside POI // 1 means outside
	$this->obj_GeoChecker=new GeoChecker();
}


function LocationFromOurGoogleDB($latlng){
			$condition = "";

					$google_data_from_our_db=db__select("SELECT geo_street,geo_town,geo_country,( 6371 * acos( cos( radians(".$latlng[0].") ) * cos( radians( gps_latitude ) ) * cos( radians( gps_longitude ) - radians(".$latlng[1].") )
						 + sin( radians(".$latlng[0].") ) * sin( radians( gps_latitude ) ) ) ) 
						 AS distance FROM livetrack.tbl_geodata_itgc  ORDER BY distance asc  LIMIT  1", $condition);
						 return $google_data_from_our_db;

}

function LocationFromOurGoogleDB_mahaveer($latlng){
	 			$condition = "";
				$latlng=explode(",",$latlng);
				$latlng[0]=safe($latlng[0]);
				$latlng[1]=safe($latlng[1]);
				
				if($latlng[0]=="" && $latlng[1]==""){
					exit;
				} 

$UserPoiData=db__select("SELECT name,gps_radius,gps_longitude,gps_latitude,(3959 * acos( cos( radians(".$latlng[0].") ) * cos( radians( gps_latitude ) ) * cos( radians( gps_longitude ) - radians(".$latlng[1].") )												 + sin( radians(".$latlng[0].") ) * sin( radians( gps_latitude ) ) ) )*1000												 AS distance FROM pois where sys_user_id='".$_SESSION['ParentId']."' or id in (select distinct sys_poi_id from group_pois where active=true and sys_group_id in (select sys_group_id from group_users where sys_user_id='".$_SESSION['UserId']."')) ORDER BY distance asc  LIMIT  1  ", $condition);
 
																			
									if(count($UserPoiData)>0 and $UserPoiData[0]['gps_radius'] > $UserPoiData[0]['distance'])
									{
												return $LocationPoi=$UserPoiData[0]['name'];
												 
									}
									else
									{
 
									

					//$google_data_from_our_db=select_query("SELECT geo_street,geo_town,geo_country,( 6371 * acos( cos( radians(".$latlng[0].") ) * cos( radians( gps_latitude ) ) * cos( radians( gps_longitude ) - radians(".$latlng[1].") ) + sin( radians(".$latlng[0].") ) * sin( radians( gps_latitude ) ) ) )  AS distance FROM tbl_geodata_matrix  ORDER BY distance asc  LIMIT  1");

					 $google_data_from_our_db=select_Procedure("CALL NearestGeoAddress(".$latlng[0].", ".$latlng[1].")");
 					 


					if(count($google_data_from_our_db)){
						//$this->tempHTML .= $google_data_from_our_db[0]['geo_street']." ,". $google_data_from_our_db[0]['geo_town'] ." ,". $google_data_from_our_db[0]['geo_country'] ;
if($google_data_from_our_db[0]['geo_street']!="")
							 {
$LocRemoveSpecialChar=$google_data_from_our_db[0]['geo_street']."  ". $google_data_from_our_db[0]['geo_town'];
$LocRemoveSpecialChar=str_replace( array( '\'', '"', ',' , ';', '<', '>', '?' ), ' ', $LocRemoveSpecialChar);
						return round(($google_data_from_our_db[0]['distance']),2) ." KM from ".$LocRemoveSpecialChar  ;
							 }
					}
					else
						{

						return "";
						} 
									}
					
						 

}
function KmFromDestination($latlng,$destination){
	 
				$latlng=explode(",",$latlng);
				$latlng[0]=safe($latlng[0]);
				$latlng[1]=safe($latlng[1]);
				
				if($latlng[0]=="" && $latlng[1]==""){
					//return "no";
					exit;
				} 
				  
		$KmFromDestinationData=db__select("SELECT *,((7912*asin ( sqrt ( power ( sin ((".$latlng[0]."-Lat ) * 0.00872664625997 ), 2 ) +

 cos( ".$latlng[0]." * 0.0174532925) * cos ( Lat*0.0174532925) * power ( sin ((

 ".$latlng[1]."- Lng ) * 0.00872664625997), 2))))*1.609344) as distance  FROM livetrack.geopc_in where  	
city='".$destination."' or 
Region4='".$destination."' or
Region3='".$destination."' or
Region2='".$destination."' or
Region1='".$destination."' order by distance limit 1 ",$condition);
if(count($KmFromDestinationData)){
						 
if($KmFromDestinationData[0]['distance']!="")
							 {
$LocRemoveSpecialChar=$KmFromDestinationData[0]['City']."  ". $KmFromDestinationData[0]['Region4']."  ". $KmFromDestinationData[0]['Region1'];
$LocRemoveSpecialChar=str_replace( array( '\'', '"', ',' , ';', '<', '>', '?' ), ' ', $LocRemoveSpecialChar);
						//return round(($KmFromDestinationData[0]['distance']),2) ." KM from ".$LocRemoveSpecialChar  ;
						return round(($KmFromDestinationData[0]['distance']),2) ." KM from ".$destination."##".$KmFromDestinationData[0]['Lat'].",".$KmFromDestinationData[0]['Lng']  ;
							 }
							 //city,Region4,Region3,Region2,Region1

					}
					else
						{

						return "---";
						} 
	}





function LocationFromOurGoogleDB_API($latlng){
	 
				$latlng=explode(",",$latlng);
				$latlng[0]=safe($latlng[0]);
				$latlng[1]=safe($latlng[1]);
				
				if($latlng[0]=="" && $latlng[1]==""){
					exit;
				} 
 

					$google_data_from_our_db=select_Procedure1234("CALL NearestGeoAddress(".$latlng[0].", ".$latlng[1].")");
 					 


					if(count($google_data_from_our_db)){
						//$this->tempHTML .= $google_data_from_our_db[0]['geo_street']." ,". $google_data_from_our_db[0]['geo_town'] ." ,". $google_data_from_our_db[0]['geo_country'] ;
if($google_data_from_our_db[0]['geo_street']!="")
							 {
$LocRemoveSpecialChar=$google_data_from_our_db[0]['geo_street']."  ". $google_data_from_our_db[0]['geo_town'];
$LocRemoveSpecialChar=str_replace( array( '\'', '"', ',' , ';', '<', '>', '?' ), ' ', $LocRemoveSpecialChar);
						return round(($google_data_from_our_db[0]['distance']),2) ." KM from ".$LocRemoveSpecialChar  ;
							 }
					}
					else
						{

						return "";
						}
									 
					
						 

}

     function LocationFromOurGoogleDB_123($latlng){
	 			$condition = "";
				$latlng=explode(",",$latlng);
				  $latlng[0]=safe($latlng[0]);
				  $latlng[1]=safe($latlng[1]);
				
				if($latlng[0]=="" && $latlng[1]==""){
					exit;
				} 


$UserPoiData=db__select("SELECT name,gps_radius,gps_longitude,gps_latitude,(3959 * acos( cos( radians(".$latlng[0].") ) * cos( radians( gps_latitude ) ) * cos( radians( gps_longitude ) - radians(".$latlng[1].") )												 + sin( radians(".$latlng[0].") ) * sin( radians( gps_latitude ) ) ) )*1000												 AS distance FROM pois where sys_user_id='".$_SESSION['ParentId']."' or id in (select distinct sys_poi_id from group_pois where active=true and sys_group_id in (select sys_group_id from group_users where sys_user_id='".$_SESSION['UserId']."')) ORDER BY distance asc  LIMIT  1  ", $condition);
 
																		
									if(count($UserPoiData)>0 and $UserPoiData[0]['gps_radius'] > $UserPoiData[0]['distance'])
									{
												return $LocationPoi=$UserPoiData[0]['name'];
												 
									}
									else
									{
 
									

					//$google_data_from_our_db=select_query("SELECT geo_street,geo_town,geo_country,( 6371 * acos( cos( radians(".$latlng[0].") ) * cos( radians( gps_latitude ) ) * cos( radians( gps_longitude ) - radians(".$latlng[1].") ) + sin( radians(".$latlng[0].") ) * sin( radians( gps_latitude ) ) ) )  AS distance FROM tbl_geodata_matrix  ORDER BY distance asc  LIMIT  1");

					echo $google_data_from_our_db=select_Procedure123("CALL NearestGeoAddress(".$latlng[0].", ".$latlng[1].")");
 					 
echo "sdf";	

					if(count($google_data_from_our_db)){
						//$this->tempHTML .= $google_data_from_our_db[0]['geo_street']." ,". $google_data_from_our_db[0]['geo_town'] ." ,". $google_data_from_our_db[0]['geo_country'] ;
if($google_data_from_our_db[0]['geo_street']!="")
							 {
$LocRemoveSpecialChar=$google_data_from_our_db[0]['geo_street']."  ". $google_data_from_our_db[0]['geo_town'];
$LocRemoveSpecialChar=str_replace( array( '\'', '"', ',' , ';', '<', '>', '?' ), ' ', $LocRemoveSpecialChar);
						return round(($google_data_from_our_db[0]['distance']),2) ." KM from ".$LocRemoveSpecialChar  ;
							 }
					}
					else
						{

						return "";
						}
									}
					
						 

}         

function LocationFromGoogle($latlng){
      $Location="";     
	 $ch = curl_init();
	//$url[0]="http://dynamisenterprises.co.uk/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[0]="http://www.bid-4it.co.uk//tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//2$url[1]="http://www.adttransport.co.uk/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[2]="http://skwiix.com/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[3]="http://www.g-trac.in/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//3$url[2]="http://www.financeleadhouse.com/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
//1039446
//http://maincontroller00.appspot.com/maincontroller?latlng=28.6926654,77.1523698


	// $url[0]="http://54.254.107.86:8080/GTracLocationService/?method=address&q=".$latlng[0].",".$latlng[1];


	 $url[0]="http://54.254.219.121:8080/DevelopmentLocationService/?method=address&flag=0&q=".$latlng[0].",".$latlng[1];
	 //$url[1]="http://54.251.149.59/getAddress?latlng=".$latlng[0].",".$latlng[1];
	//$url[2]="http://54.251.178.105/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	///$url[3]="http://54.251.189.51/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[4]="http://www.adttransport.co.uk/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[5]="http://www.financeleadhouse.com/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	curl_setopt($ch, CURLOPT_URL,$url[rand(0,count($url)-1)]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0 );
	//echo strstr($html,"delhi");
	$Location =curl_exec($ch);		
	curl_close($ch);

	if($Location=="")
	{
	 
	 //$Location=$latlng[0].",".$latlng[1];
	  
	} 
	// $Location=LocationFromOurGoogleDB_mahaveer($latlng[0].",".$latlng[1]);
	return $Location;

}
function LocationFromGooglehrecl($latlng){
      $Location="";     
	 $ch = curl_init();
	//$url[0]="http://dynamisenterprises.co.uk/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[0]="http://www.bid-4it.co.uk//tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//2$url[1]="http://www.adttransport.co.uk/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[2]="http://skwiix.com/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[3]="http://www.g-trac.in/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//3$url[2]="http://www.financeleadhouse.com/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
 
	 //$url[0]="http://mastergeocoder.appspot.com/geocoding?method=address&q=".$latlng[0].",".$latlng[1];

	// $url[0]="http://54.254.107.86:8080/GTracLocationService/?method=address&q=".$latlng[0].",".$latlng[1];

	$url[0]="http://54.254.219.121:8080/DevelopmentLocationService/?method=address&flag=0&q=".$latlng[0].",".$latlng[1];

	 //$url[1]="http://54.251.149.59/getAddress?latlng=".$latlng[0].",".$latlng[1];
	//$url[2]="http://54.251.178.105/tracking/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	///$url[3]="http://54.251.189.51/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[4]="http://www.adttransport.co.uk/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	//$url[5]="http://www.financeleadhouse.com/getaddress.php?latlng=".$latlng[0].",".$latlng[1];
	curl_setopt($ch, CURLOPT_URL,$url[rand(0,count($url)-1)]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0 );
	//echo strstr($html,"delhi");
	$Location =curl_exec($ch);		
	curl_close($ch);

	if($Location=="")
	{
	 
	 //$Location=$latlng[0].",".$latlng[1];
	  
	} 
	// $Location=LocationFromOurGoogleDB_mahaveer($latlng[0].",".$latlng[1]);
	return $Location;

}

function checkForHighWays($AddressLocation=""){

global $UserPoiData;

				if((strstr($AddressLocation,"National Highway") or strstr($AddressLocation,"State Highway") or  strstr($AddressLocation,"NH ") or  strstr($AddressLocation,"SH ") or $AddressLocation=="") && count($UserPoiData)>0 && ($_SESSION['UserId']=="3116" or $_SESSION['ParentId']=="3116") ){
					return round(($UserPoiData[0]['distance']/1000),2). " KM From " . $UserPoiData[0]['name'];
				}
				else{
				return $AddressLocation;
				}
}


	function findLocation($latlng)
		{
	######################################
	## Constructor Stars Here


				$this->tempHTML='';
				$latlng=explode(",",$latlng);
				$latlng[0]=safe($latlng[0]);
				$latlng[1]=safe($latlng[1]);
				
				if($latlng[0]=="" && $latlng[1]==""){
					exit;
				}
				
				if($this->veh_id!=""){
						$this->tempHTML="<b>".$this->veh_reg."</b><br>";
				}

					//0.000000000 	0.000000000 
					if($latlng[0]=='0.000000000' || $latlng[1]=='0.000000000')
					{
					return $this->tempHTML;	
					}
 
					$google_data_from_our_db=$this->LocationFromOurGoogleDB($latlng);
					if(count($google_data_from_our_db)==1 && $google_data_from_our_db[0]['distance'] <= 0.2){
						//$this->tempHTML .= $google_data_from_our_db[0]['geo_street']." ,". $google_data_from_our_db[0]['geo_town'] ." ,". $google_data_from_our_db[0]['geo_country'] ;
						$this->tempHTML .= round($google_data_from_our_db[0]['distance'],2)." Km from ".$google_data_from_our_db[0]['geo_street']." ,". $google_data_from_our_db[0]['geo_town'];
					}
					else{
						$this->tempHTML .= $this->LocationFromGoogle($latlng);
						$this->tempHTML=str_replace("?","",$this->tempHTML);
						if($this->tempHTML!='')
						{
						if(stristr($this->tempHTML, '</b><br>') == false)
							{
							if($this->tempHTML!="")
							{
						$query="INSERT INTO livetrack.tbl_geodata_itgc (geo_street,gps_latitude,gps_longitude,update_from) VALUES ('".$this->tempHTML."',".$latlng[0].",".$latlng[1].",'PHP');";
						$qry=@mysql_query($query);
							}
							}
						}
					}


					return $this->checkForHighWays($this->tempHTML)."";
					exit;
				 
	######################################
	## Constructor End Here
	}


	function LocationFromGoogle_vehicleStatus($latlng)
	{
      $Location="";     
	 $ch = curl_init();
	 
 
	 $url[0]="http://54.254.107.86:8080/GTracLocationService/?method=address&q=".$latlng[0].",".$latlng[1];
	 
	curl_setopt($ch, CURLOPT_URL,$url[rand(0,count($url)-1)]);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0 );
	//echo strstr($html,"delhi");
	$Location =curl_exec($ch);		
	curl_close($ch);

	 
	return $Location;

	}



	function findLocation_VehicleStatus($latlng)
		{
	######################################
	## Constructor Stars Here


				$this->tempHTML='';
				$latlng=explode(",",$latlng);
				$latlng[0]=safe($latlng[0]);
				$latlng[1]=safe($latlng[1]);
				
				if($latlng[0]=="" && $latlng[1]==""){
					exit;
				}
				
				if($this->veh_id!=""){
						$this->tempHTML="<b>".$this->veh_reg."</b><br>";
				}

					//0.000000000 	0.000000000 
					if($latlng[0]=='0.000000000' || $latlng[1]=='0.000000000')
					{
					return $this->tempHTML;	
					}

				 
					
					$google_data_from_our_db=$this->LocationFromOurGoogleDB($latlng);
					if(count($google_data_from_our_db)==1 && $google_data_from_our_db[0]['distance'] <=0.1){
						//$this->tempHTML .= $google_data_from_our_db[0]['geo_street']." ,". $google_data_from_our_db[0]['geo_town'] ." ,". $google_data_from_our_db[0]['geo_country'] ;
						$this->tempHTML .= round($google_data_from_our_db[0]['distance'],2)." Km from ".$google_data_from_our_db[0]['geo_street']." ,". $google_data_from_our_db[0]['geo_town'];
					}
					else{
						$this->tempHTML .= $this->LocationFromGoogle_vehicleStatus($latlng);
						$this->tempHTML=str_replace("?","",$this->tempHTML);
						if($this->tempHTML!='')
						{
						if(stristr($this->tempHTML, '</b><br>') == false)
							{
							if($this->tempHTML!="")
							{
						$query="INSERT INTO livetrack.tbl_geodata_itgc (geo_street,gps_latitude,gps_longitude,update_from) VALUES ('".$this->tempHTML."',".$latlng[0].",".$latlng[1].",'php');";
						$qry=@mysql_query($query);
							}
							}
						}
					}


					return $this->checkForHighWays($this->tempHTML)."";
					exit;
				 
	
	######################################
	## Constructor End Here
	}
	
}

?>