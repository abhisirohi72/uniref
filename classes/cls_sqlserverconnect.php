<?php
class SqlServer{


	var $conn;
	var $connStr;
	var $rs;
	var $num_columns; 
	var $fld;
	var $fname;
	var $arr;

	function connect(){
		$this->conn = new COM ("ADODB.Connection") or die("Cannot start ADO");
		$this->connStr = 'PROVIDER=SQLNCLI.1;SERVER=212.25.5.134;DATABASE=dualreporting;UID=trackingexpert;PWD=trackingexpert';
		$this->conn->open($this->connStr); //Open the connection to the database
	}


		function select_query($query,$condition=0){

				if($condition==1){
						echo "<br>".$query."<br>";
				}


				$this->rs = $this->conn->execute($query);
				$this->num_columns = $this->rs->Fields->Count();

					$i=0;		
					while (!$this->rs->EOF)  //carry on looping through while there are records
					{
								for ($j=0; $j < $this->num_columns; $j++) {
										$this->arr[$i][$this->rs->Fields($j)->name]=$this->rs->Fields($j)->value;
								}			
							$this->rs->MoveNext(); //move on to the next record
							$i=$i+1;
					}			
					return $this->arr;
		}
}

?>