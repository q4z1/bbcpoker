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

<form action="/exp5/players1.php" method="get">
Input a Player Name: <input type="text" name="nick" maxlength="20">
<input type="submit" value="Get Player Statistics">
</form>

<?php

$pid = (int)$_GET['id'];
$pname = trim($_GET['nick']);
$found = 0;
if($pid>1024)
{
	$request = "SELECT name FROM table2 WHERE playerid=$pid ";
	$result = mysql_query($request);
	$row = mysql_fetch_array($result);
	if($row) $pname = $row[0];
	if($row) $found=1;
}
if($found==0)
{
	$pname2=mysql_real_escape_string($pname);
	$request="SELECT playerid,name FROM table2 WHERE name='$pname2'";
	$result = mysql_query($request);
	$row = mysql_fetch_array($result);
	if($row)$pid=(int)$row[0];
	if($row)$pname=$row[1];
	if($row) $found=1;
}
if($found==0)
{
	print "<h2>We could not find a Player</h2>
	<p>Please enter a correct username<br><small>This is case-insensitive</smal></p>";
}
if($found==1)
{
	print "<h1> Statistics of $pname </h1>";
	//print "<h2><!--<a href=\"/exp5/players1.php?id=$pid\">-->$pname</h2>";
	//print "<br><br>";
	$request="SELECT * FROM table2 WHERE playerid=$pid";
	$result=mysql_query($request);
	$row = mysql_fetch_array($result);
	if(!$row) $found=0;
	
}
if($found==1)
{
	function pc2table($pctext,$title,$mult=1)
	{
		$pcia=explode(",",$pctext);
		if(count($pcia)!=10) return "<p>Error</p>";
		$sum1=0;
		$pcfa=array(0);
		$pcpa=array(0);
		for($i1=0;$i1<10;$i1++) $sum1+=$pcia[$i1];
		$pcia[10]=$sum1;
		for($i1=0;$i1<10;$i1++)
		{
			if($sum1>0) $f1=(float)$pcia[$i1]*100.0/$sum1;
			else $f1=0;
			$pcfa[$i1]=sprintf("%.1f",$f1)."%";
			$pcpa[$i1]=$mult*(10-$i1)*$pcia[$i1];
		}
		$sum3=0;
		for($i1=0;$i1<10;$i1++) $sum3+=$pcpa[$i1];
		$pcfa[10]="100%";
		if($sum1==0) return "<p><b>$title</b>: No games were found</p>";
		if($sum1==0)$pcfa[10]="0.0%";
		$pcpa[10]=$sum3;
		
		$ret="<table><tr><th colspan=13>$title</th></tr>
		<tr><td rowspan=4><img src=\"/exp5/pic1.php?d=$pctext\" width=120 height=120 alt=\"pie\"></td>";
		for($i1=1;$i1<11;$i1++) $ret .= "<td>Place $i1</td>";
		$ret.="<td>Total</td>
		<td rowspan=4><img src=\"/exp5/pic1.php?t=1&amp;d=$pctext\" width=160 height=120 alt=\"bar\"></td></tr>
		<tr>";
		
		for($i1=0;$i1<11;$i1++) $ret.="<td>$pcia[$i1]</td>";
		$ret.="</tr>\n<tr>";
		for($i1=0;$i1<11;$i1++) $ret.="<td>$pcfa[$i1]</td>";
		$ret.="</tr>\n<tr>";
		for($i1=0;$i1<11;$i1++) $ret.="<td>$pcpa[$i1]</td>";
		$ret.="</tr></table>";
		
		return $ret;
	}
	$sscore=$row['saisonscore'];
	$sgames=$row['saisongames'];
	$spoints=$row['saisonpoints'];
	$ascore=$row['alltimescore'];
	$agames=$row['alltimegames'];
	$apoints=$row['alltimepoints'];
	if($agames>0) $appg=sprintf("%.4f",(float)$apoints/$agames);
	else $appg="0.0";
	$acoef=0;
	if($agames>1) $acoef=sprintf("%.4f",1+log((float)$agames,2));
	$ts2=$row['ts2'];
	$ts3=$row['ts3'];
	$ts4=$row['ts4'];
	$rating=$row['rating'];
	$roia=sprintf("%0.2f",$row['alltimeroi']/100);
  $roi100=sprintf("%0.2f",$row['hundredroi']/100);
	$pid2=$row['playerid'];
	$as1 = floor($ascore/1000);
	$as2 = $ascore-$as1*1000;
	$as3 = sprintf("%d.%03d",$as1,$as2);
	if($sgames>0) $sppg=sprintf("%.4f",(float)$spoints/$sgames);
	else $sppg="0.0";
	$scoef=0;
	if($sgames>1) $scoef=sprintf("%.4f",1+log((float)$sgames,2));
	$ss1 = floor($sscore/1000);
	$ss2 = $sscore-$ss1*1000;
	$ss3 = sprintf("%d.%03d",$ss1,$ss2);
	$request="SELECT COUNT(*) FROM table2 WHERE playerid>1024 AND (saisonscore>$sscore OR (saisonscore=$sscore AND saisongames<$sgames)
OR (saisongames=$sscore AND saisongames=$sgames AND (alltimescore>$ascore OR (alltimescore=$ascore AND playerid>$pid2))))";
	
	$result=mysql_query($request);
	$row2=mysql_fetch_array($result);
	$srpos=$row2[0]+1;
	if($sgames==0) $srpos="-";
	$request="SELECT COUNT(*) FROM table2 WHERE playerid>1024 AND (alltimescore>$ascore OR (alltimescore=$ascore AND alltimegames<$agames)
