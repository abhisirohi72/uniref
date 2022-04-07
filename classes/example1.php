<?php
/////////////////////////////////////////////////////
// PHP Class : HTML Table
// Author    : Dimitris Kossikidis
// E-mail    : kossikidis@vip.gr
// File      : example1.php
// Descr.    : Build an HTML table without writing 
//  			any HTML code. Javascript supported			
/////////////////////////////////////////////////////

include ("html_table.class.php");

// create object
$mytbl = new html_table();

// General Table properties
$mytbl->width = "450"; 			// set table width;
$mytbl->cellspacing = 1; 		// 1 is class's default value
$mytbl->cellpadding = 4;    	// 4 is class's default value
$mytbl->border = 0; 			// 0 is class's default value
$mytbl->rowcolor = "#E9E9E9"; 	// table's rows colors...default is #FFCC99

// Set table's header row
$mytbl->display_header_row = TRUE;       // enable the option. Default is FALSE
$mytbl->set_bold_labels = TRUE;    		 // Default is TRUE
$mytbl->set_header_font_color="#000000"; // Default is #000000
$mytbl->set_header_font_face="Tahoma";   // default is Tahoma
$mytbl->set_header_bgcolor ="#FF9933";   // Default if $FFCC33

//Set row event
$mytbl->set_row_event = TRUE; // Default is FALSE
$mytbl->set_row_event_color = "#FF9900"; //Default is #9999FF

// Set table's rows alter colors
$mytbl->set_alter_colors = TRUE;    	// Default is False
$mytbl->first_alter_color = "#CCCCCC"; 	// Default is #FFCC99
$mytbl->second_alter_color = "#E9E9E9"; // Default is #FFFFFF

// Add Font Tags in each cell
$mytbl->display_fonts = TRUE; // Default Is FALSE


// Builbing A Table - 3 colums, 5 rows

// 1st row Colspan 3
$myarr[0][0]["colspan"]= 3;
$myarr[0][0]["align"]  = "center";
$myarr[0][0]["text"]   = "Example of HTML TABLE Class";

// adding rows
for ( $i=1; $i<=25; $i++){
	$myarr[$i][0]["width"] = 50;
	$myarr[$i][0]["align"] = "right";
	$myarr[$i][0]["text"]  = $i;
	$myarr[$i][1]["width"] = 300;
	$myarr[$i][1]["align"] = "center";
	$myarr[$i][1]["text"]  = "<a href=\"#\">Link to Page $i</a>";	
	$myarr[$i][2]["width"] = 100;
	$myarr[$i][2]["align"] = "left";
	$myarr[$i][2]["text"]  = "Option $i";		
}	

// Building Html from array
$html = $mytbl->build_html_table( $myarr );
?>
<html>
<head><title>HTML TABLE CLASS ::: Example</title></head>
<body>
<center>
<?php
	// Echo the table.
	echo $html;
?>
<a href="mailto:kossikidis@vip.gr">Dimitris Kossikidis</a>
</center>
</body>
</html>
