
<?php
require('library/php-excel-reader/excel_reader2.php');
require('library/SpreadsheetReader.php');

$dbHost = "localhost";
$dbDatabase = "controlroom";
$dbPasswrod = "";
$dbUser = "root";
$mysqli = new mysqli($dbHost, $dbUser, $dbPasswrod, $dbDatabase);
print_r($_FILES);
if(isset($_POST['Submit']))
{
    $mimes = array('application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.oasis.opendocument.spreadsheet','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    if(in_array($_FILES["file"]["type"],$mimes))
    {
        $uploadFilePath = 'uploads/'.basename($_FILES['file']['name']);

		move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);
        $Reader = new SpreadsheetReader($uploadFilePath);

		$totalSheet = count($Reader->sheets());
       // echo "You have total ".$totalSheet." sheets";

		/* For Loop for all sheets */
        for($i=0;$i<$totalSheet;$i++)
        {
            $Reader->ChangeSheet($i);
            foreach ($Reader as $Row)
            {
               
                $trip_location = isset($Row[0]) ? $Row[0] : '';
                $trip_number = isset($Row[1]) ? $Row[1] : '';
				$source_destination = isset($Row[2]) ? $Row[2] : '';
				$dispatch_date = isset($Row[3]) ? $Row[3] : '';
				$journey_hour = isset($Row[4]) ? $Row[4] : '';
				$driver_name = isset($Row[5]) ? $Row[5] : '';
				$driver_number = isset($Row[6]) ? $Row[6] : '';
				$date = isset($Row[7]) ? $Row[7] : '';
				
                //$html.="<td>".$title."</td>";
                //$html.="<td>".$description."</td>";
                //$html.="</tr>";

				$query = "insert into trip(trip_location,trip_number,source_destination,dispatch_date,journey_hour,driver_name,driver_number,date) values('".$trip_location."','".$trip_number."', '".$source_destination."', '".$dispatch_date."', '".$journey_hour."', '".$driver_name."', '".$driver_number."', '".$date."' )";
                $mysqli->query($query);
            }
        }
        

            echo "<br />Data Inserted in dababase";
        }
        else
        {
            die("<br/>Sorry, File type is not allowed. Only Excel file.");
        }
}
?>