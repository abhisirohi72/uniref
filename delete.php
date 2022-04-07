<?php 
error_reporting(0);
require('conn/config.php'); 
echo "Loading.........";
if( $_GET['action'] == 'branch' ) {
 $q = mysql_query("DELETE FROM branch WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-branch.php'</script>";
	}
}


if( $_GET['action'] == 'category' ) {
 $q = mysql_query("DELETE FROM categories WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-category.php'</script>";
	}
}


if( $_GET['action'] == 'vehicle-category' ) {
 $q = mysql_query("DELETE FROM vehicle_category WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-vehicle-category.php'</script>";
	}
}




if( $_GET['action'] == 'categories' ) {
 $q = mysql_query("DELETE FROM categories WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-category.php'</script>";
	}
}

if( $_GET['action'] == 'business_type' ) {
 $q = mysql_query("DELETE FROM business_type WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-business-type.php'</script>";
	}
}

if( $_GET['action'] == 'material_type' ) {
 $q = mysql_query("DELETE FROM material_type WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-material-type.php'</script>";
	}
}

if( $_GET['action'] == 'exception_type' ) {
 $q = mysql_query("DELETE FROM exception_type_new WHERE id = '".base64_decode($_GET['id'])."'");
  //$q = mysql_query("DELETE FROM exception_type_new WHERE exception_type = '".$_GET['excep']."' and sub_category='".$_GET['hour']."' ");

	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-exception-type.php'</script>";
	}
}


if( $_GET['action'] == 'vehicle_type' ) {
 $q = mysql_query("DELETE FROM vehicle_type WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-vehicle-type.php'</script>";
	}
}



if( $_GET['action'] == 'vehicle_make' ) {
 $q = mysql_query("DELETE FROM vehicle_make WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-vehicle-make.php'</script>";
	}
}


if( $_GET['action'] == 'route' ) {
 $q = mysql_query("DELETE FROM route WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-route.php'</script>";
	}
}


if( $_GET['action'] == 'party' ) {
 $q = mysql_query("DELETE FROM party WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-party.php'</script>";
	}
}


if( $_GET['action'] == 'third_party' ) {
 $q = mysql_query("DELETE FROM third_party WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-third-party.php'</script>";
	}
}

if( $_GET['action'] == 'contact' ) {
 $q = mysql_query("DELETE FROM contacts_info WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-contacts.php'</script>";
	}
}

if( $_GET['action'] == 'people' ) {
 $q = mysql_query("DELETE FROM people WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-people.php'</script>";
	}
}


if( $_GET['action'] == 'level' ) {
 $q = mysql_query("DELETE FROM level WHERE id = '".base64_decode($_GET['id'])."'");
	if( $q == true ){
		$_SESSION['success_msg'] = 'set';
		echo "<script>window.location.href='view-level.php'</script>";
	}
}
?>