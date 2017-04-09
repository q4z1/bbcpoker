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

<h1>RATING version 5.0.1</h1>
<!--<p>noobratingâ¢</p> -->
<h2> Description</h2>

<p>OK this is my new idea.<br>
The aim is to give every Player a Number (Rating) that shows how strong she/he is.<br>
We all now that there is no perfect solution that satisfies everyone for this. but i tried to find a system for this.<br>
<!--The basic idea is: the better your place is, the more points you get. But also your opponents in each game play a role: if your oppponents are weaker than you 
(in average), then you get less points compared to the situation, where your opponents are stronger than you.<br>-->
I got the basic idea from "other Sports" . e.g. chess: 
<a href=https://en.wikipedia.org/wiki/Elo_rating_system>https://en.wikipedia.org/wiki/Elo_rating_system</a> <br>
</p>
<h3>How does it work? what do you need to know?</h3>
<p>
You do not need to know the full details on how the rating will be computed, because this is very complicated. Here are some basic principles:
<ul>
<li>you start with a number of rating points. after each game you will win or loose points, depending on how good you play.</li>
<li>the place you get in a game has a big influence on how many points you make.</li>
<li>If you play against players with a higher rating than yours and are successful, you get many rating points</li>
<li>If you play against players with a higher rating than yours and are not successful, you loose a few rating points</li>
<li>If you play against players with a lower rating than yours and are successful, you win a few rating points</li>
<li>If you play against players with a lower rating than yours and are not successful, you loose many rating points</li>
</ul>
Also, i made a page wher you can enter the rating of all players and then you get an example calculation for one game:
<a href="http://bbcpoker.bplaced.net/exp4/formula5.php">(click here)</a>. 
I published the source code there too, so you can try to understand the detailed computation (again, this is not necessary)<br>
</p>
<!--
<a href="http://www.codecogs.com/eqnedit.php?latex=R_i&space;\mbox{&space;is&space;the&space;rating&space;number&space;of&space;player&space;i,&space;}&space;P_i&space;\mbox{&space;is&space;her/his&space;place&space;(1&space;to&space;10),&space;and&space;}\\&space;G_i&space;\mbox{&space;is&space;the&space;number&space;of&space;games&space;of&space;that&space;player}\\&space;\\&space;E_i&space;:=&space;\frac{45 \cdot 2^{\frac{R_i}{4000}}}{\sum_{j=1}^{10}2^{\frac{R_j}{4000}}}\\&space;S_i&space;:=&space;\left\{\begin{matrix}&space;20&space;&&space;\mbox{if&space;}&space;G_i>30\\&space;80&plus;\frac{(20-80)(G_i-1)}{30-1}&space;&&space;\mbox{else}&space;\end{matrix}&space;\right&space;\\&space;R'_i&space;:=&space;R_i&space;&plus;&space;\left&space;\lceil&space;S_i(10-P_i-E_i)-\frac{1}{2}&space;\right&space;\rceil" target="_blank"><img src="http://latex.codecogs.com/gif.latex?R_i&space;\mbox{&space;is&space;the&space;rating&space;number&space;of&space;player&space;i,&space;}&space;P_i&space;\mbox{&space;is&space;her/his&space;place&space;(1&space;to&space;10),&space;and&space;}\\&space;G_i&space;\mbox{&space;is&space;the&space;number&space;of&space;games&space;of&space;that&space;player}\\&space;\\&space;E_i&space;:=&space;\frac{45 \cdot 2^{\frac{R_i}{4000}}}{\sum_{j=1}^{10}2^{\frac{R_j}{4000}}}\\&space;S_i&space;:=&space;\left\{\begin{matrix}&space;20&space;&&space;\mbox{if&space;}&space;G_i>30\\&space;80&plus;\frac{(20-80)(G_i-1)}{30-1}&space;&&space;\mbox{else}&space;\end{matrix}&space;\right&space;\\&space;R'_i&space;:=&space;R_i&space;&plus;&space;\left&space;\lceil&space;S_i(10-P_i-E_i)-\frac{1}{2}&space;\right&space;\rceil"  /></a>
-->

<?php

//CONSTANTS
$startrating = (int)$_GET['startrating'];
if($startrating==0) $startrating=5000;
$probdiff=(int)$_GET['dif'];
if($probdiff==0)$probdiff=3000;
//$speed=30;
$startspeed= (int)$_GET['start'];
if($startspeed==0) $startspeed= 80;
$minspeed = (int)$_GET['speed'];
if($minspeed==0)$minspeed = 35;

$mingames=(int)$_GET['mg'];
if($mingames==0)$mingames=30;

$winbonus=(float)$_GET['win'];
// @YYY new in v5: win bonus

if($winbonus<= 0.0) $winbonus=1.17;

$winbonus=round($winbonus,3);
//$rounding = 1;
//if($GET['ceil']==1)$rounding = 0;

