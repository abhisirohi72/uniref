<?php

session_start();


$dbhost = '203.115.101.54';
$dbuser = 'pintu';
$dbpass = '123456';
$db = 'controlroom';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$db);
//$selected = mysql_select_db($db,$conn) or die("Could not connect to database");


$status=$_GET['status'];
$noti_results = mysqli_query( $conn,"SELECT * FROM idle_notification WHERE is_active='0' and sys_user_id='".$_SESSION['user_id']."' ");


$data = array();


  $i=1;
//echo mysql_num_rows($noti_results)."<br>";
if (mysqli_num_rows($noti_results) > 0) {
			
  while($row = mysqli_fetch_assoc($noti_results)) {

    //echo $i;

    $get_contacts = mysqli_query($conn,"SELECT * FROM contacts_info WHERE vehicles like  '%".$row['veh_no']."%' and exception = '".$row['location']."' and hr <='".$row['idle_hr']."'  and user_id='".$_SESSION['user_id']."' order by hr desc");


    if (mysqli_num_rows($get_contacts) > 0)  {


        //echo $get_contacts->id;echo "<br>";

       
         $people=mysqli_fetch_assoc($get_contacts);

         $arr = array(
                                    'id' =>$row['id'],
                                    'veh_no' =>$row['veh_no'],
                                    'sys_service_id' => $row['sys_service_id'],
                                    'sys_group_id' => $row['sys_group_id'],
                                    'idle_hr' => $row['idle_hr'],
                                    'location' => $row['location'],
                                    'comment' =>$row['comment'],
                                    'submit_type' => $row['submit_type'],
                                    'insert_time' =>$row['insert_time'],
                                    'update_time' => $row['update_time'],
                                    'distance_from' => $row['distance_from'],
                                    'comment_by' => $row['comment_by'],
                                    'is_active' => $row['is_active'],
                                    'people' =>$people['people'],
                                    'contact'=>$people['contact']
                                    
                                );


      array_push($data, $arr);









         // echo "<pre>";
         // print_r($people);
         // echo $people['people']."<br>";
         // echo $people['contact'];

         // echo $i;

     }


        
                               
    
   
   //  $i++;



  	// $arr = array(
   //                                  'id' =>$row['id'],
   //                                  'veh_no' =>$row['veh_no'],
   //                                  'sys_service_id' => $row['sys_service_id'],
   //                                  'sys_group_id' => $row['sys_group_id'],
   //                                  'idle_hr' => $row['idle_hr'],
   //                                  'location' => $row['location'],
   //                                  'comment' =>$row['comment'],
   //                                  'submit_type' => $row['submit_type'],
   //                                  'insert_time' =>$row['insert_time'],
   //                                  'update_time' => $row['update_time'],
   //                                  'distance_from' => $row['distance_from'],
   //                                  'comment_by' => $row['comment_by'],
   //                                  'is_active' => $row['is_active']
                                    
   //                              );


  	//   array_push($data, $arr);

	// echo $row['id']."--".$row['veh_no']."--".$row['sys_service_id']."--".$row['sys_group_id']."--".$row['idle_hr']."--".$row['location']."--".$row['comment']."--".$row['submit_type']."--".$row['insert_time']."--".$row['update_time']."--".$row['distance_from']."--".$row['comment_by'] ."--".$row['is_active'] ."<br>";

			

		}

}
//echo "<pre>";
echo json_encode($data);

//print_r($data);
?>



