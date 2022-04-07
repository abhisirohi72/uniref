<?php
include ("BasicTable.php");

// create object
$mytbl = new BasicTable("<table class=\"dataTable\">");
$mytbl->addHeader("Start");
$mytbl->addHeader("Stop");
$mytbl->addHeader("Stop");
$tmp[] = "<td class=\"subheading\" colspan=\"9\">7/8/09</td>";
$mytbl->addRow($tmp);
echo $mytbl->getTable();
?>