$n1=$_GET['pl1'];
$n2=$_GET['pl2'];


print <<<E
<h2> Parameters</h2>
<form action="exp6/rating5.php" method=get>
Start Rating:<input type="text" name="startrating" value=$startrating><br>
difference between players  <input type="text" name="dif" value=$probdiff><br>
Speed at Start: <input type="text" name="start" value=$startspeed>
Normal Speed: <input type="text" name="speed" value=$minspeed><br>
first games: <input type="text" name="mg" value=$mingames><br>
Bonus for winners: <input type="text" name="win" value=$winbonus><br>
specify two players who get extra data: <input type="text" name="pl1" value="$n1"><input type="text" name="pl2" value="$n2">
<input type="submit" value="Get Test data!">
</form>
<p>Explanation to the parameters:<br>
<b>Start Rating</b>: the rating points everyone starts with, it doesnt make huge difference, rather a matter of taste<br>
<b>difference between players</b>: if this value is small, the score of different people will be more close to each other. <br>
<b>speed</b>: this describes, how many points can you win or loose in a game.<br>
<b>speed at start/first games</b>:the "speed" will be higher in the first games, so players can get to their true rating quicker. After the "first games" there will be "normal speed"<br>
<b>Bonus for winners</b>: if this is higher, winners will get more rating points. others will lose more. this should not be lower than 1.0<br>
note: the combination of the different parameters plays a role. example:<br>
<b>speed/difference together</b>: if there is a high speed and small difference between players, the latest games are much more significant, but have in mind, that the rating should be something current and not sth of games long ago.
</p>
E;



print <<<E
<h2> Rating history </h2>
<p>it displays name, the number of games that player played in "( )", gamenumber and step (first column), 
rating after the game and the difference (=won/lost points).</p>
<!--<table border=1>
<tr><td>game</td><th>Place 1</th><th>Place 2</th><th>Place 3</th><th>Place 4</th>
<th>Place 5</th><th>Place 6</th><th>Place 7</th><th>Place 8</th>
<th>Place 9</th><th>Place 10</th></tr>-->
E;

$request = "SELECT playerid, name FROM table2";
$result = mysql_query($request);
$spid2=0;
$spid1=0;
while($row=mysql_fetch_object($result))
{
	if ($row->name == $_GET['pl1']) $spid1=$row->playerid;
	if ($row->name == $_GET['pl2']) $spid2=$row->playerid;
}

include "exp6/func3.php";

calcrating2(0,$to="2017-05-01 00:00:00",0,0,1,$spid1,$spid2,
$probdiff,$startspeed,$minspeed,$mingames,$winbonus,$startrating);



/*
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
	//print $ps;
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
print "<tr><th>#</th><th>Name</th><th>Games</th><th>Rating</th><th>Stars</th>";
$cmaxr=$rating[$db1[0]];
for($i1=0;$i1<=$maxpp;$i1++)
{
	$p=$i1+1;
	$n=$pnames[$db1[$i1]];
	$r=$rating[$db1[$i1]];
	$g=$games[$db1[$i1]];
	$stars=10 - floor((0.5 - 1.0/(1+pow(2.0,($cmaxr-$r)/(float)$probdiff)))/0.05); 
	print "<tr><td>$p</td><td>$n</td><td>$g</td><td>$r</td><td>$stars</td></tr>\n";

}

print "</table>";
if($spid1!=998 and $spid2!=998)
{	print "<h3>rating over time of the two selected players</h3>";
	$n1=$_GET['pl1'];
	$n2=$_GET['pl2'];
	print "<p>you selected the players $n1 and $n2</p><br>\n";
	print <<<E
	<img src="exp6/temppic1.png" alt="t"><br>
	<img src="exp6/temppic2.png" alt="t"><br>
	<img src="exp6/temppic3.png" alt="t"><br>
	
E;
*/
/*
print "<h2> Rating table of players </h2>";

print "<table border=1><tr><th>#</th><th>Player</th><th>Rating</th></tr>";


$request="SELECT name,rating FROM table2 WHERE rating>1000 ORDER BY rating DESC";
$result=mysql_query($request);
$i1=0;
while($row=mysql_fetch_object($result))
{
	$i1++;
	print "<tr><td>$i1</td><td>" . $row->name . "</td><td>" . $row->rating . "</td></tr>\n";
}
print "</table>";
*/

if($spid1!=0 or $spid2!=0)
{	print "<h3>rating over time of the selected players</h3>";
	$n1=$_GET['pl1'];
	$n2=$_GET['pl2'];
	print "<p>you selected the players $n1 and $n2</p><br>\n";
	print <<<E
	<img src="exp6/temppic5.png" alt="t"><br>
	
E;



}

?>
<?php
include "footer1.php";
?>

</body>
</html>
