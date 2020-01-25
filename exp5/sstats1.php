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

$request = "SELECT MAX(gameno) FROM table1 WHERE step = '3'";
$result=mysql_query($request);
$row=mysql_fetch_array($result);
$maxseason = $row[0] +1;
$season = (int)$_GET['season'];

if($season<1 or ($season>$maxseason and $season < 2012) or $season>2023) $season = $maxseason-1;


print "<form action=\"exp5/sstats1.php\" method=\"get\">
Select a Season: ";
for($i1 = 1;$i1<2015;$i1++)
{	
	if($i1==$maxseason)
	{	
		$i1=2013;
		print "<br>Or a Year:";
	}
	if($season == $i1) $ctext = " checked";
	else $ctext="";
	print "<input type=\"radio\" name=\"season\" value=\"$i1\"$ctext>$i1 \n";
}

print "<br>
<input type=\"submit\" value=\"Get Statistics!\">
</form>
";

$url = $_SERVER['REQUEST_URI'];

print "<h1> Season $season - Statistics</h1>";
print "<p><a href=\"/$url#r1\">Ranking</a><br>
<a href=\"/$url#s1\">Games of Step 1</a><br>
<a href=\"/$url#s2\">Games of Step 2</a><br>
<a href=\"/$url#s3\">Games of Step 3</a></p>
";
$sort1=$_GET['sort'];
$sort2=3;
if((int)$sort1 < 3 and (int)$sort1>0) $sort2=(int)$sort1;
$sort3 = array("","Points","Games","Score")[$sort2];

print "<p>The following table is sorted by <b>$sort3</b>. You can choose another ranking:</p>";
print "<p>";
if ($sort2 !=1) print " <a href=\"/exp5/sstats1.php?season=$season&amp;sort=1\">Sort by Points</a> ";
if ($sort2 !=2) print " <a href=\"/exp5/sstats1.php?season=$season&amp;sort=2\">Sort by Games</a> ";
if ($sort2 !=3) print " <a href=\"/exp5/sstats1.php?season=$season&amp;sort=3\">Sort by Score</a> ";
print "</p>";

?>

<h2 id="r1">Table of Players - Detailed Season Ranking </h2>
<table border=1>
<tr><th rowspan=2>Pos.</th>
<th rowspan=2>Playername</th>
<th rowspan=2>Step</th>
<th colspan="10"><b>Positions</b></th>
<?php
print "<th rowspan=2><a href=\"/exp5/sstats1.php?season=$season&amp;sort=2\">Games</a></th>
<th rowspan=2><a href=\"/exp5/sstats1.php?season=$season&amp;sort=1\">Points</a></th>
<th rowspan=2><a href=\"/exp5/sstats1.php?season=$season&amp;sort=3\">Score</a></th>";
?>
</tr>
<tr>
<th> 1</th>
<th> 2</th>
<th> 3</th>
<th> 4</th>
<th> 5</th>
<th> 6</th>
<th> 7</th>
<th> 8</th>
<th> 9</th>
<th>10</th>
</tr>

<?php
$request = "SELECT * FROM table1 WHERE season=$season ORDER BY step, gameno";
function calcpc($step,$season,$pid)
{
	$pcarray=array(0,0,0,0,0,0,0,0,0,0); 	
	$seasontext="";
	if($season >0) $seasontext=" AND season='$season'";
	if ($season > 2012) $seasontext = "AND datetime < '$season-12-31 23:59:59' AND datetime>'$season-01-01 00:00:00'";
	for($i=1;$i<11;$i++)
	{
		$request = "SELECT COUNT(*) FROM table1 WHERE p$i ='$pid' AND step='$step' $seasontext";
		$result = mysql_query($request) or die("error123ohui132");
		$row = mysql_fetch_array($result);
		$pcarray[$i-1]=$row[0];
	}
	return implode(",",$pcarray);
}
$error=0;
$error2 = 0;
$request="SELECT * FROM table2 ORDER BY playerid ASC";
$result = mysql_query($request);
function ptsbypc($pc)
{
	$retval=0;
	$pcarray=explode(",",$pc);
	for($i=0;$i<10;$i++) $retval += (10-$i)*((int)$pcarray[$i]);
	return $retval;
}
function countgames($pc1,$pc2,$pc3)
{
		$retval=0;
		$pcarray=explode(",",$pc1);
		for($i=0;$i<10;$i++) $retval += (int)$pcarray[$i];
		$pcarray=explode(",",$pc2);
		for($i=0;$i<10;$i++) $retval += (int)$pcarray[$i];
		$pcarray=explode(",",$pc3);
		for($i=0;$i<10;$i++) $retval += (int)$pcarray[$i];
		return $retval;
}
function calcscore($points, $games)//number of games
{	
		if($games<=0 or $points<=0) return 0;
		$coefficient = 1 + log((float)$games, 2);//logarithm with base 2
		$score =(float)$points* $coefficient /(float)$games;
		return (int)($score*1000);
}

$i1=0;
$db1 = array();
while($row=mysql_fetch_object($result)	and $error==0)
{
	$i1++;
	$pid = $row->playerid;
	if($pid <1024) $error=201;
	$pname = $row->name;	
	$s1pc = calcpc(1, $season, $pid);
	$s2pc = calcpc(2, $season, $pid);
	$s3pc = calcpc(3, $season, $pid);
	$spoints = ptsbypc($s1pc)+2*ptsbypc($s2pc)+4*ptsbypc($s3pc);
	$sgames = countgames($s1pc,$s2pc,$s3pc);
	$sscore = calcscore($spoints, $sgames);
	$db1[] = array($pid, $pname, $s1pc, $s2pc, $s3pc,$spoints, $sgames, $sscore);
}

