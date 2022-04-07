<?php 
session_start();
error_reporting(0);
require("config.php");
 
//echo "<pre>";print_r($_REQUEST);die;

if($_REQUEST['action'] == 'userDetails') 
{
	
	$cust_nameData = $_REQUEST['customer_id'];
	
	$cust_nameSplit = explode("##",$cust_nameData);
		
	$cust_name = $cust_nameSplit[0];
	$cust_id = $cust_nameSplit[1];
	
	$get_edit_recd = select_query("SELECT * FROM $db_name.customer_details WHERE cust_id='".$cust_id."' and is_active='1' and 
	loginid='".$_SESSION['user_id']."' ");
	
	echo $get_edit_recd[0]['phone_no'].'##'.$get_edit_recd[0]['email_id'];
			 
	
}

if($_REQUEST['action'] == 'getModelname') 
{
	
	$cust_nameData = $_REQUEST['customer_id'];
	
	$cust_nameSplit = explode("##",$cust_nameData);
		
	$cust_name = $cust_nameSplit[0];
	$cust_id = $cust_nameSplit[1];
	
	$get_model = select_query("SELECT * FROM $db_name.customer_model_master WHERE customer_id='".$cust_id."' and is_active='1' and 
	loginid='".$_SESSION['user_id']."' ");
	
	if(count($get_model)>0)
	{
		$gtracAllVeh = array();	
		foreach($get_model as $key => $gallval) {
					
			  $gtracAllVeh[] = trim('{"model":"'.$gallval['model_purchased'].'"}','"');
		}
		
		$strReplaceVal = str_replace('"{\"model\":\"','{"model":"',json_encode($gtracAllVeh));
		 
		echo $str = str_replace('\"}"','"}',$strReplaceVal);
		
	}
	else
	{
	  echo '[{"model":""}]' ; 
	}
			 
	
}

if($_REQUEST['action'] == 'getSerialno') 
{
	
	$model = $_REQUEST['model'];
	
	$cust_nameData = $_REQUEST['customer_id'];
	
	$cust_nameSplit = explode("##",$cust_nameData);
		
	$cust_name = $cust_nameSplit[0];
	$cust_id = $cust_nameSplit[1];
	
	$get_Serialno = select_query("SELECT * FROM $db_name.customer_model_master WHERE customer_id='".$cust_id."' and 
	model_purchased='".$model."' and is_active='1' and  loginid='".$_SESSION['user_id']."' ");
	
	echo $get_Serialno[0]['serial_no'];
			 
	
}


?>