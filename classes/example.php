<html>
<body>
<table align="center" border="0" cellpadding="5">
<?php
  include('tz.php');
  
  $color = array("#FFFFFF", "#CCCCCC");

  $time = time();
  $x = 0;
  while(list($zone,$null) = each($tz_zone)) {
    $there = tz_localtime($time, $zone);
    echo "<tr bgcolor=\"" . $color[$x%2] . "\"><td>$zone</td><td>" . sprintf("%02d/%02d/%d %02d:%02d:%02d\n", $there[4]+1, $there[3], $there[5]+1900, $there[2], $there[1], $there[0]) . "</td><td>" . ($there[9]?'DST':'') . "</td></tr>\n";
    $x++;
  }
?>
</table>
</body>
</html>
