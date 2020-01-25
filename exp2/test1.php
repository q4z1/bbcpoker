<?php
$auth1=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc']==2 or $_SESSION['upc']==3)
    $auth1 = 1;
} //$_COOKIE['PHPSESSID'] != ""


print '<!DOCTYPE html>
<html>';
require_once($_SERVER['DOCUMENT_ROOT'].'/defines.php');
ini_set('include_path', ROOT_DIR);
include "head.php";
$regulartaskcount=1;
include "exp2/regulartasks.php";
print "<body>";
include "header1.php"; 
include "exp5/nav1.php";
?>

<?php

if($auth1==1)
{
print "<h1> Results Input</h1>";

//$ttext = date("Y-m-d H:i:s",time()-7*43200);
$request = "SELECT * FROM table1 ORDER BY inputtime DESC 
LIMIT 1";
$result = mysql_query($request);
$c=0;
while($row = mysql_fetch_object($result))
{
	$c++;
	$gameno = $row->gameno;
	$step=$row->step;
	print "<p><b>Last Game input: BBC Game $gameno of Step $step</b></p>";
	$pids = array($row->p1,$row->p2,$row->p3,$row->p4,$row->p5,$row->p6,$row->p7,$row->p8,$row->p9,$row->p10);
	print "<table border=1><tr><th>Winner</th>";
	for($i1=2;$i1<11;$i1++) print "<td>Place $i1</td>";
	print "</tr>\n<tr>";
	$pnames=array("","","");
	for($i1=0;$i1<10;$i1++)
	{
		print "<td>";
		$request2 = "SELECT name FROM table2 WHERE playerid=$pids[$i1] AND playerid>1024";
		$result2 = mysql_query($request2);
		while($row2 = mysql_fetch_object($result2))
		{
			print $row2->name ;
			if($i1<3)$pnames[$i1]=$row2->name;
		}
		print "</td>";	
	}
	print "</tr></table>";
	print "<p>Season: <b>";
	print $row->season;
	print "</b>, ";
	$time = $row->datetime;
	
	if(strpos($time,"2000-01-01")===false) $dtt = "<b>" . date("D, d M Y H:i T",strtotime($time)) ."</b>";
	else $dtt = "unknown";
	print "Begin: $dtt, ";
	$time = $row->inputtime;
	if(strpos($time,"2000-01-01")===false) $dtt = "<b>" . date("D, d M Y H:i T",strtotime($time)) ."</b>";
	else $dtt = "unknown";
	print "Input: $dtt</p>";
}
	


print <<<E
<h2>New Game Input</h2>
<p> (with some error detection)</p>
<p> if less than 10 players participated, enter "0" as name </p>
<form action="/exp2/test2.php" method="post">
<p>Game start, Date:  
E;

$dateval = date("Y-m-d");
print "<input type=\"Text\" name=\"date\" value=\"$dateval\" maxlength=10 size=10>";
$hour=(int)date("H");

$timeval="23:59:59";
if($hour==20 or $hour==21) $timeval="19:30:00";
if($hour==23) $timeval="21:30:00";
if($hour==24) $timeval="23:00:00";
print "Time: <input type=\"Text\" name=\"time\" value=\"$timeval\" maxlength=8 size=8></p>";



$step=$_POST['step'];
if($step != 2 and $step != 3 and $step!=4) $step = 1;
print "Step ";
for($i=1;$i<5;$i++)
{
	$ctext="";
	if($step==$i) $ctext=" checked=\"true\"";
	print "<input type=\"radio\" name=\"step\" value=\"$i\"$ctext>$i";
}

print <<<E
<table>
<tr>
<th>Position</th>
<th>Playername </th>
<th>new Player?</th>
</tr>
E;
 
for($i=1;$i<11;$i++)
{
	$pl = $_POST['pl'][$i-1];
	print "<tr><th>$i. Place:</th>
<th><input type=\"Text\" name=\"place[]\" value=\"$pl\"></th>
<th><input type=\"checkbox\" name=\"newplayer[]\" value=\"$i\"></th></tr>\n";

}

print <<<E
</table>
<p>Players that where reserved but missing and should loose tickets:<br>
<input type="Text" name="punish[]">
<input type="Text" name="punish[]">
<input type="Text" name="punish[]">
<input type="Text" name="punish[]">
</p>

<input type="Submit" value="Send Data"></p>
</form>
E;


/* <p>Password: <input type="password" name="pass">  */

}
else print "<p>Hello, if you are an admin, you could visit that page: <a href=\"/login.php\">(click here)</a></p>";


?>

<?php
include "footer1.php";
?>
</body>
</html>