<?php
    $dbHost = "localhost";
	$dbDatabase = "controlroom";
    $dbPasswrod = "";
    $dbUser = "root";
    $mysqli = new mysqli($dbHost, $dbUser, $dbPasswrod, $dbDatabase);
?>
<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
<div class="container">

	<form method="POST" action="excelUpload.php" enctype="multipart/form-data">
        <div class="form-group">
            <label>Upload Excel</label>
            <input type="file" name="file" class="form-control">
        </div>
        <div class="form-group">
            <button type="submit" name="Submit" class="btn btn-success">Upload</button>
        </div>
    </form>
</div>
</body>
</html>