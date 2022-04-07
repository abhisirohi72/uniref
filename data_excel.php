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

$startdate = $_REQUEST['from_date'];
$Enddate = $_REQUEST['to_date'];
$Showday = $_REQUEST['select_day'];
$Showrequest = $_REQUEST["select_req"];
$currentdate = date('Y-m-d');

if($startdate!='' && $Enddate!='' && $Showrequest!='' && $Showday!='')
{
	if($startdate!='' && $Enddate!='' && ($Showrequest!=0 || $_POST["Showrequest"]!='') && $Showday=='0')
	{
			if($_POST["Showrequest"]==1)
			{
				$WhereQuery="WHERE job_status=1 and is_active='1' and request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==2)
			{
				$WhereQuery="WHERE job_status=2 and is_active='1' and request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==3)
			{
				$WhereQuery="WHERE job_status=3 and is_active='1' and request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==4)
			{
				$WhereQuery="WHERE job_status=4 and is_active='1' and request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==5)
			{
				$WhereQuery="WHERE job_status=5 and is_active='0' and request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==6)
			{
				$WhereQuery="WHERE request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
			}
			else
			{ 
				$WhereQuery="WHERE job_status IN ('0','1','2','3') and is_active='1' and request_date>='".$startdate."' and request_date<='".$Enddate."' and to_technician!='' ";
			}
	}
	else if($startdate=='' && $Enddate=='' && ($Showrequest!=0 || $_POST["Showrequest"]!='') && $Showday=='0')
	{
			if($_POST["Showrequest"]==1)
			{
				$WhereQuery="WHERE is_active='1' and job_status=1 and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==2)
			{
				$WhereQuery="WHERE is_active='1' and job_status=2 and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==3)
			{
				$WhereQuery="WHERE is_active='1' and job_status=3 and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==4)
			{
				$WhereQuery="WHERE is_active='1' and job_status=4 and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==5)
			{
				$WhereQuery="WHERE is_active='0' and job_status=5 and to_technician!='' ";
			}
			else if($_POST["Showrequest"]==6)
			{
				$WhereQuery="WHERE to_technician!='' ";
			}
			else
			{ 
				$WhereQuery="WHERE is_active='1' and job_status IN ('0','1','2','3') and to_technician!='' ";
			
			}
	}
	else if($startdate=='' && $Enddate=='' && ($Showrequest==0 || $_POST["Showrequest"]=='') && $Showday!='0')
	{
			 if($Showday == 'Today')
			 {
				  $todayStdate = date('Y-m-d',(strtotime($currentdate)))." 00:00";
				  $todayEddate = date('Y-m-d',(strtotime($currentdate)))." 23:59";
				  
				  $WhereQuery=" where request_date>='".$todayStdate."' and request_date<='".$todayEddate."' and to_technician!='' ";
			 }
			 else if($Showday == 'Tomorrow')
			 {
				 $tomorrowStdate = date('Y-m-d', strtotime('+1 days'))." 00:00";
				 $tomorrowEddate = date('Y-m-d', strtotime('+1 days'))." 23:59";
				  
				 $WhereQuery=" where request_date>='".$tomorrowStdate."' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
			 }
			 else if($Showday == 'NextDay')
			 {
				 $nextDayStdate = date('Y-m-d',strtotime('+2 days'))." 00:00";
				 $nextDayEddate = date('Y-m-d',strtotime('+2 days'))." 23:59";
				  
				 $WhereQuery=" where request_date>='".$nextDayStdate."' and request_date<='".$nextDayEddate."' and to_technician!='' ";
			 }
	}
	else if($startdate=='' && $Enddate=='' && ($Showrequest!=0 || $_POST["Showrequest"]!='') && $Showday!='0')
	{
		 if($Showday == 'Today')
		 {
				$todayStdate = date('Y-m-d',(strtotime($currentdate)))." 00:00";
				$todayEddate = date('Y-m-d',(strtotime($currentdate)))." 23:59";
				  
				if($_POST["Showrequest"]==1)
				{
					$WhereQuery="WHERE job_status=1 and request_date>='".$todayStdate."' and is_active='1' and request_date<='".$todayEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==2)
				{
					$WhereQuery="WHERE job_status=2 and request_date>='".$todayStdate."' and is_active='1' and request_date<='".$todayEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==3)
				{
					$WhereQuery="WHERE job_status=3 and request_date>='".$todayStdate."' and is_active='1' and request_date<='".$todayEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==4)
				{
					$WhereQuery="WHERE job_status=4 and request_date>='".$todayStdate."' and is_active='1' and request_date<='".$todayEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==5)
				{
					$WhereQuery="WHERE job_status=5 and request_date>='".$todayStdate."' and is_active='0' and request_date<='".$todayEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==6)
				{
					$WhereQuery="WHERE request_date>='".$todayStdate."' and request_date<='".$todayEddate."' and to_technician!='' ";
				}
				else
				{ 
					$WhereQuery="WHERE job_status IN ('0','1','2','3') and is_active='1' and request_date>='".$todayStdate."' and request_date<='".$todayEddate."' and to_technician!='' ";
				
				}
				
		 }
		 else if($Showday == 'Tomorrow')
		 {
				 $tomorrowStdate = date('Y-m-d', strtotime('+1 days'))." 00:00";
				 $tomorrowEddate = date('Y-m-d', strtotime('+1 days'))." 23:59";
				  
				if($_POST["Showrequest"]==1)
				{
					$WhereQuery="WHERE job_status=1 and request_date>='".$tomorrowStdate."' and is_active='1' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==2)
				{
					$WhereQuery="WHERE job_status=2 and request_date>='".$tomorrowStdate."' and is_active='1' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==3)
				{
					$WhereQuery="WHERE job_status=3 and request_date>='".$tomorrowStdate."' and is_active='1' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==4)
				{
					$WhereQuery="WHERE job_status=4 and request_date>='".$tomorrowStdate."' and is_active='1' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==5)
				{
					$WhereQuery="WHERE job_status=5 and request_date>='".$tomorrowStdate."' and is_active='5' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==6)
				{
					$WhereQuery="WHERE request_date>='".$tomorrowStdate."' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
				}
				else
				{ 
					$WhereQuery="WHERE job_status IN ('0','1','2','3')) and is_active='1' and request_date>='".$tomorrowStdate."' and request_date<='".$tomorrowEddate."' and to_technician!='' ";
				
				}
		 }
		 else if($Showday == 'NextDay')
		 {
				 $nextDayStdate = date('Y-m-d',strtotime('+2 days'))." 00:00";
				 $nextDayEddate = date('Y-m-d',strtotime('+2 days'))." 23:59";
				  
				if($_POST["Showrequest"]==1)
				{
					$WhereQuery="WHERE job_status=1 and request_date>='".$nextDayStdate."' and is_active='1' and request_date<='".$nextDayEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==2)
				{
					$WhereQuery="WHERE job_status=2 and request_date>='".$nextDayStdate."' and is_active='1' and request_date<='".$nextDayEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==3)
				{
					$WhereQuery="WHERE job_status=3 and request_date>='".$nextDayStdate."' and is_active='1' and request_date<='".$nextDayEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==4)
				{
					$WhereQuery="WHERE job_status=4 and request_date>='".$nextDayStdate."' and is_active='1' and request_date<='".$nextDayEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==5)
				{
					$WhereQuery="WHERE job_status=5 and request_date>='".$nextDayStdate."' and is_active='0' and request_date<='".$nextDayEddate."' and to_technician!='' ";
				}
				else if($_POST["Showrequest"]==6)
				{
					$WhereQuery="WHERE request_date>='".$nextDayStdate."' and request_date<='".$nextDayEddate."' and to_technician!='' ";
				}
				else
				{ 
					$WhereQuery="WHERE job_status IN ('0','1','2','3') and is_active='1' and request_date>='".$nextDayStdate."' and request_date<='".$nextDayEddate."' and to_technician!='' ";
				
				}
		 }
		
	}
}
else
{			
	$WhereQuery="WHERE to_technician!='' and job_status!=5 and is_active='1' and request_date<='".$currentdate."' ";
}
   
$get_job_data = select_query("SELECT * FROM $db_name.all_job_details ". $WhereQuery." order by id desc ");

/*$get_job_data = select_query("SELECT * FROM $employee_track.login_emp_details WHERE is_active='1' order by created_date desc ");*/

//echo "<pre>";print_r($get_job_data);die;

$name = "All_Ticket_Request_".$currentdate.".xls";
$objPHPExcel = new PHPExcel();


$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(70);
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



    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "All Ticket Request");
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
    $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Ticket ID');
    $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Call Type');
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Service Type');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Priority');
	$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Location');
	$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Status');
	$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'Created On');
	
	$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'Product Group');
	$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'Technician Name');
	$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'Customer Name');
	$objPHPExcel->getActiveSheet()->SetCellValue('L2', 'Pin Code');
	$objPHPExcel->getActiveSheet()->SetCellValue('M2', 'Product');
	$objPHPExcel->getActiveSheet()->SetCellValue('N2', 'Phone No');
	
    $objPHPExcel->getActiveSheet()->getStyle("A2:B2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("C2:D2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("E2:F2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("G2:H2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("I2:J2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("K2:L2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("M2:N2")->applyFromArray($style)->getAlignment()->setWrapText(true);

    $cou=1;
    $final=array();
	    
                
    foreach($get_job_data as $key=>$dat)
    {	
		$technician_name = select_query("select emp_name from $db_name.technicians_login_details where id='".$dat['to_technician']."'");
		
		if($dat['job_status']==0 && $dat['to_technician']!=''){$status = "Assign to Installer";} 
		else if($dat['job_status']==0 && $dat['to_technician']==''){$status = "Job Not Assign";} 
		else if($dat['job_status']==1){$status = "Accept/On the Way";} 
		else if($dat['job_status']==2){$status = "On the Way";} 
		else if($dat['job_status']==3){$status = "Working";} 
		else if($dat['job_status']==4){$status = "Reject";} 
		else if($dat['job_status']==5){$status = "Complete";}
		
		
        $final[$key]['0']=$cou;
        $final[$key]['1']=$dat['ticket_no'];
        $final[$key]['2']=$dat['call_type'];
        $final[$key]['3']=$dat['service_type'];
		$final[$key]['4']=$dat['priority_type'];
		$final[$key]['5']=$dat['job_location'];
		$final[$key]['6']=$status;
		$final[$key]['7']=$dat['request_date'];
		
		$final[$key]['8']=$dat['product_group'];
		$final[$key]['9']=$technician_name[0]['emp_name'];
		$final[$key]['10']=$dat['customer_name'];
		$final[$key]['11']=$dat['pin_code'];
		$final[$key]['12']=$dat['product_group'];
		$final[$key]['13']=$dat['customer_phone_no'];
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