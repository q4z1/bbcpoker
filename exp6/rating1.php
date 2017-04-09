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

<h1>RATING version 1.2.1</h1>

<h2> Description</h2>

<p>OK this is my new idea.<br>
The aim is to give every Player a Number (Rating) that shows how strong she/he is.<br>
We all now that there is no perfect solution that satisfies everyone for this. but i tried to find a system for this.<br>
The basic idea is: the better your place is, the more points you get. But also your opponents in each game play a role: if your oppponents are weaker than you 
(in average), then you get less points compared to the situation, where your opponents are stronger than you.<br>
I got the basic idea from "other Sports" . e.g. chess: 
<a href=https://en.wikipedia.org/wiki/Elo_rating_system>https://en.wikipedia.org/wiki/Elo_rating_system</a> <br>
</p>

<a href="http://www.codecogs.com/eqnedit.php?latex=R_i&space;\mbox{&space;is&space;the&space;rating&space;number&space;of&space;player&space;i,&space;}&space;P_i&space;\mbox{&space;is&space;her/his&space;place&space;(1&space;to&space;10),&space;and&space;}\\&space;G_i&space;\mbox{&space;is&space;the&space;number&space;of&space;games&space;of&space;that&space;player}\\&space;M&space;:=&space;\frac{1}{9}\sum_{i}{R_i}\\&space;E_i&space;:=&space;\frac{1}{1&plus;2^\frac{M-\frac{10}{9}R_i}{4000}}\\&space;S_i&space;:=&space;\left\{\begin{matrix}&space;20&space;&&space;\mbox{if&space;}&space;G_i>30\\&space;80&plus;\frac{(20-80)(G_i-1)}{30-1}&space;&&space;\mbox{else}&space;\end{matrix}&space;\right&space;\\&space;R'_i&space;:=&space;R_i&space;&plus;&space;\left&space;\lceil&space;S_i(10-P_i-9E_i)-\frac{1}{2}&space;\right&space;\rceil" target="_blank"><img src="http://latex.codecogs.com/gif.latex?R_i&space;\mbox{&space;is&space;the&space;rating&space;number&space;of&space;player&space;i,&space;}&space;P_i&space;\mbox{&space;is&space;her/his&space;place&space;(1&space;to&space;10),&space;and&space;}\\&space;G_i&space;\mbox{&space;is&space;the&space;number&space;of&space;games&space;of&space;that&space;player}\\&space;M&space;:=&space;\frac{1}{9}\sum_{i}{R_i}\\&space;E_i&space;:=&space;\frac{1}{1&plus;2^\frac{M-\frac{10}{9}R_i}{4000}}\\&space;S_i&space;:=&space;\left\{\begin{matrix}&space;20&space;&&space;\mbox{if&space;}&space;G_i>30\\&space;80&plus;\frac{(20-80)(G_i-1)}{30-1}&space;&&space;\mbox{else}&space;\end{matrix}&space;\right&space;\\&space;R'_i&space;:=&space;R_i&space;&plus;&space;\left&space;\lceil&space;S_i(10-P_i-9E_i)-\frac{1}{2}&space;\right&space;\rceil" title="R_i \mbox{ is the rating number of player i, } P_i \mbox{ is her/his place (1 to 10), and }\\ G_i \mbox{ is the number of games of that player}\\ M := \frac{1}{9}\sum_{i}{R_i}\\ E_i := \frac{1}{1+2^\frac{M-\frac{10}{9}R_i}{4000}}\\ S_i := \left\{\begin{matrix} 20 & \mbox{if } G_i>30\\ 80+\frac{(20-80)(G_i-1)}{30-1} & \mbox{else} \end{matrix} \right \\ R'_i := R_i + \left \lceil S_i(10-P_i-9E_i)-\frac{1}{2} \right \rceil" /></a>


<!--<h2> Rating history </h2>

<table border=1>
<tr><th></th><th>Place 1</th><th>Place 2</th><th>Place 3</th><th>Place 4</th>
<th>Place 5</th><th>Place 6</th><th>Place 7</th><th>Place 8</th>
<th>Place 9</th><th>Place 10</th><th></th></tr>-->
<?php
//CONSTANTS
$startrating = (int)$_GET['cr0'];
if($startrating==0) $startrating=5000;
$probdiff=(float)$_GET['cd'];
if($probdiff==0)$probdiff=(float)4000;
//$speed=30;
$startspeed= (float)$_GET['cs1'];
if($startspeed==0) $startspeed= 80;
$minspeed = (float)$_GET['csm'];
if($minspeed==0)$minspeed = 20;

$mingames=(int)$_GET['cgm'];
if($mingames==0)$mingames=30;
//$rounding = 1;
//if($GET['ceil']==1)$rounding = 0;

