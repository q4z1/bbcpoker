<?php
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
function printform($step,$gameno=0)
{
	if($gameno==0)$gameno="";
	$c1 = "";$c2 = "";$c3 = "";$c4 = "";
	if($step==1) $c1 = " checked";
	if($step==2) $c2 = " checked";
	if($step==3) $c3 = " checked";
	if($step==4) $c4 = " checked";
	print "
<form action = \"exp5/gameslist3.php\" method=\"get\">
Select Step: 
<input type=\"radio\" name=\"step\" value=\"1\"$c1>1
<input type=\"radio\" name=\"step\" value=\"2\"$c2>2
<input type=\"radio\" name=\"step\" value=\"3\"$c3>3
<input type=\"radio\" name=\"step\" value=\"4\"$c4>4
Game Number: <input type=\"text\" name=\"g\" value=\"$gameno\" maxlength=4 size=5>
<input type=\"submit\" value=\"Get Game Data\">
</form>";
}

print "<h1>Statistics of Games</h1>";
$gameno = (int)$_GET['g'];
$step = (int)$_GET['step'];
if($step<0)$step=0;
if($gameno<0)$gameno=0;
$request = "SELECT * FROM table1 WHERE step=$step and gameno=$gameno";
$result = mysql_query($request);
$c=0;
//$row=false;
while($row = mysql_fetch_object($result))
{
	$row2=$row;
	$c++;
	
}

printform($step,$gameno);
if($c==0)
{
	if($step+$gameno==0)print "<h2> No Game was selected </h2>";
	else print "<h2> The Game $gameno of Step $step could not be found</h2>";
}
else 
{	
	print "<h2>BBC Game $gameno of Step $step</h2>";
	$pids = array($row2->p1,$row2->p2,$row2->p3,$row2->p4,$row2->p5,$row2->p6,$row2->p7,$row2->p8,$row2->p9,$row2->p10);
	print "<table border=1><tr><th>Winner</th>";
	for($i1=2;$i1<11;$i1++) print "<td>Place $i1</td>";
	print "</tr>\n<tr>";
	for($i1=0;$i1<10;$i1++)
	{
		print "<td>";
		$request = "SELECT name FROM table2 WHERE playerid=$pids[$i1] AND playerid>1024";
		$result = mysql_query($request);
		while($row = mysql_fetch_object($result))
		{
			$n= $row->name ;
			print "<a href=/exp5/players1.php?id=$pids[$i1]>$n</a>";
		}
		print "</td>";	
	}
	print "</tr></table>";
	print "<br><br><a href=/exp6/getcode1.php?s=$step&g=$gameno>BBCode</a>";
	print "<p>Season: <b>";
	print $row2->season;
	print "</b>, ";
	$time = $row2->datetime;
	if(strpos($time,"00:00:01")===false) $dtt = "<b>" . date("D, d M Y H:i T",strtotime($time)) ."</b>";
	else $dtt = "unknown";
	print "Begin: $dtt, ";
	$time = $row2->inputtime;
	if(strpos($time,"2000-01-01")===false) $dtt = "<b>" . date("D, d M Y H:i T",strtotime($time)) ."</b>";
	else $dtt = "unknown";
	print "Input: $dtt</p>";
	$lfname="logfiles/BBC$gameno" . "Step$step.html";
	require_once $_SERVER['DOCUMENT_ROOT'].'/defines.php';
chdir(ROOT_DIR);
	if(!file_exists($lfname))$lfname="exp6/lf3/g$gameno"."s$step.html";
	if(file_exists($lfname)) print "<iframe src=\"$lfname\" height=600 style=\"width:90%; \"></iframe>";
	else print "<p>We could not find the Logfile Analysis</p>";
}

?>


<?php
include "footer1.php";
?>

</body>
</html>