function comp($a1,$a2, $crit)
{
	if($a1[$crit] > $a2[$crit]) return -1;
	if($a1[$crit] < $a2[$crit]) return 1;
	if($a1[7] > $a2[7]) return -1;
	if($a1[7] < $a2[7]) return 1;
	if($a1[6] > $a2[6]) return 1;
	if($a1[6] < $a2[6]) return -1;
	if($a1[0] > $a2[0]) return -1;
	else return 1;
}
function swap(&$db, $x1,$x2)
{
	$c=$db[$x2];
	$db[$x2]=$db[$x1];
	$db[$x1] = $c;
}

function quicksort(&$db , $start, $end, $crit)
{
	$pivot = $db[$start];
	$left = $start + 1;
	$right = $end;
	if($right < $left) return;
	while(1) 
	{
		while(comp($db[$left],$pivot,$crit)==-1 and $left <$right) $left++;
		if($right == $left) break;
		while(comp($db[$right],$pivot,$crit)==1 and $left <$right) $right--;
		if($right == $left) break;
		swap($db,$right , $left);
	}
	if(comp($db[$right],$pivot,$crit)==1) $right--;
	if($right==$start) 
	{
		quicksort($db,$start+1,$end,$crit);
		return;
	}
	if(comp($db[$right],$pivot,$crit)==1) print "<p>ERRRRRRORp 39ctn 3ictlhug34tgc34</p>";
	swap($db, $start,$right);
	quicksort($db,$start,$right-1,$crit);
	quicksort($db,$right+1,$end,$crit);
	return;
}
$db2 = $db1;
quicksort($db1,0,count($db1)-1,$sort2 + 4);

$keinspiel="0,0,0,0,0,0,0,0,0,0";
$spanfeld = array("",""," rowspan=2"," rowspan=3");
$i3 = 0;
for($i1 = 0;$i1<count($db2);$i1++)
{
	$pname=$db1[$i1][1];
	$spoints=$db1[$i1][5];
	$s1pc = $db1[$i1][2];
	$s2pc = $db1[$i1][3];
	$s3pc = $db1[$i1][4];
	$sgames = $db1[$i1][6];
	$sscore=$db1[$i1][7];
	$span=3;
	if($s3pc == $keinspiel) $span--;
	if($s2pc == $keinspiel) $span--;
	if($s1pc == $keinspiel) $span--;
	if($span ==0 or $db1[$i1][0]<1025	) continue;
	$i3++;
	$s = $spanfeld[$span];
	$ss1 = floor($sscore/1000);
	$ss2 = $sscore-$ss1*1000;
	$ss3 = sprintf("%d.%03d",$ss1,$ss2);
	$endofthisrow = "<td$s>$sgames</td><td$s>$spoints</td><td$s>$ss3</td></tr>\n";
	print "<tr><td$s>$i3</td><th$s>$pname</th>";
	
	if($s1pc != $keinspiel)
	{	print "<td>1</td>";
		$pcarray=explode(",",$s1pc);
		for($i2=0;$i2<10;$i2++)print "<td>$pcarray[$i2]</td>";
		print $endofthisrow;
	}
	if($s2pc != $keinspiel)
	{	
		if($s1pc!=$keinspiel) print "<tr>";
		print "<td>2</td>";
		$pcarray=explode(",",$s2pc);
		for($i2=0;$i2<10;$i2++)print "<td>$pcarray[$i2]</td>";
		if($s1pc==$keinspiel) print $endofthisrow;
	}
	if($s3pc != $keinspiel)
	{	
		if($s1pc!=$keinspiel or $s2pc!=$keinspiel) print "<tr>";
		print "<td>3</td>";
		$pcarray=explode(",",$s3pc);
		for($i2=0;$i2<10;$i2++)print "<td>$pcarray[$i2]</td>";
		if($s1pc==$keinspiel and $s2pc==$keinspiel)print $endofthisrow;
	}
}

print "</table>";
print "<p id=\"s1\"><a href=\"/$url#\">Back to Top</a></p>";

$step=1;
for($step=1;$step<4;$step++)
{
print "<br><h2> List of Games in Step $step </h2><br>\n";
if($season >0) $seasontext=" AND season='$season'";
if ($season > 2012) $seasontext = "AND datetime < '$season-12-31 23:59:59' AND datetime>'$season-01-01 00:00:00'";
$request = "SELECT * FROM table1 WHERE step='$step' $seasontext ORDER BY gameno ASC";
$result = mysql_query($request);
$trans=array();
print "<table border=1>";
print "<tr><th>#</th>";
print "<th>Winner</th>";
for($i=2;$i<11;$i++) print "<th> Place $i</th>";
print "</tr>";
while($row = mysql_fetch_object($result))
{
	$pid = array($row->p1,$row->p2,$row->p3,$row->p4,$row->p5,$row->p6,$row->p7,$row->p8,$row->p9,$row->p10);
	$gameno=$row->gameno;
	print "<tr><td>$gameno</td>";
	for($i=0;$i<10;$i++)
	{
		if($trans[$pid[$i]]=="")
		{
			$request2 = "SELECT name FROM table2 WHERE playerid='$pid[$i]'";
			$result2 = mysql_query($request2);
			while($row2 = mysql_fetch_object($result2)) $trans[$pid[$i]]=$row2->name;
		}
		if($trans[$pid[$i]]!="0")
		{
			$n = $trans[$pid[$i]];
			
			print "<td>$n</td>";
			
		}
		else print "<td></td>";
	}
	print "</tr>";
}
print "</table>";

$s = $step+1;
if($step<3)print "<p id=\"s$s\"><a href=\"/$url#\">Back to Top</a></p>";
}
?>

<?php
include "footer1.php";
?>

</body>
</html>