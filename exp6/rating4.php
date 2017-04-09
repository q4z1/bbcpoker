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

<h1>RATING version 4.0.2</h1>

<h2> Description</h2>

<p>OK this is my new idea.<br>
The aim is to give every Player a Number (Rating) that shows how strong she/he is.<br>
We all now that there is no perfect solution that satisfies everyone for this. but i tried to find a system for this.<br>
The basic idea is: the better your place is, the more points you get. But also your opponents in each game play a role: if your oppponents are weaker than you 
(in average), then you get less points compared to the situation, where your opponents are stronger than you.<br>
I got the basic idea from "other Sports" . e.g. chess: 
<a href=https://en.wikipedia.org/wiki/Elo_rating_system>https://en.wikipedia.org/wiki/Elo_rating_system</a> <br>
</p>

<?php
//CONSTANTS
$q=(float)$_GET['q'];
$c=(float)$_GET['c'];

if($q<=0.0) $q=log(10.0)/400.0;
$q=round($q,7);
if($c<=0.0) $c=2.5;
$maxrd=(int)$_GET['maxrd'];
$minrd=(int)$_GET['minrd'];
if($maxrd<=0) $maxrd=40;
if($minrd<=0) $minrd=10;
$startrat=(int)$_GET['startrat'];
if($startrat<=0) $startrat=500.0;
$n1=$_GET['pl1'];
$n2=$_GET['pl2'];

print <<<E
<h3>Input</h3>
<form action="exp6/rating4.php" method="get">
<b>System parameters:</b><br>
<form>
q:<input type="text" value="$q" name="q" size=8>
c:<input type="text" value="$c" name="c" size=5>
<br>
maxRD:<input type="text" value="$maxrd" name="maxrd" maxlength=6 size=4>
minRD:<input type="text" value="$minrd" name="minrd" maxlength=6 size=4>
start rating:<input type="text" value="$startrat" name="startrat" maxlength=7 size=5>
<br>
E;


print <<<E
specify two players who get extra data: <input type="text" name="pl1" value="$n1"><input type="text" name="pl2" value="$n2">
<input type="submit" value="Get Test data!">
</form>
<p>Explanation to the parameters:<br>
(nothing so far...)
</p>
E;

print <<<E
<h2> Rating history </h2>
<p>it displays name, the RD, gamename ("s1g320" means step 1 game #320), rating before the game and the difference (=won/lost points)</p>
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

calcrating1(0,"2014-01-01 00:00:00",0,1,0,$q,$c,$maxrd,$minrd,1,$spid1,$spid2,$startrat);
// meaning: output in html


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
*/
/*$db1=array();
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

*/
if($spid1!=0 or $spid2!=0)
{	print "<h3>rating over time of the selected players</h3>";
	$n1=$_GET['pl1'];
	$n2=$_GET['pl2'];
	print "<p>you selected the players $n1 and $n2</p><br>\n";
	print <<<E
	<img src="exp6/temppic4.png" alt="t"><br>
	
E;



}

?>
<?php
include "footer1.php";
?>

</body>
</html>
