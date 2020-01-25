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


<h1>Recent BBC Games</h1>



<?php
$ttext = date("Y-m-d H:i:s",time()-4*43200);
$request = "SELECT * FROM table1 WHERE datetime>'$ttext' ORDER BY datetime DESC, step DESC, gameno DESC";
$result = mysql_query($request);
$c=0;
while($row = mysql_fetch_object($result))
{
	$c++;
	$gameno = $row->gameno;
	$step=$row->step;
	print "<hr>";
	print "<h2>BBC Game $gameno of Step $step</h2>";
	print "<p><a href=\"/exp5/gameslist3.php?step=$step&amp;g=$gameno\">Click here for the logfile analysis</a></p>";
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
			$n= $row2->name ;
			print "<a href=/exp5/players1.php?id=$pids[$i1]>$n</a>";
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
	print "<p>Congratulations <span style=\"font-size:x-large;color:#bb8800\"><b>$pnames[0]</b></span> and 
<span style=\"font-size:large;color:#bb8800\">$pnames[1]</span><br>
Bravo <span style=\"font-size:large\"><b>$pnames[2]</b></span>
</p>";
	
	
}

?>

<?php
include "footer1.php";
?>
</body>
</html>
