    <?php
	/// <summary>
    /// A simple wrapper to make generating the html tables a bit easier.
    /// </summary>
    class BasicTable
    {
        var $openTag;
        var $headers=array();
        var $rows;

        function __construct($opentag)
        {
            $this->openTag = $opentag;
        }

        function addHeader($header)
        {
            $this->headers[]=$header;
        }

        function addRow($cells)
        {
            $tmp = "";
			//echo $cells;
            foreach($cells as $value)
            {
                $tmp.= "".$value."\n";
            }
            $this->rows[]=$tmp;			
        }

        function getTable()
        {

            $tmp .= $this->openTag."\n";
            $tmp .= "<tr>";

            
            for ($i = 0; $i < count($this->headers); $i++)
            {
                $tmp .= "<th>" . $this->headers[$i] . "</th>";
            }
            $tmp .= "</tr>\n";
          
		    $toggle = false;
			//echo count($this->rows);
            for ($j = 0; $j < count($this->rows); $j++)
            {
                $tmp .= "<tr>".$this->rows[$j]."</tr>\n";
                //toggle = !toggle;
            }
           
            $tmp .= "</table>\n";          
			return $tmp;
			
        }
    }

	?>