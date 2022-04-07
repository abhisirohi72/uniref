<?php 
include("config.php");
session_start();
// echo "<pre>";
// print_r($_SESSION);
// exit;
require_once ('Excel_Classes/PHPExcel.php');
$objPHPExcel = new PHPExcel();
$action = (isset($_REQUEST['action']))?$_REQUEST['action']:"";
if($action == "view-fsr-request-job")
{
	$name = $_SESSION['user_id']."view-fsr-request-job.xls";
	$objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(75);
    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
    $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(30);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "Customer Service/Complaint");
    $objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
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
     $objPHPExcel->getActiveSheet()->getStyle("B1:G1")->applyFromArray($style);


    $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'S.No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Ticket ID');
    $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Service Type');
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Location');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Status');
	$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Created On');
	$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Technician Name');
	$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'Customer Name');
    $objPHPExcel->getActiveSheet()->getStyle("A2:H2")->applyFromArray($style)->getAlignment()->setWrapText(true);

    $final=array();
	$currentdate = date('Y-m-d');
	
	// echo "SELECT t1.*, t2.emp_name FROM all_job_details AS t1 LEFT JOIN technicians_login_details AS t2 ON t2.id=t1.to_technician WHERE t1.loginid='".$_SESSION['user_id']."' and t1.to_technician is null  and t1.job_status!=5 and t1.is_active='1' and t1.request_date<='".$currentdate."'  order by t1.id desc ";
	// exit;
	
	$getJobDetails = select_query("SELECT * FROM $db_name.all_job_details WHERE to_technician is null  and job_status!=5 and is_active='1' and request_date<='".$currentdate."'  order by id desc ");
    
	foreach($getJobDetails as $key=>$getJobDetail)
    {
		$technician_name = select_query("select emp_name from technicians_login_details where id='".$getJobDetail['to_technician']."'");
		foreach($technician_name as $technician_name);
		
        $final[$key]['0']=$key+1;
        $final[$key]['1']=$getJobDetail['ticket_no'];
        $final[$key]['2']=$getJobDetail['service_type'];
        $final[$key]['3']=$getJobDetail['job_location'];
		if($getJobDetail['job_status']==0 && $getJobDetail['to_technician']!=''){
			$final[$key]['4']= "Assign to Installer";
		} else if($getJobDetail['job_status']==0 && $getJobDetail['to_technician']==''){
			$final[$key]['4']= "Job Not Assign";
		} else if($getJobDetail['job_status']==1){
			$final[$key]['4']= "Accept/On the Way";
		} else if($getJobDetail['job_status']==2){
			$final[$key]['4']= "On the Way";
		} else if($getJobDetail['job_status']==3){
			$final[$key]['4']= "Working";
		} else if($getJobDetail['job_status']==4){
			$final[$key]['4']= "Reject";
		} else if($getJobDetail['job_status']==5){
			$final[$key]['4']= "Complete";
		}
		$final[$key]['5']= $getJobDetail['request_date'];
		$final[$key]['6']= (isset($technician_name['emp_name']))?$technician_name['emp_name']:"";
		$final[$key]['7']= $getJobDetail['customer_name'];
	}
}elseif($action == "customer_history"){
	$name = $_SESSION['user_id']."customer_history.xls";
	$objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(55);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(75);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(55);
    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
    $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(30);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "All Ticket Request");
    $objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
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
     $objPHPExcel->getActiveSheet()->getStyle("B1:G1")->applyFromArray($style);


    $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'S.No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Name/ID');
    $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Mobile No');
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Organisation Name');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Model Purchased');
	$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Serial No');
	$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Date Of Installations');
    $objPHPExcel->getActiveSheet()->getStyle("A2:B2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("C2:D2")->applyFromArray($style)->getAlignment()->setWrapText(true);

    $final=array();
	$currentdate = date('Y-m-d');
	
	// echo "SELECT t1.*, t2.emp_name FROM all_job_details AS t1 LEFT JOIN technicians_login_details AS t2 ON t2.id=t1.to_technician WHERE t1.loginid='".$_SESSION['user_id']."' and t1.to_technician !=''  and t1.job_status!=5 and t1.is_active='1' and t1.request_date<='".$currentdate."'  order by t1.id desc ";
	
	$get_peoples = select_query("SELECT * FROM customer_details WHERE is_active='1' order by id desc ");
    
	foreach($get_peoples as $key=>$get_people)
    {
		if($get_people['date_of_installation'] != '0000-00-00' && $get_people['date_of_installation'] != '')
		{
			$installationDate = date("d F Y",strtotime($get_people['date_of_installation'])); 
		
		} else {
			$installationDate = '';
		}
        $final[$key]['0']=$key+1;
        $final[$key]['1']=$get_people['name'].'/'.$get_people['cust_id'];
        $final[$key]['2']=$get_people['phone_no'];
        $final[$key]['3']=$get_people['company_name'];
		$final[$key]['4']= $get_people['model_purchased'];
		$final[$key]['5']= $get_people['serial_no'];
		$final[$key]['6']= $installationDate;
	}
}elseif($action == "customer_notification"){
	$name = $_SESSION['user_id']."customer_notification.xls";
	$objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(75);
    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
    $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(30);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "All Customer Notification");
    $objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
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
     $objPHPExcel->getActiveSheet()->getStyle("B1:G1")->applyFromArray($style);


    $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'S.No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Send To');
    $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Notification Message');
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'From');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'To');
    $objPHPExcel->getActiveSheet()->getStyle("A2:B2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("C2:D2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

    $final=array();
	
	$WhereQuery = "WHERE is_active='1' order by to_date desc,create_time desc limit 50 ";
	
	$get_tech_leave_data = select_query("SELECT * FROM cust_push_notification ".$WhereQuery);
    
	foreach($get_tech_leave_data as $key=>$detail)
    {
		if($detail['person_id']!= '' && $detail['person_id']!='All')
		{	
			$get_cust_data = select_query("SELECT * FROM customer_details WHERE cust_id='".$detail['person_id']."' ");
			$cust_name = $get_cust_data[0]['name'];
		} 
		else 
		{
			$cust_name = $detail['person_id'];
		}
        $final[$key]['0']=$key+1;
        $final[$key]['1']=$cust_name;
        $final[$key]['2']=$detail['message'];
        $final[$key]['3']=$detail['from_date'];
		$final[$key]['4']= $detail['to_date'];
	}
}elseif($action == "customer_rating"){
	$name = $_SESSION['user_id']."customer_rating.xls";
	$objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    // $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(75);
    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
    $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(30);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "All Customer Rating");
    $objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
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
     $objPHPExcel->getActiveSheet()->getStyle("B1:G1")->applyFromArray($style);


    $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'S.No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Ticket ID');
    $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Customer Name');
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Request Date');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Service Type');
	$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Location');
	$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Close Date');
	$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'Rating');
	$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'Rating Comment');
    $objPHPExcel->getActiveSheet()->getStyle("A2:B2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("C2:D2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

    $final=array();
	
	$WhereQuery = "WHERE is_active='0' and job_rating!='' order by job_close_time desc";
	
	$get_cust_rating_data = select_query("SELECT * FROM all_job_details ".$WhereQuery);
    
	foreach($get_cust_rating_data as $key=>$detail)
    {
        $final[$key]['0']=$key+1;
        $final[$key]['1']=$detail['ticket_no'];
        $final[$key]['2']=$detail['customer_name'];
        $final[$key]['3']=$detail['request_date'];
		$final[$key]['4']= $detail['service_type'];
		$final[$key]['5']= $detail['job_location'];
		$final[$key]['6']= $detail['job_close_time'];
		$final[$key]['7']= $detail['job_rating'];
		$final[$key]['8']= $detail['rating_msg'];
	}
}elseif($action == "technicians_leave_request"){
	$name = $_SESSION['user_id']."technicians_leave_request.xls";
	$objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    // $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(75);
    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
    $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(30);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "All Technicians Leave Request");
    $objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
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
     $objPHPExcel->getActiveSheet()->getStyle("B1:G1")->applyFromArray($style);


    $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'S.No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Name');
    $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Leave From');
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Leave To');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Reason of Leave');
	$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Approve/Disapprove');
    $objPHPExcel->getActiveSheet()->getStyle("A2:B2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("C2:D2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

    $final=array();
	
	$WhereQuery = "WHERE is_active='1' order by to_date desc limit 50 ";
	
	$get_tech_leave_data = select_query("SELECT * FROM leave_request ".$WhereQuery);
    
	foreach($get_tech_leave_data as $key=>$detail)
    {
        $final[$key]['0']=$key+1;
        $final[$key]['1']=$detail['name'];
        $final[$key]['2']=$detail['from_date'];
        $final[$key]['3']=$detail['to_date'];
		$final[$key]['4']= $detail['reason'];
		if( $detail['is_status'] == '1' ) { 
			$final[$key]['5']= "Approve";
		} else if( $detail['is_status'] == '2' ) { 
			$final[$key]['5']= "Reject";
		} else {
			$final[$key]['5']= "No Action";
		}
	}
}elseif($action == "technicians_notification"){
	$name = $_SESSION['user_id']."technicians_notification.xls";
	$objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    // $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(75);
    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
    $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(30);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "All Technicians Notification");
    $objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
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
     $objPHPExcel->getActiveSheet()->getStyle("B1:G1")->applyFromArray($style);


    $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'S.No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Send To');
    $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Notification Message');
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'From');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'To');
    $objPHPExcel->getActiveSheet()->getStyle("A2:B2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("C2:D2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

    $final=array();
	
	$WhereQuery = "WHERE is_active='1' order by to_date desc,create_time desc limit 50 ";
	
	$get_tech_leave_data = select_query("SELECT * FROM push_notification ".$WhereQuery);
    
	foreach($get_tech_leave_data as $key=>$detail)
    {
		if($detail['person_id'] != "" && $detail['person_id'] != "All")
		{
			$get_tech_data = select_query("SELECT * FROM technicians_login_details WHERE id='".$detail['person_id']."' ");
			$tech_name = $get_tech_data[0]['emp_name'];
		} else {
			$tech_name = $detail['person_id'];
		}
        $final[$key]['0']=$key+1;
        $final[$key]['1']=$tech_name;
        $final[$key]['2']=$detail['message'];
        $final[$key]['3']=$detail['from_date'];
		$final[$key]['4']= $detail['to_date'];
	}
}elseif($action == "technicians_job_history"){
	$name = $_SESSION['user_id']."technicians_job_history.xls";
	$objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    // $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(75);
    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
    $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(30);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "All Technicians Service History");
    $objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
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
     $objPHPExcel->getActiveSheet()->getStyle("B1:G1")->applyFromArray($style);


    $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'S.No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Name');
    $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Mobile No');
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Technicians Id');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Date of Joining');
	$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Service Done');
	$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Date of Leaving');
	
    $objPHPExcel->getActiveSheet()->getStyle("A2:B2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("C2:D2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	// $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

    $final=array();
	
	$get_people = select_query("SELECT * FROM technicians_login_details WHERE is_active='1' order by id desc ");
    
	foreach($get_people as $key=>$detail)
    {
		$service_done = select_query("SELECT count(id) as no_of_job FROM all_job_details WHERE to_technician='".$detail['id']."' and job_status='5' order by id desc ");
		
        $final[$key]['0']=$key+1;
        $final[$key]['1']=$detail['emp_name'];
        $final[$key]['2']=$detail['mobile_no'];
        $final[$key]['3']=$detail['technician_id'];
		if($detail['date_of_joining'] != '0000-00-00' && $detail['date_of_joining'] != ''){
			$final[$key]['4']= date("d/m/Y",strtotime($detail['date_of_joining'])); 
		}
		$final[$key]['5']= $service_done[0]['no_of_job'];
		if($detail['job_status'] == 0){
			$final[$key]['6']= "Assign to Installer";
		} else if( $detail['job_status'] == 1 ) { 
			$final[$key]['6']= "Accept/On the Way";
		} else if( $detail['job_status'] == 2 ) { 
			$final[$key]['6']= "On the Way";
		} else if( $detail['job_status'] == 3 ) { 
			$final[$key]['6']= "Currently Working";
		} else if( $detail['job_status'] == 4 ) { 
			$final[$key]['6']= "Reject";
		} else if( $detail['job_status'] == 5 ) { 
			$final[$key]['6']= "Completed";
		} else {
			$final[$key]['6']= "No Action";
		}
	}
}elseif($action == "technicians_attendance"){
	$name = $_SESSION['user_id']."technicians_attendance.xls";
	$objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    // $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(75);
    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
    $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(30);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "All Technicians Attendance");
    $objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
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
     $objPHPExcel->getActiveSheet()->getStyle("B1:G1")->applyFromArray($style);


    $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'S.No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Name/ID');
    $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Mobile No');
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Day Start Location');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Day End Location');
	$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Task Assigned');
	$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Current Status');
	$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'Day In Time');
	$objPHPExcel->getActiveSheet()->SetCellValue('I2', 'Day Out Time');
	$objPHPExcel->getActiveSheet()->SetCellValue('J2', 'Total Working Hrs');
	$objPHPExcel->getActiveSheet()->SetCellValue('K2', 'Start KM');
	$objPHPExcel->getActiveSheet()->SetCellValue('L2', 'End KM');
	$objPHPExcel->getActiveSheet()->SetCellValue('M2', 'Total Kms Travelled');
	
    $objPHPExcel->getActiveSheet()->getStyle("A2:B2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("C2:D2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	// $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

    $final=array();
	$startdate = date('Y-m-d');
	$get_people = select_query("SELECT * FROM technicians_login_details WHERE is_active='1' order by id desc ");
    
	foreach($get_people as $key=>$detail)
    {
		$service_done = select_query("SELECT count(id) as no_of_job FROM all_job_details WHERE to_technician='".$detail['id']."' and request_date='".$startdate."' and is_active='1'");
		
		$tech_day_in_out = select_query("SELECT * FROM installer_attendence_tbl WHERE 
						inst_id='".$detail['id']."'  and  req_date='".$startdate."'  and is_active='1' ");
					
		if(count($tech_day_in_out)>0)
		{
			
			if($tech_day_in_out[0]['start_time']!=''){
				$tech_day_in = date("h:i A",strtotime($tech_day_in_out[0]['start_time']));
			}
			else{
				$tech_day_in = '';
			}
			if($tech_day_in_out[0]['end_time']!=''){
				$tech_day_out = date("h:i A",strtotime($tech_day_in_out[0]['end_time']));
			}
			else{
				$tech_day_out = '';
			}
			if($tech_day_in_out[0]['end_time']!='')
			{
				$journeyHrSec = dateDifferenceSecond($tech_day_in_out[0]['start_time'], $tech_day_in_out[0]['end_time']);
				$total_hr = minDifferenceForJourney($journeyHrSec);
				
				$start_km = $tech_day_in_out[0]['odometer_start_km'];
				$end_km   = $tech_day_in_out[0]['odometer_end_km'];
				
			}else{
				$total_hr = '';
			}
			
			if($tech_day_in_out[0]['odometer_start_km']!='' && $tech_day_in_out[0]['odometer_end_km']!='')
			{
				$getdistance = $tech_day_in_out[0]['odometer_end_km'] - $tech_day_in_out[0]['odometer_start_km'];
			} else{
				$getdistance = '0';
			}
			
		} else {
			$tech_day_in = '';
			$tech_day_out = '';
			$total_hr = '';
			$start_km = '';
			$end_km = '';
			$getdistance = '0';
		}
		
        $final[$key]['0']=$key+1;
        $final[$key]['1']=$detail['emp_name'].'/'.$detail['technician_id'];
        $final[$key]['2']=$detail['mobile_no'];
        $final[$key]['3']=(isset($tech_day_in_out[0]['start_location']))?$tech_day_in_out[0]['start_location']:'';
		$final[$key]['4']= (isset($tech_day_in_out[0]['end_location']))?$tech_day_in_out[0]['end_location']:'';
		$final[$key]['5']= (isset($service_done[0]['no_of_job']))?$service_done[0]['no_of_job']:'';
		if( $detail['job_status'] == '1' ) { 
			$final[$key]['6']= "On the Way";
		} else if( $detail['job_status'] == '2' ) { 
			$final[$key]['6']= "Currently Working";
		} else if( $detail['job_status'] == '5' ) { 
			$final[$key]['6']= "Completed";
		} else {
			$final[$key]['6']= "No Action";
		}
		$final[$key]['7']= $tech_day_in;
		$final[$key]['8']= $tech_day_out;
		$final[$key]['9']= $total_hr;
		$final[$key]['10']= $start_km;
		$final[$key]['11']= $end_km;
		$final[$key]['12']= $getdistance." Kms";
	}
}elseif($action == "technicians_extra_expense"){
	$name = $_SESSION['user_id']."technicians_extra_expense.xls";
	$objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    // $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(75);
    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
    $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(30);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "All Technicians Extra Expense");
    $objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
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
     $objPHPExcel->getActiveSheet()->getStyle("B1:G1")->applyFromArray($style);


    $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'S.No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Name');
    $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Phone No');
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Start Location');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'End Location');
	$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Bill Amount');
	$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Request Date');
	$objPHPExcel->getActiveSheet()->SetCellValue('H2', 'Approve/Disapprove');
	
    $objPHPExcel->getActiveSheet()->getStyle("A2:B2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("C2:D2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("E2:F2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("G2:H2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

    $final=array();
	$startdate = date('Y-m-d');
	
	$WhereQuery = "WHERE is_active='1' order by req_date desc limit 50 ";
	
	$get_tech_extra_exp = select_query("SELECT * FROM extra_expense_claim_tbl ".$WhereQuery);
    
	foreach($get_tech_extra_exp as $key=>$detail)
    {
		$get_people = select_query("SELECT * FROM technicians_login_details WHERE id='".$detail['tech_id']."' order by id  ");
		
        $final[$key]['0']=$key+1;
        $final[$key]['1']=$get_people[0]['emp_name'];
        $final[$key]['2']=$detail['phone_no'];
        $final[$key]['3']=$detail['start_location'];
		$final[$key]['4']= $detail['end_location'];
		$final[$key]['5']= $detail['bill_amount'];
		$final[$key]['6']= $detail['req_date'];
		if( $detail['approve_status'] == '1' ) { 
			$final[$key]['7']= "Approve";
		} else if( $detail['approve_status'] == '2' ) { 
			$final[$key]['7']= "Reject";
		} else {
			$final[$key]['7']= "No Action";
		}
	}
}elseif($action == "technicians_tracking_view"){
	error_reporting(0);
	$name = $_SESSION['user_id']."technicians_tracking_view.xls";
	$objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    // $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(75);
    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
    $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(30);
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
	
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "Technicians Tracking Day Record");
    $objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
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
     $objPHPExcel->getActiveSheet()->getStyle("B1:G1")->applyFromArray($style);


    $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'S.No.');
    $objPHPExcel->getActiveSheet()->SetCellValue('B2', 'Name/ID');
    $objPHPExcel->getActiveSheet()->SetCellValue('C2', 'Mobile No');
    $objPHPExcel->getActiveSheet()->SetCellValue('D2', 'Location');
	$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'Battery Level');
	$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'Location Time');
	$objPHPExcel->getActiveSheet()->SetCellValue('G2', 'Date');
	
    $objPHPExcel->getActiveSheet()->getStyle("A2:B2")->applyFromArray($style)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle("C2:D2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("E2:F2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle("G2:H2")->applyFromArray($style)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

    $final=array();
	$startdate = date('Y-m-d');
	
	$get_people = select_query("SELECT * FROM $db_name.technicians_login_details WHERE  is_active='1' order by id  ");
    
	foreach($get_people as $key=>$detail)
    {
		$tech_current_locId = select_query("select max(id) as id from technicians_tracking where 
								tech_id='".$detail['id']."' and Date_of_journey='$startdate' and is_active='1'  group by tech_id");			
				
		$get_tech_loc = select_query("SELECT * FROM technicians_tracking WHERE id='".$tech_current_locId[0]['id']."'  ");	
		
		$get_tech_leave_data = select_query("SELECT * FROM leave_request WHERE tech_id='".$detail['id']."' and
								is_active='1' and is_status='1' and ((from_date BETWEEN '".$startdate."' AND '".$startdate."')  
								or (to_date BETWEEN '".$startdate."' AND '".$startdate."'))");
		
        $final[$key]['0']=$key+1;
        $final[$key]['1']=$detail['emp_name']."/ ".$detail['technician_id'];
		if(count($get_tech_leave_data)>0){ 
			$final[$key]['2']= "Leave";
		}
        $final[$key]['3']=$detail['mobile_no'];
		$final[$key]['4']= $get_tech_loc[0]['job_location'];
		$final[$key]['5']= $get_tech_loc[0]['battery_level'];
		$final[$key]['6']= $get_tech_loc[0]['location_time'];
		$final[$key]['7']= $get_tech_loc[0]['Date_of_journey']; 
	}
}
// echo "<pre>";
// print_r($final);
// exit;
if(isset($final) && !empty($final))
{
    $charArray = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U');
    $counter = 3;    
    for($i=0;$i<count($final);$i++){
        foreach($final[$i] as $k=>$val){
           $objPHPExcel->getActiveSheet()->SetCellValue($charArray[$k].''.$counter, $val);
           $objPHPExcel->getActiveSheet()->getStyle($charArray[$k].''.$counter)->getAlignment()->setWrapText(true);
           $objPHPExcel->getActiveSheet()->getStyle($charArray[$k].''.$counter)->applyFromArray($style1);
        }  
        $counter++ ;
    }                
}         

    
// echo $name; exit;
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'.$name.'"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');                         
// $file = __DOCUMENT_ROOT.'/reports/excel_reports/'.$name ;            
// $objWriter->save($file);                
    $objWriter->save('php://output');
    return true;
?>