print <<<E
<h2> Parameters</h2>
<form action="exp6/rating1.php" method=get>
Start Rating:<input type="text" name="cr0" value=$startrating><br>
difference between players (: <input type="text" name="cd" value=$probdiff><br>
Speed at Start: <input type="text" name="cs1" value=$startspeed>
Normal Speed: <input type="text" name="csm" value=$minspeed><br>
first games: <input type="text" name="cgm" value=$mingames><br>
<!--round up/Ceil points: <input type="text" name="ceil" value=$rounding><br>-->
<input type="submit" value="Get Test data!">
</form>
E;

$time1=time();


print <<<E
<h2> Rating history </h2>

<table border=1>
<tr><th></th><th>Place 1</th><th>Place 2</th><th>Place 3</th><th>Place 4</th>
<th>Place 5</th><th>Place 6</th><th>Place 7</th><th>Place 8</th>
<th>Place 9</th><th>Place 10</th><th></th></tr>
E;

$request = "SELECT playerid, name FROM table2";
$result = mysql_query($request);
$pnames=array();
$maxid = 0;
while($row=mysql_fetch_object($result))
{
	$pnames[$row->playerid] = $row->name;
	if($maxid < $row->playerid) $maxid = $row->playerid;
}
$rating = array();
$games = array();
for($i1=1024;$i1<$maxid+5;$i1++) $rating[$i1]=$startrating;
for($i1=1024;$i1<$maxid+5;$i1++) $games[$i1]=0;
$request = "SELECT * FROM table1 ";
$result = mysql_query($request);
$expect = array();
while($row=mysql_fetch_object($result))
{
	$place=array(0,$row->p1,$row->p2,$row->p3,$row->p4,$row->p5,$row->p6,$row->p7,$row->p8,$row->p9,$row->p10);
	$MMM = 0.0;
	print "<tr><td colspan=12></td></tr>\n";
	print "<tr><td></td>";
	for($i1=1;$i1<=10;$i1++) 
	{
		$n= $pnames[$place[$i1]];
		print "<td>$n</td>";
	}
	print "<td></td></tr>\n<tr><td></td>";
	$ga=array();
	for($i1=1;$i1<=10;$i1++)
	{
		$games[$place[$i1]]++;
		$r = $rating[$place[$i1]];
		$ga[$i1] = $games[$place[$i1]];
		$g = $ga[$i1];
		print "<td>$r($g)</td>";
	}
	print "<td></td></tr>\n<tr><td></td>";
	for($i1=1;$i1<=10;$i1++) $MMM += $rating[$place[$i1]];
	$MMM = (float)$MMM/9.0;
	for($i1=1;$i1<=10;$i1++)
	{
		$expect = 1.0/(1+pow(2,($MMM-$rating[$place[$i1]]*10.0/9.0)/$probdiff));
		if($ga[$i1]<$mingames) $speed2 = (float)$startspeed + (float)($minspeed-$startspeed)*($ga[$i1]-1)/($mingames-1);
		if($ga[$i1]>=$mingames)$speed2=$minspeed;
		$add = (int) ceil($speed2*(10-$i1 - 9*$expect) -0.5	);
		print "<td>$add</td>";
		$rating[$place[$i1]] += $add;
	}
	print "<td></td></tr>";
}

print "</table>\n";
$timediff= time()-$time1;
print "<p><b>DIFFERENCE: $timediff SECONDS</b></p>";
function swap(&$db, $x1,$x2)
{
	$c=$db[$x2];
	$db[$x2]=$db[$x1];
	$db[$x1] = $c;
}
function quicksort1(&$db,$rating,$start,$end,$time1,&$pfc)
{	
	if($end <= $start) return;
	$td = time()-$time1;
	
	$ps = "($start, " . $rating[$start] . " , $end , ". $rating[$end] . " , $td , $pfc )\n";
	$pfc+=3;
	print $ps;
	//$pivot=(int)($start+$end)/2;
	//swap($db, $start, $pivot);
	$pivot = $rating[$start];
	//print "$pivot[1]";
	$left = $start + 1;
	$right = $end;
	while(1) 
	{
		while($rating[$left]>$pivot and $left <$right) {$left++;$pfc++;}
		if($right == $left) break;
		while($rating[$right]<$pivot and $left <$right) {$right--;$pfc++;}
		if($right == $left) break;
		swap($db,$right , $left);
		swap($rating,$right , $left);
	}
	if($rating[$right]<$pivot) {$right--;$pfc++;}
	if($right==$start) 
	{
		quicksort1($db,$rating,$start+1,$end,$time1,$pfc);
		return;
	}
	if($rating[$right]<$pivot) print "<p>ERRRRRRORp 39ctn 3ictlhug34tgc34</p>";
	swap($db, $start,$right);
	swap($rating, $start,$right);
	$pfc++;
	quicksort1($db,$rating,$start,$right-1,$time1,$pfc);
	quicksort1($db,$rating,$right+1,$end,$time1,$pfc);
	return;

}

$db1=array();
$rating2=array();
$i2=0;
for($i1=1024;$i1<=$maxid;$i1++) {
	if($games[$i1]>29)
	{
		$db1[$i2]=$i1-1024;
		$i2++;
	}
}
$maxpp=$i2-1;
for($i1=0;$i1<=$maxpp;$i1++) $rating2[$i1]=$rating[$db1[$i1]+1024];
	
print "<pre>";
print $maxpp;
$pfcount = 0;
quicksort1($db1,$rating2,0,$maxpp,$time1,$pfcount);
//quicksort1($db1,$rating,0,$maxid-1024);
for($i1=1024;$i1<=$maxid;$i1++) $db1[$i1-1024]+=1024;
print "</pre>";
print "<h2>Table of players</h2>\n<table border=1>";
print "<tr><th>#</th><th>Name</th><th>Games</th><th>Rating</th>";
for($i1=0;$i1<=$maxpp;$i1++)
{
	$p=$i1+1;
	$n=$pnames[$db1[$i1]];
	$r=$rating[$db1[$i1]];
	$g=$games[$db1[$i1]];
	print "<tr><td>$p</td><td>$n</td><td>$g</td><td>$r</td></tr>\n";

}

print "</table>";
?>
<?php
include "footer1.php";
?>

</body>
</html>
