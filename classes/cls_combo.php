<?
// In Sql Pass First Column as ID Column and 2nd Column Should Be Display Text column
class ComboBox{
	var $style;
	function MakeCombo($ssql,$cmbname,$firstnode, $firstnodevalue, $onclick,$defaultvalue){
		$rst = mysql_query($ssql);
		$eof = mysql_num_rows($rst);
		if($eof != 0){			?>
			<select <?=$this->style;?> name="<? echo $cmbname ?>"  id="<? echo $cmbname ?>" onChange="<? echo $onclick ?>">
				<? if($firstnode != ''){ ?>
					<option value="<? echo $firstnodevalue ?>"><? echo $firstnode; ?></option>
				<? } //for the first node
					while ($row = mysql_fetch_array($rst)){
						if($defaultvalue == $row[0]) {
							?><option selected value="<? echo $row[0]; ?>"><? echo $row[1]; ?></option><?
						} else {
							?><option value="<? echo $row[0]; ?>"><? echo $row[1]; ?></option><?
						}
					} //for the while
				?>
			</select>
			<?
		} 
	}// End Of function MakeCombo
	
	function MakeComboForMap($ssql,$cmbname,$firstnode, $firstnodevalue, $onclick,$defaultvalue){
		$rst = mysql_query($ssql);
		$eof = mysql_num_rows($rst);
		if($eof != 0){			?>
			<select <?=$this->style;?> name="<? echo $cmbname ?>"  id="<? echo $cmbname ?>" onChange="<? echo $onclick ?>">
				<? if($firstnode != ''){ ?>
					<option value="<? echo $firstnodevalue ?>"><? echo $firstnode; ?></option>
				<? } //for the first node
					while ($row = mysql_fetch_array($rst)){
						if($defaultvalue == $row[2]) {
							?><option selected value="<? echo $row[0]; ?>"><? echo $row[1]; ?></option><?
						} else {
							?><option value="<? echo $row[0]; ?>"><? echo $row[1]; ?></option><?
						}
					} //for the while
				?>
			</select>
			<?
		} 
	}
}//end of class Combo

class ListBox123{
	function MakeList($ssql,$cmbname,$firstnode, $firstnodevalue,$height, $onclick){
		$rst = mysql_query($ssql);
		$eof = mysql_num_rows($rst);
		if($eof != 0){			?>
			<select name="<? echo $cmbname ?>" multiple size="<? echo $height; ?>" onChange="<? echo $onclick; ?>">
				<? if($firstnode != ''){ ?>
					<option value="<? echo $firstnodevalue ?>"><? echo $firstnode; ?></option>
				<? } //for the first node
					while ($row = mysql_fetch_array($rst)){
						?><option value="<? echo $row[0]; ?>"><? echo $row[1]; ?></option><?
					} //for the while
				?>
			</select>
			<?
		} 
	}
}//end of class List Box




class ListBox{
	function MakeList($ssql,$cmbname,$firstnode, $firstnodevalue,$height, $onclick, $selectedvalue){
		$arr_selectedvalue = explode(",",$selectedvalue);
		//echo "<br>".count($arr_selectedvalue)." <  This IS Count";
		$rst = mysql_query($ssql);
		$eof = mysql_num_rows($rst);
		if($eof != 0){			?>
			<select name="<? echo $cmbname ?>" multiple size="<? echo $height; ?>" onChange="<? echo $onclick; ?>">
				<? if($firstnode != ''){ ?>
					<option value="<? echo $firstnodevalue ?>"><? echo $firstnode; ?></option>
				<? } //for the first node
					while ($row = mysql_fetch_array($rst)){
						$i = 0;
						$added = '';
						for($i=0;$i<count($arr_selectedvalue);++$i){
							if(trim($arr_selectedvalue[$i]) == $row[0]){
								?><option selected value="<? echo $row[0]; ?>"><? echo $row[1]; ?></option><?		
								$added = 'y';
							}
						}
						if($added == ''){?>
							<option value="<? echo $row[0]; ?>"><? echo $row[1]; ?></option><?
						}
					} //for the while
				?>
			</select>
			<?
		} 
	}
}//end of class List Box


?>