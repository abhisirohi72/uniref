<?
/////////////////////////////////////////////////////
// PHP Class : HTML Table
// Author    : Dimitris Kossikidis
// E-mail    : kossikidis@vip.gr
// Descr.    : Build an HTML table without writing 
//  			any HTML code. Javascript supported			
/////////////////////////////////////////////////////

class html_table{

      var $bgcolor = "";            // Table's bgcolor
      var $width = "100%";			// Table's width
      var $cellspacing = 1;			// Table's cellspacing
      var $cellpadding = 4;         // Table's cellpadding
      var $border = 0;   			// Table's Border
      var $row_color="#FFCC99";     // 

      var $set_row_event = FALSE;    // Enable onmouseover event to display selected table's row in a different color
      var $set_row_event_color="#9999FF"; // set the color

      var $set_alter_colors = FALSE;  // Display Table rows in a different color except header if it is enabled
      var $first_alter_color="#FFCC99"; // first row color
      var $second_alter_color="#FFFFFF"; // second row color

      var $display_header_row = FALSE;   // Table Has a header Row
      var $set_bold_labels=TRUE;      // Fonts in header are bold
      var $set_header_font_color="#000000"; // header font color
      var $set_header_font_face="Tahoma";   // header font face
      var $set_header_font_size=2;          // header font face
      var $set_header_bgcolor ="#FFCC33";   // header background color

      var $display_fonts = FALSE;     // Output Font 's Tags in table's cells
      var $set_font_color="#000000";  // Tag font color
      var $set_font_face="Tahoma";    // Tag font face
      var $set_font_size=2;           // Tag font size

      var $current_color;            // don't change it.
	  var $count;					 // count rows
      /**
       * html_table::opentable()
       * 
       * @return 
       **/
      function opentable(){
               $html = "\n\n<!--// Start Table //-->\n\n\n<table width='".$this->width."'border='".$this->border."' cellspacing='". $this->cellspacing."' cellpadding='". $this->cellpadding."' bgcolor='". $this->bgcolor."'>\n";
               return $html;
      }


      /**
       * html_table::build_row_event()
       * 
       * @return 
       **/
      function build_row_event(){
               $event = " onmouseover=\"this.style.background='$this->set_row_event_color';this.style.cursor='hand'\"
                                        onmouseout=\"this.style.background='$this->current_color'\"";
               return $event;
      }


      /**
       * html_table::row()
       * 
       * @param $isheader
       * @return 
       **/
      function row( $isheader ){

               if ( $this->display_header_row ){
                   if ( $isheader ){
                       $this->current_color = $this->set_header_bgcolor;
                   }else{
                       if ( $this->set_alter_colors ){
                         $this->count%2!=0 ? $this->current_color = $this->first_alter_color : $this->current_color = $this->second_alter_color;
                       }else{
                         $this->current_color = $this->row_color;
                       }
                   }
               }else{
                    if ( $this->set_alter_colors ){
                         if ( $this->set_alter_colors ){
                              $this->count%2!=0 ? $this->current_color = $this->first_alter_color : $this->current_color = $this->second_alter_color;
                         }
                    }else{
                         $this->current_color = $this->row_color;
                    }
               }

               if ( $this->set_row_event && $isheader==0) { 
			   		$event = $this->build_row_event(); 
			   } else { 
			   		$event = "" ;
			   }
               $html = "<tr bgcolor='".$this->current_color."' $event>\n";
               return $html;
      }

      /**
       * html_table::row_close()
       * 
       * @return 
       **/
      function row_close(){
               $html = "</tr>\n";
               return $html;
      }

      /**
       * html_table::build_html_table()
       * 
       * @param $contents
       * @return 
       **/
      function build_html_table( $contents ){
               $TABLE = $this->opentable();
               $TABLE .= $this->build_columns( $contents );
               $TABLE .= $this->closetable();
               return $TABLE;
      }

      /**
       * html_table::build_fonts()
       * 
       * @param $txt
       * @param integer $isheader
       * @return 
       **/
      function build_fonts($txt,$isheader=0){

               if ($isheader && $this->display_header_row){
                   if ($this->set_bold_labels){
                       $txt = "<b>$txt</b>";
                   }
                   $html = "<font face='$this->set_header_font_face' size='$this->set_header_font_size' color='$this->set_header_font_color'>$txt</font>";
               }else{
                   if ($this->display_fonts){
                       $html = "<font face='$this->set_font_face' size='$this->set_font_size' color='$this->set_font_color'>$txt</font>";
				   }else{
				   		$html = $txt;
				   }
               }
               return $html;
      }

      /**
       * html_table::build_columns()
       * 
       * @param $contents
       * @return 
       **/
      function build_columns( $contents ){

               $html = "";
               reset($contents);
               while (list($key, $dis) = each($contents)){
                      !$this->count ? $header=1 : $header=0;
					  $this->count++;
                      $html .= $this->row( $header );
                      $cell="";
                      while ( list($id,$values) = each($dis)){
                              $cell .= "<td ";
                              if ( IsSet($values["align"]) ) $cell .= "align='".$values["align"]."' ";
                              if ( IsSet($values["width"]) ) $cell .= "width='".$values["width"]."' ";
                              if ( IsSet($values["bgcolor"]) ) $cell .= "bgcolor='".$values["bgcolor"]."' ";
                              if ( IsSet($values["colspan"]) ) $cell .= "colspan='".$values["colspan"]."' ";
                              if ( IsSet($values["valign"]) ) $cell .= "align='".$values["valign"]."' ";
                              if ( !IsSet($values["text"]) ) $values["text"]="&nbsp;";
                              $cell .= ">";
                              $cell .= $this->build_fonts($values["text"],$header);
                              $cell .= "</td>\n";
                      }
                      $html .= $cell;
                      $html .= $this->row_close();
               }
               return $html;
      }

      /**
       * html_table::closetable()
       * 
       * @return 
       **/
      function closetable(){
               $html = "</table>\n\n\n <!--// Close Table //-->\n\n\n";
               return $html;
      }

}

?>
