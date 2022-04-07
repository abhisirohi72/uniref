<?php
ob_start();
session_start();
error_reporting(0);
include_once('config.php');
require_once (__DOCUMENT_ROOT.'/Excel_Classes/PHPExcel.php');

ini_set('max_execution_time', 100);

$user_id=$_SESSION['user_id'];
$comapny=$_SESSION['company'];
$id_roles=$_SESSION['id_roles'];
$active_status=$_SESSION['active_status'];
  
$action = $_REQUEST["submit"];

//echo "<pre>";print_r($_POST);die;
$currentdate = date('Y-m-d');

$login_id = $_REQUEST['login_id'];


 
$get_customer = select_query("SELECT * FROM $db_name.technicians_login_details WHERE is_active='1' order by id desc ");

//echo "<pre>";print_r($get_customer);die;

$name = "All_Technician_".$currentdate.".xls";
$objPHPExcel = new PHPExcel();


$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
//$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(70);
//$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);            
$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);

/*$style = array(
       'alignment' => array(
     'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      )
);
$objPHPExcel->getDefaultStyle()->applyFromArray($style);        */    



    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "All Technician Details");
    $objPHPExcel->getActiveSheet()->mergeCells('B1:H1');     
    $style = array(
        	'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								),
			 'font' => array('bold' => true)
    );
	$style1 = array(
        	'alignment' => array(
									'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								)
    
    );
    //echo "<pre>";print_r($objPHPExcel);die;
     $objPHPExcel->getActiveSheet()->getStyle("B1:H1")->applyFromArray($style);

   
    $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'S.No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Name/ID');
    $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Mobile No');
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Home Address');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Aadhar No');
	$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Gender');
	$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'DOB');
	$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'Battery');
	$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'Active Status');
	$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'Day Status');
	
    $objPHPExcel->getActiveSheet()->getStyle("A2:B2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("C2:D2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("E2:F2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("G2:H2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("I2:J2")->applyFromArray($style)->getAlignment()->setWrapText(true);

    $cou=1;
    $final=array();
	    
                
    foreach($get_customer as $key=>$dat)
    {	
		
		if( $dat['is_active'] == '1' ) { $status = "Active";} else {$status = "Deactive";}
		
		if( $dat['day_start_end'] == '1' ) { $day_end = "Job Start";} else if( $dat['day_start_end'] == '2' ) { $day_end = "Job End";} else {$day_end = "Job Not Start";}
		
		
        $final[$key]['0']=$cou;
        $final[$key]['1']=$dat['emp_name'].'/'.$dat['technician_id'];
        $final[$key]['2']=$dat['mobile_no'];
        $final[$key]['3']=$dat['home_address'];
		$final[$key]['4']=$dat['aadhar_no'];
		$final[$key]['5']=$dat['gender'];
		$final[$key]['6']=$dat['dob'];
		$final[$key]['7']=$dat['battery_level'];
		
		$final[$key]['8']=$status;
		$final[$key]['9']=$day_end;
        $cou++;
        
    }


if(isset($final) && !empty($final))
{
$charArray = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U');
$counter = 3;    
    for($i=0;$i<count($final);$i++){
        foreach($final[$i] as $k=>$val){
            //echo $charArray[$k];die;
           $objPHPExcel->getActiveSheet()->SetCellValue($charArray[$k].''.$counter, $val);
           $objPHPExcel->getActiveSheet()->getStyle($charArray[$k].''.$counter)->getAlignment()->setWrapText(true);
           $objPHPExcel->getActiveSheet()->getStyle($charArray[$k].''.$counter)->applyFromArray($style1);
          }  
           $counter++ ;
    }                
}         

    

header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'.$name.'"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');                         
// $file = __DOCUMENT_ROOT.'/reports/excel_reports/'.$name ;            
// $objWriter->save($file);                
$objWriter->save('php://output');
return true;



?>  