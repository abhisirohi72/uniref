<?php
ini_set('display_errors', 1);
ob_start();
date_default_timezone_set ("Asia/Calcutta");

// define('__SITE_URL', 'http://203.115.101.55/uniRefBackend');
define('__SITE_URL', 'http://localhost/uniref');
//define('__SITE_URL', 'http://192.168.1.24/uniRefBackend');

define('__DOCUMENT_ROOT', 'C:/xampp/htdocs/uniRefBackend');
//define('__DOCUMENT_ROOT', 'D:/xampp/htdocs/uniRefBackend');

$db_name = "new_uniref";

$hostname2 = "203.115.101.54";
$username2 = "gtrac";
$password2 = "gtrac123";
$databasename2 = "new_uniref";

//$image_path = 'http://localhost/uniRefSystemAPIV1';
//$image_path = 'http://192.168.1.93/uniRefSystemAPIV1';
$image_path = 'http://203.115.101.54/uniRefSystemAPIV1';
 
$dblink2 = @mysql_connect($hostname2,$username2,$password2) ;

//@mysql_select_db($databasename,$dblink);
@mysql_select_db($databasename2,$dblink2);

$Date = date("Y-m-d");
$dateFrom=date('Y-m-d', strtotime($Date. ' - 5 days'));
 
function getcountRow($query)
{
	global $dblink2;
	$hostname2 = "203.115.101.54";
	$username2 = "gtrac";
	$password2 = "gtrac123";
	$databasename2 = "new_uniref";
	
	$dblink2 = @mysql_connect($hostname2,$username2,$password2) ;
	
	$Numberofservice = mysql_query($query)or die(mysql_error($dblink2));;
	$count=mysql_num_rows($Numberofservice);
	return $count;
}

function select_query($query,$condition=0)
{
	global $dblink2;
	$hostname2 = "203.115.101.54";
	$username2 = "gtrac";
	$password2 = "gtrac123";
	$databasename2 = "new_uniref";
	
	$dblink2 = @mysql_connect($hostname2,$username2,$password2) ;
	
	if($condition==1){
		//echo "<br>".$query."<br>";
	}
	$qry=mysql_query($query)or die(mysql_error($dblink2));; 
	
	$num=@mysql_num_rows($qry);
	$num_field=@mysql_num_fields($qry);
	for($i=0;$i<$num_field;$i++)
	{
	$fname[]=@mysql_field_name($qry,$i);
	}
	for($i=0;$i<$num;$i++){
	$result=mysql_fetch_array($qry);
	foreach($fname as $key => $value ) {
		$arr[$i][$value]=$result[$value];
		}
	}
	
	
	return $arr;
}

function insert_query($table_name, $form_data)
{
  
    global $dblink2;
    $hostname2 = "203.115.101.54";
    $username2 = "gtrac";
    $password2 = "gtrac123";
    $databasename2 = "new_uniref";
   
    $dblink2 = @mysql_connect($hostname2,$username2,$password2) ;

    // retrieve the keys of the array (column titles)
    $fields = array_keys($form_data);
 
    // build the query
     $sql = "INSERT INTO ".$table_name."
    (`".implode('`,`', $fields)."`)
    VALUES('".implode("','", $form_data)."')";
 
    // run and return the query result resource
    $insert = mysql_query($sql,$dblink2) or die(mysql_error($dblink2));
    //return $sql;
	return mysql_insert_id();
}

function update_query($table_name,$form_data,$condition)
{
  
    global $dblink2;
    $hostname2 = "203.115.101.54";
    $username2 = "gtrac";
    $password2 = "gtrac123";
    $databasename2 = "new_uniref";
   
    $dblink2 = @mysql_connect($hostname2,$username2,$password2) ;

    // retrieve the keys of the array (column titles)
    //$fields = array_keys($form_data);
    $cond = array();
    foreach($condition as $field => $val) {
       $cond[] = "$field = '$val'";
    }
   
    $fields = array();
    foreach($form_data as $field => $val) {
       $fields[] = "$field = '$val'";
    }
   
    // build the query    
    //$sql = "UPDATE ".$table_name." SET ". join(', ', $fields) ." WHERE ".implode('`,`', $cond)."='".implode("','", $condition)."'";
     $sql = "UPDATE ".$table_name." SET ". join(', ', $fields) ." WHERE ".join(' and ', $cond);
      
    // run and return the query result resource
    $update = mysql_query($sql,$dblink2)or die(mysql_error($dblink2));
    return $sql;
	//return $update;
}

/*******************  End Connection Code ***********************/


?>