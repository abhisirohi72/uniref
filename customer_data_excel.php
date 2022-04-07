<?php
ob_start();
session_start();
error_reporting(0);
include_once('config.php');
require_once (__DOCUMENT_ROOT.'/Excel_Classes/PHPExcel.php');

ini_set('max_execution_time', 100);

function dateDifference($date1, $date2)
{ 

    $days1 = date('d', strtotime($date1));        

    $ts1 = strtotime($date1);        
    $ts2 = strtotime($date2);
    
    $year1 = date('Y', $ts1);
    $year2 = date('Y', $ts2);
    
    $month1 = date('m', $ts1);
    $month2 = date('m', $ts2);
    
    if($days1 > 15)        
    {
        $months = (($year2 - $year1) * 12) + ($month2 - $month1);
    }
    else if($days1 < 16)        
    {
        $months = ((($year2 - $year1) * 12) + ($month2 - $month1))+1;
    }
        
   return $months;

}

$user_id=$_SESSION['user_id'];
$comapny=$_SESSION['company'];
$id_roles=$_SESSION['id_roles'];
$active_status=$_SESSION['active_status'];
  
$action = $_REQUEST["submit"];

//echo "<pre>";print_r($_POST);die;
$currentdate = date('Y-m-d');
$todaydate = date('Y-m-d');
$login_id = $_REQUEST['login_id'];


 
$get_customer = select_query("SELECT * FROM $db_name.customer_details WHERE loginid='".$login_id."' and is_active='1' order by id desc ");

//echo "<pre>";print_r($get_customer);die;

$name = "All_Customer_".$currentdate.".xls";
$objPHPExcel = new PHPExcel();


$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(70);
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



    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "All Customer Details");
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
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Organisation Name');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'No of Service');
	$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Total Amount');
	$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Advance Amount');
	$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'Address');
	
	$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'Date of Installation');
	$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'Handing over date');
	$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'Warranty In Months');
	$objPHPExcel->getActiveSheet()->SetCellValue('L2', 'Next AMC Month');
	
    $objPHPExcel->getActiveSheet()->getStyle("A2:B2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("C2:D2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("E2:F2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("G2:H2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("I2:J2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("K2:L2")->applyFromArray($style)->getAlignment()->setWrapText(true);

    $cou=1;
    $final=array();
	    
                
    foreach($get_customer as $key=>$dat)
    {	
		
		if($dat['date_of_installation'] != '0000-00-00' && $dat['date_of_installation'] != '')
		{
			$installationDate = date("d F Y",strtotime($dat['date_of_installation'])); 
		
		} else {
			$installationDate = '';
		}
		
		if($dat['handover_warranty'] != '0000-00-00' && $dat['handover_warranty'] != '')
		{
			$handover_warranty = date("d F Y",strtotime($dat['handover_warranty'])); 
		
		} else {
			$handover_warranty = '';
		}
		
		$no_of_month = 12;
		
		$amc_no_of_service = $dat['amc_no_of_service'];
		
		$amc_month = $no_of_month/$amc_no_of_service;
		
		if($installationDate != "")
		{
			
			$monthdiff = dateDifference($dat['date_of_installation'], $todaydate);
			
			if($monthdiff >= 0 && $monthdiff < $amc_month){ $addmonth = $amc_month;}
			else if($monthdiff >= $amc_month && $monthdiff < ($amc_month*2)){ $addmonth = ($amc_month*2);}
			else if($monthdiff >= ($amc_month*2) && $monthdiff < ($amc_month*3)){ $addmonth = ($amc_month*3);}
			else if($monthdiff >= ($amc_month*3) && $monthdiff < ($amc_month*4)){ $addmonth = ($amc_month*4);}
			else if($monthdiff >= ($amc_month*4) && $monthdiff < ($amc_month*5)){ $addmonth = ($amc_month*5);}
			else if($monthdiff >= ($amc_month*5) && $monthdiff < ($amc_month*6)){ $addmonth = ($amc_month*6);}
			
			$effectiveDate = date('Y-m-d', strtotime("+".$addmonth." months", strtotime($dat['date_of_installation'])));
			
			$nextAMCDate = date('F Y', strtotime("-1 days", strtotime($effectiveDate)));
		}
		
		
        $final[$key]['0']=$cou;
        $final[$key]['1']=$dat['name'].'/'.$dat['cust_id'];
        $final[$key]['2']=$dat['phone_no'];
        $final[$key]['3']=$dat['company_name'];
		$final[$key]['4']=$dat['amc_no_of_service'];
		$final[$key]['5']=$dat['total_purchase_amount'];
		$final[$key]['6']=$dat['amount_recd_advance'];
		$final[$key]['7']=$dat['home_address'];
		
		$final[$key]['8']=$installationDate;
		$final[$key]['9']=$handover_warranty;
		$final[$key]['10']=$dat['warranty_month'].' Months';
		$final[$key]['11']=$nextAMCDate;
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