<?php
//Author - Sanjana
 
require("conn/config.php"); 

function dbRowInsert($table_name, $data)
{
    $fields = array_keys($data);
    $sql = "INSERT INTO ".$table_name."  (`".implode('`,`', $fields)."`) VALUES('".implode("','", $data)."')";
    return mysql_query($sql);
}

function dbRowDelete($table_name, $where_clause='')
{
    $whereSQL = '';
    if(!empty($where_clause))
    {
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
			$whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    $sql = "DELETE FROM ".$table_name.$whereSQL;
    return mysql_query($sql);
}

function dbRowUpdate($table_name, $form_data, $where_clause='')
{
    $whereSQL = '';
    if(!empty($where_clause))
    {
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    $sql = "UPDATE ".$table_name." SET ";
	$sets = array();
    foreach($form_data as $column => $value)
    {
         $sets[] = "`".$column."` = '".$value."'";
    }
    $sql .= implode(', ', $sets);
	
	$sql .= $whereSQL;
	
    return mysql_query($sql);
}