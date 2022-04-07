<?php

class DbManager{

	function getYear($temp){
	$year=substr($temp,2,2);
	return $year;
	}
	
	
	function getMonth($temp){
	$month=substr($temp,5,2);
	return $month;
	}
	
	function getday($temp)
	{
	$day=substr($temp,8,2);
	return $day;
	}
	function CreateWeekRange($start, $end, $format = 'd')
 	 {


           $start = new DateTime($start);
           $end = new DateTime($end);

          $invert = $start > $end;
          $dates = array();
          $dates[] = $start->format($format);
        // $dates[] = $start->format($format);
          while ($start != $end)
          {
                  $start->modify(($invert ? '-' : '+') . '1 day');
                  $dates[] = $start->format($format);
           }


           for($i=0;$i<count($dates);$i++)
                        {
                           $WeekDevide=7;
                           $module=$dates[$i]%07;

                           if($module!=0)
                                {
                           $weeks=($dates[$i] / $WeekDevide) +1;
                                }
                                else
                                {
                                        $weeks=($dates[$i] / $WeekDevide);
                                }


                           $weekArray[]=floor($weeks);

                        }
                        $weekArray=array_unique($weekArray,SORT_REGULAR);

            return implode(",",$weekArray);




           }


	function DateMysqlToTimestamp($md)
	{
	$v = mktime ( substr($md, 11, 2) , substr($md, 14, 2), substr($md, 17, 2) , substr($md, 5, 2) , substr($md, 8, 2) ,
	substr($md, 0, 4));
	return $v;
	}
	
	function returnDbArray($dateStart,$dateEnd,$forTwoDates=0)
	{
	//2013-01-21
	// Manage Database Years
	$startYear=$this->getYear($dateStart);
	$endYear=$this->getYear($dateEnd);
	
	$startMonth=$this->getMonth($dateStart);
	$endMonth=$this->getMonth($dateEnd);
	
	$startdate=$this->getday($dateStart);
	$enddate=$this->getday($dateEnd);
	
	$dbArray=array();
	
	$mainStartMonth=$startMonth;
	$i=0;
	for($startYear;$startYear<=$endYear;$startYear++){
	
	$i=$i+1;
	$startYear;
	
	if($startYear!=$endYear){
	
	if($mainStartMonth!=$startMonth){
	$startMonth=1;
	}
	for($startMonth;$startMonth<=12;$startMonth++){
	
	if(strlen($startMonth)==1){     $zero="0";}else{$zero="";}
	
	$temp1=$zero.$startMonth."_".$startYear;
	// ########### Get Previous Month Year
	if(date('M')=="Jan"){
	$y=date('y')-1;
	if(strlen($y)==1){
	$y="0".$y;
	}
	$m="12";
	}
	else{
	$y=date('y');
	$m=date('m')-1;
	
	if(strlen($m)==1){
	$m="0".$m;
	}
	}
	$temp2=$m."_".$y;
	
	// Remove  or $temp1==$temp2 to include last month
	if($temp1==date('m_y')){
	/*if($startdate==$enddate && $enddate==date('d'))
					{
					$dbArray[]="matrix";
	
					}
					else if($enddate==date('d'))
			{
	
			  $dbArray[]="matrix";
			   $dbArray[]="matrix_".date('m_y');
	
	
			}
			else
					{
	  $dbArray[]="matrix_".date('m_y');
					}
	
	 */
	
	$dbArray[]="matrix_".date('m_y');
	}
	else{
	$dbArray[]="matrix_".$zero.$startMonth."_".$startYear;
	}
	}
	
	}
	else{
	
	if($i!=1){
	$startMonth=1;
	}
	for($startMonth;$startMonth<=$endMonth;$startMonth++){
	if(strlen($startMonth)==1){     $zero="0";}else{$zero="";}
	$temp1=$zero.$startMonth."_".$startYear;
	
	// ########### Get Previous Month Year
	if(date('M')=="Jan"){
	$y=date('y')-1;
	if(strlen($y)==1){
	$y="0".$y;
	}
	$m="12";
	}
	else{
	$y=date('y');
	$m=date('m')-1;
	
	if(strlen($m)==1){
	$m="0".$m;
	}
	
	}
	$temp2=$m."_".$y;
	
	// Remove  or $temp1==$temp2 to include last month
	if($temp1==date('m_y'))
			{
	
	/*if($startdate==$enddate && $enddate==date('d'))
					{
					$dbArray[]="matrix";
	
					}
					else if($enddate==date('d'))
			{
	
			  $dbArray[]="matrix";
			   $dbArray[]="matrix_".date('m_y');
	
	
			}
			else
					{
	  $dbArray[]="matrix_".date('m_y');
					}*/
	
	
	 $dbArray[]="matrix_".date('m_y');
	
	//$dbArray[]="matrix";
	
	}
	
	
	
	else{
	$dbArray[]="matrix_".$zero.$startMonth."_".$startYear;
	}
	}
	}
	
	}
	
	if($forTwoDates==0){
	$dbArray[]="matrix";
	}
	$dbArray=array_unique($dbArray);
	return $dbArray;
	
	// Manage Database
	
	}
}


?>