OR (alltimegames=$ascore AND alltimegames=$agames AND (saisonscore>$sscore OR (saisonscore=$sscore AND playerid>$pid2))))";
	$result=mysql_query($request);
	$row2=mysql_fetch_array($result);
	$arpos=$row2[0]+1;
	if($agames==0) $arpos="-";
	$beststep=0;
	$i2=20;
	for($i1=0;$i1<10;$i1++) if($apcs3[$i1]>0 and $i2==20)$i2=$i1+1;
	if($i2<19) $beststep=3;
	for($i1=0;$i1<10;$i1++) if($apcs2[$i1]>0 and $i2==20)$i2=$i1+1;
	if($i2<19 and $beststep==0) $beststep=2;
	for($i1=0;$i1<10;$i1++) if($apcs2[$i1]>0 and $i2==20)$i2=$i1+1;
	if($i2<19 and $beststep==0) $beststep=1;
	print <<<E
<table>
<tr><td>PokerTh Nickname:</td><td>$pname</td></tr>
<tr><td>PokerTH Ranking Profile:</td><td><a href="https://www.pokerth.net/leaderboard/$pname" target="_blank">Click Here</a></td></tr>
<tr><td>Tickets to Step 2:</td><td>$ts2</td></tr>
<tr><td>Tickets to Step 3:</td><td>$ts3</td></tr>
<tr><td>Tickets to Step 4:</td><td>$ts4</td></tr>
<tr><th>RATING:</th><th>$rating</th></tr>
<tr><th>ROI (All-Time):</th><th>$roia %</th></tr>
<tr><th>ROI (last 100 games):</th><th>$roi100 %</th></tr>
<tr><td>BBC-ID:</td><td><a href="/exp5/players1.php?id=$pid">$pid</a></td></tr>
</table>
E;
	if($row['settings']==1)
	{
		$hasawards=0;
		$awtext=file_get_contents("exp5/awards/awardsdata.txt");
		$awrows=explode("\n",$awtext);
		$awc1=count($awrows);
		for($i1=0;$i1<$awc1;$i1++)
		{
			$data1=explode("##",$awrows[$i1],3);
			if($data1[0]!=$pid) continue;
			if($hasawards==0) print "<h2>AWARDS</h2>\n";
			if($hasawards==0) $hasawards=1;
			print <<<E
			<p style="display: inline-block"><img src="/exp5/awards/pics/$data1[1]" alt="award" title="award"> $data1[2] </p>		
E;
		}
	}
print <<<E
<h2>All-Time Statistics</h2>
<table>
<tr><th>Position in Ranking:</th><th>$arpos</th></tr>
<tr><td>Games Played:</td><td>$agames</td></tr>
<tr><td>Games Coefficient:</td><td>$acoef</td></tr>
<tr><td>Points:</td><td>$apoints</td></tr>
<tr><td>Points per Game:</td><td>$appg</td></tr>
<tr><th>Score:</th><th>$as3</th></tr>
</table>
<br>
E;
print pc2table($row['a1placecount'],"Results in Step 1",1);
print pc2table($row['a2placecount'],"Results in Step 2",2);
print pc2table($row['a3placecount'],"Results in Step 3",3);
print pc2table($row['a4placecount'],"Results in Step 4",4);
print <<<E
<h2>Current Season Statistics</h2>
<table>
<tr><th>Position in Ranking:</th><th>$srpos</th></tr>
<tr><td>Games Played:</td><td>$sgames</td></tr>
<tr><td>Games Coefficient:</td><td>$scoef</td></tr>
<tr><td>Points:</td><td>$spoints</td></tr>
<tr><td>Points per Game:</td><td>$sppg</td></tr>
<tr><th>Score:</th><th>$ss3</th></tr>
</table>
E;
print pc2table($row['s1placecount'],"Results in Step 1",1);
print pc2table($row['s2placecount'],"Results in Step 2",2);
print pc2table($row['s3placecount'],"Results in Step 3",3);
print pc2table($row['s4placecount'],"Results in Step 4",4);

$request="SELECT * FROM table1 WHERE $pid=p1 OR $pid=p2 OR $pid=p3 OR $pid=p4 OR $pid=p5
 OR $pid=p6 OR $pid=p7 OR $pid=p8 OR $pid=p9 OR $pid=p10 ORDER BY step DESC, gameno DESC";
$result=mysql_query($request);
print '<h2>Played Games</h2>';
$cstep=4;
$c=0;
$trans=array();
while($row = mysql_fetch_object($result))
{
	//$i++;
	
	$c++;
	if($cstep>=$row->step)
	{
		if($cstep>$row->step) $cstep=$row->step;
		else print "</table>";
		print "<h3>Step $cstep</h3>";
		print "<table border=1>";
		$cstep--;
	}
	$pida = array($row->p1,$row->p2,$row->p3,$row->p4,$row->p5,$row->p6,$row->p7,$row->p8,$row->p9,$row->p10);
	$gameno=$row->gameno;
	$season = $row->season;
	$step=$cstep+1;
	print "<tr><td><a href=\"/exp5/gameslist3.php?step=$step&amp;g=$gameno\">$gameno</a></td>";
	print "<td>$season</td>";
	for($i=0;$i<10;$i++)
	{
		if($trans[$pida[$i]]=="")
		{
			$request2 = "SELECT name FROM table2 WHERE playerid='$pida[$i]'";
			$result2 = mysql_query($request2);
			while($row2 = mysql_fetch_object($result2)) $trans[$pida[$i]]=$row2->name;
		}
		if($trans[$pida[$i]]!="0")
		{
			$n = $trans[$pida[$i]];
			if($pida[$i]==$pid2) print "<th>$n</th>";
			else print "<td>$n</td>";
		}
		else print "<td></td>";
	}
	print "</tr>\n";
}
if($c>0) print "</table>";

}

?>


<?php
include "footer1.php";
?>

</body>
</html>
