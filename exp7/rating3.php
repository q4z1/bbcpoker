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

<h1>RATING version 3.0.1</h1>

<h2> Description</h2>

<p>OK this is my new idea.<br>
The aim is to give every Player a Number (Rating) that shows how strong she/he is.<br>
We all now that there is no perfect solution that satisfies everyone for this. but i tried to find a system for this.<br>
The basic idea is: the better your place is, the more points you get. But also your opponents in each game play a role: if your oppponents are weaker than you 
(in average), then you get less points compared to the situation, where your opponents are stronger than you.<br>
I got the basic idea from "other Sports" . e.g. chess: 
<a href=https://en.wikipedia.org/wiki/Elo_rating_system>https://en.wikipedia.org/wiki/Elo_rating_system</a> <br>
</p>
<!--
<a href="http://www.codecogs.com/eqnedit.php?latex=R_i&space;\mbox{&space;is&space;the&space;rating&space;number&space;of&space;player&space;i,&space;}&space;P_i&space;\mbox{&space;is&space;her/his&space;place&space;(1&space;to&space;10),&space;and&space;}\\&space;G_i&space;\mbox{&space;is&space;the&space;number&space;of&space;games&space;of&space;that&space;player}\\&space;\\&space;E_i&space;:=&space;\frac{45 \cdot 2^{\frac{R_i}{4000}}}{\sum_{j=1}^{10}2^{\frac{R_j}{4000}}}\\&space;S_i&space;:=&space;\left\{\begin{matrix}&space;20&space;&&space;\mbox{if&space;}&space;G_i>30\\&space;80&plus;\frac{(20-80)(G_i-1)}{30-1}&space;&&space;\mbox{else}&space;\end{matrix}&space;\right&space;\\&space;R'_i&space;:=&space;R_i&space;&plus;&space;\left&space;\lceil&space;S_i(10-P_i-E_i)-\frac{1}{2}&space;\right&space;\rceil" target="_blank"><img src="http://latex.codecogs.com/gif.latex?R_i&space;\mbox{&space;is&space;the&space;rating&space;number&space;of&space;player&space;i,&space;}&space;P_i&space;\mbox{&space;is&space;her/his&space;place&space;(1&space;to&space;10),&space;and&space;}\\&space;G_i&space;\mbox{&space;is&space;the&space;number&space;of&space;games&space;of&space;that&space;player}\\&space;\\&space;E_i&space;:=&space;\frac{45 \cdot 2^{\frac{R_i}{4000}}}{\sum_{j=1}^{10}2^{\frac{R_j}{4000}}}\\&space;S_i&space;:=&space;\left\{\begin{matrix}&space;20&space;&&space;\mbox{if&space;}&space;G_i>30\\&space;80&plus;\frac{(20-80)(G_i-1)}{30-1}&space;&&space;\mbox{else}&space;\end{matrix}&space;\right&space;\\&space;R'_i&space;:=&space;R_i&space;&plus;&space;\left&space;\lceil&space;S_i(10-P_i-E_i)-\frac{1}{2}&space;\right&space;\rceil"  /></a>
-->

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
$n1=$_GET['pl1'];
$n2=$_GET['pl2'];



$c1=$probdiff;
$c2=$startspeed;
$c3=$minspeed;
$c4=$mingames;
/*
print <<<E
<a href="http://www.codecogs.com/eqnedit.php?latex=R_i&space;\\mbox{&space;is&space;the&space;rating&space;number&space;of&space;player&space;i,&space;}&space;P_i&space;\\mbox{&space;is&space;her/his&space;place&space;(1&space;to&space;10),&space;and&space;}\\\\&space;G_i&space;\\mbox{&space;is&space;the&space;number&space;of&space;games&space;of&space;that&space;player}\\\\&space;\\\\&space;E_i&space;:=&space;\\frac{45 \\cdot 2^{\\frac{R_i}{{$c1}}}}{\\sum_{j=1}^{10}2^{\\frac{R_j}{{$c1}}}}\\\\&space;S_i&space;:=&space;\\left\\{\\begin{matrix}&space;{$c3}&space;&&space;\\mbox{if&space;}&space;G_i>{$c4}\\\\&space;{$c2}&plus;\\frac{({$c3}-{$c2})(G_i-1)}{{$c4}-1}&space;&&space;\\mbox{else}&space;\\end{matrix}&space;\\right&space;\\\\&space;R'_i&space;:=&space;R_i&space;&plus;&space;\\left&space;\\lceil&space;S_i(10-P_i-E_i)-\\frac{1}{2}&space;\\right&space;\\rceil" target="_blank"><img src="http://latex.codecogs.com/gif.latex?R_i&space;\\mbox{&space;is&space;the&space;rating&space;number&space;of&space;player&space;i,&space;}&space;P_i&space;\\mbox{&space;is&space;her/his&space;place&space;(1&space;to&space;10),&space;and&space;}\\\\&space;G_i&space;\\mbox{&space;is&space;the&space;number&space;of&space;games&space;of&space;that&space;player}\\\\&space;\\\\&space;E_i&space;:=&space;\\frac{45 \\cdot 2^{\\frac{R_i}{{$c1}}}}{\\sum_{j=1}^{10}2^{\\frac{R_j}{{$c1}}}}\\\\&space;S_i&space;:=&space;\\left\\{\\begin{matrix}&space;{$c3}&space;&&space;\\mbox{if&space;}&space;G_i>{$c4}\\\\&space;{$c2}&plus;\\frac{({$c3}-{$c2})(G_i-1)}{{$c4}-1}&space;&&space;\\mbox{else}&space;\\end{matrix}&space;\\right&space;\\\\&space;R'_i&space;:=&space;R_i&space;&plus;&space;\\left&space;\\lceil&space;S_i(10-P_i-E_i)-\\frac{1}{2}&space;\\right&space;\\rceil"  /></a>
E;
*/



print <<<E
<h2> Parameters</h2>
<form action="/exp6/rating2.php" method=get>
Start Rating:<input type="text" name="cr0" value=$startrating><br>
difference between players  <input type="text" name="cd" value=$probdiff><br>
Speed at Start: <input type="text" name="cs1" value=$startspeed>
Normal Speed: <input type="text" name="csm" value=$minspeed><br>
first games: <input type="text" name="cgm" value=$mingames><br>
specify two players who get extra data: <input type="text" name="pl1" value="$n1"><input type="text" name="pl2" value="$n2">
<input type="submit" value="Get Test data!">
</form>
<p>Explanation to the parameters:<br>
<b>Start Rating</b>: the rating points everyone starts with, it doesnt make huge difference, rather a matter of taste<br>
<b>difference between players</b>: if this value is small, the score of different people will be more close to each other. <br>
<b>speed</b>: this describes, how many points can you win or loose in a game.<br>
<b>speed at start/first games</b>:the "speed" will be higher in the first games, so players can get to their true rating quicker<br>
note: the combination of the different parameters plays a role. example:<br>
<b>speed/difference together</b>: if there is a high speed and small difference between players, the latest games are much more significant, but have in mind, that the rating should be something current and not sth of games long ago.
</p>
E;

$time1=time();


print <<<E
<h2> Rating history </h2>
<p>it displays name, the number of games that player played, gamename ("s1g320" means step 1 game #320), rating before the game and the difference (=won/lost points). on the left it has the average rating in that game</p>
<table border=1>
<tr><td>game</td><th>Place 1</th><th>Place 2</th><th>Place 3</th><th>Place 4</th>
<th>Place 5</th><th>Place 6</th><th>Place 7</th><th>Place 8</th>
<th>Place 9</th><th>Place 10</th><th></th></tr>
E;

$request = "SELECT playerid, name FROM table2";
$result = mysql_query($request);
$pnames=array();
$maxid = 0;
$spid2=998;
$spid1=998;
while($row=mysql_fetch_object($result))
{
	$pnames[$row->playerid] = $row->name;
	if ($row->name == $_GET['pl1']) $spid1=$row->playerid;
	if ($row->name == $_GET['pl2']) $spid2=$row->playerid;
	if($maxid < $row->playerid) $maxid = $row->playerid;
}

$history1=array($startrating);
$hissi1=1;
$history2=array($startrating);
$hissi2=1;
$history31=array($startrating);
$history32=array($startrating);
$hissi3=1;

$rating = array();
$games = array();
for($i1=1024;$i1<$maxid+5;$i1++) $rating[$i1]=$startrating;
for($i1=1024;$i1<$maxid+5;$i1++) $games[$i1]=0;
$request = "SELECT * FROM table1 ";
$result = mysql_query($request);
$expect = array();
$maxratingever=$startrating-1000;
$i7=0;
while($row=mysql_fetch_object($result) and $i7<420)
{
	$place=array(0,$row->p1,$row->p2,$row->p3,$row->p4,$row->p5,$row->p6,$row->p7,$row->p8,$row->p9,$row->p10);
	$NN = 10; //number of players in the game
	$gamename="s".$row->step . "g" . $row->gameno;
	print "<tr><td colspan=12></td></tr>\n";
	print "<tr><td>$gamename</td>";
	
	$ga = array();
	for($i1=1;$i1<=10;$i1++) 
	{
		$n= $pnames[$place[$i1]];
		if($n=="0" and $NN>=$i1)
		{
			$NN=$i1-1;
			$n="";
		}
		$games[$place[$i1]]++;
		$ga[$i1] = $games[$place[$i1]];
		$g = $ga[$i1];
		print "<td>$n ($g)</td>";
	}
	print "<td></td></tr>\n";
	$averagerating = 0.0;
	$specialplayer=0;
	for($i1=1;$i1<=$NN;$i1++)
	{
		$averagerating+=$rating[$place[$i1]];
		
		//print "<td>$r</td>";
	}
	$averagerating=ceil($averagerating/$NN);
	print "\n<tr><td>$averagerating</td>";
	
	//$expect=array();
	
	$adda=array();
	
	for($i1=1;$i1<=$NN;$i1++)
	{	
		$expect=0.0;
		for($i2=1;$i2<=$NN;$i2++)
		{
			$diff = (float)$rating[$place[$i1]]-$rating[$place[$i2]];
			$expect += 1.0/(1.0 + pow(2.0,$diff/$probdiff));
		}
		if($ga[$i1]<$mingames) $speed2 = (float)$startspeed + (float)($minspeed-$startspeed)*($ga[$i1]-1)/($mingames-1);
		if($ga[$i1]>=$mingames)$speed2=$minspeed;
		$adda[$i1] = (int) ceil($speed2*($NN-$i1 - $expect) -0.5);
		
	}
	
	for($i1=1;$i1<=$NN;$i1++)
	{
		$r = $rating[$place[$i1]];
		$add=$adda[$i1];
		print "<td>$r | $add </td>";
		if ($place[$i1]==$spid1) $specialplayer+=1;
		if ($place[$i1]==$spid2) $specialplayer+=2;
		$rating[$place[$i1]] += $add;
		if($maxratingever<$rating[$place[$i1]])$maxratingever=$rating[$place[$i1]];
	
	}
	
	
	
	/*$strength=array();
	
	for ($i1=1;$i1<=$NN;$i1++) $strength[$i1]=pow(2,(float)$rating[$place[$i1]]/$probdiff);
		
	$totalstrength = 0.0;
	for ($i1=1;$i1<=$NN;$i1++) $totalstrength += $strength[$i1];
	$specialplayer=0;
	for($i1=1;$i1<=10;$i1++)
	{
		$expect = ($NN*$NN-$NN)*$strength[$i1]/(2*$totalstrength);
		if($ga[$i1]<$mingames) $speed2 = (float)$startspeed + (float)($minspeed-$startspeed)*($ga[$i1]-1)/($mingames-1);
		if($ga[$i1]>=$mingames)$speed2=$minspeed;
		$add = (int) ceil($speed2*($NN-$i1 - $expect) -0.5);
		$r = $rating[$place[$i1]];
		print "<td>$r | $add </td>";
		if ($place[$i1]==$spid1) $specialplayer+=1;
		if ($place[$i1]==$spid2) $specialplayer+=2;
		$rating[$place[$i1]] += $add;
		if($maxratingever<$rating[$place[$i1]])$maxratingever=$rating[$place[$i1]];
	}*/
	if($specialplayer==1 or $specialplayer==3)
	{
		$history1[$hissi1]=$rating[$spid1];
		$history31[$hissi3]=$rating[$spid1];
		$history32[$hissi3]=$rating[$spid2];
		$hissi1++;
		$hissi3++;
	}
	if($specialplayer==2 or $specialplayer==3)
	{
		$history2[$hissi2]=$rating[$spid2];
		$history31[$hissi3]=$rating[$spid1];
		$history32[$hissi3]=$rating[$spid2];
		$hissi2++;
		$hissi3++;
	}
	print "<td></td></tr>";
	$i7++;
}

$min1=$startrating;
$min2=$startrating;
$min3=$startrating;
$max1=$startrating;
$max2=$startrating;
$max3=$startrating;
for($i1=0;$i1<$hissi1;$i1++)
{	if($min1>$history1[$i1]) $min1=$history1[$i1];
	if($max1<$history1[$i1]) $max1=$history1[$i1];}
for($i1=0;$i1<$hissi2;$i1++)
{	if($min2>$history2[$i1]) $min2=$history2[$i1];
	if($max2<$history2[$i1]) $max2=$history2[$i1];}
for($i1=0;$i1<$hissi3;$i1++)
{	if($min3>$history31[$i1]) $min3=$history31[$i1];
	if($max3<$history31[$i1]) $max3=$history31[$i1];
	if($min3>$history32[$i1]) $min3=$history32[$i1];
	if($max3<$history32[$i1]) $max3=$history32[$i1];}



$img1=imagecreate($hissi1*3+40,220);
$img2=imagecreate($hissi2*3+40,220);
$img3=imagecreate($hissi3*2+40,220);
$background = imagecolorallocate( $img1,255, 255, 255);
$background = imagecolorallocate( $img2,255, 255, 255);
$background = imagecolorallocate( $img3,255, 255, 255);
//imagecolortransparent($img1,$background);
$bluecolor=imagecolorallocate($img1,0,0,255);
$bluecolor3=imagecolorallocate($img3,0,0,255);
$redcolor=imagecolorallocate($img2,255,0,0);
$redcolor3=imagecolorallocate($img3,255,0,0);
$yold=floor(210.0+200.0*($min1-$history1[0])/((float)$max1-$min1+1));
$yarray=array();
for($i1=1;$i1<$hissi1;$i1++)
{
	$ynew=floor(210.0+200.0*($min1-$history1[$i1])/((float)$max1-$min1+1));
	imageline($img1,$i1*3+4,$yold,$i1*3+7,$ynew,$bluecolor);
	$yold=$ynew;
}
imagestring($img1,1,$hissi1*3+10,4,$max1,$bluecolor);
imagestring($img1,1,$hissi1*3+10,206,$min1,$bluecolor);
imagepng($img1,"exp6/temppic1.png");
$yold=floor(210.0+200.0*($min2-$history2[0])/((float)$max2-$min2+1));
for($i1=1;$i1<$hissi2;$i1++)
{
	$ynew=floor(210.0+200.0*($min2-$history2[$i1])/((float)$max2-$min2+1));
	imageline($img2,$i1*3+4,$yold,$i1*3+7,$ynew,$redcolor);
	$yold=$ynew;
}
imagestring($img2,1,$hissi2*3+10,4,$max2,$redcolor);
imagestring($img2,1,$hissi2*3+10,206,$min2,$redcolor);
imagepng($img2,"exp6/temppic2.png");
$yold1=floor(210.0+200.0*($min2-$history31[0])/((float)$max3-$min3+1));
$yold2=floor(210.0+200.0*($min2-$history32[0])/((float)$max3-$min3+1));
for($i1=1;$i1<$hissi3;$i1++)
{
	$ynew1=floor(210.0+200.0*($min3-$history31[$i1])/((float)$max3-$min3+1));
	$ynew2=floor(210.0+200.0*($min3-$history32[$i1])/((float)$max3-$min3+1));
	imageline($img3,$i1*2+5,$yold2,$i1*2+7,$ynew2,$redcolor3);
	imageline($img3,$i1*2+5,$yold1,$i1*2+7,$ynew1,$bluecolor3);
	$yold1=$ynew1;
	$yold2=$ynew2;
}
imagestring($img3,1,$hissi3*2+10,4,$max3,$redcolor3);
imagestring($img3,1,$hissi3*2+10,206,$min3,$bluecolor3);
imagepng($img3,"exp6/temppic3.png");


imagecolordeallocate($img3,$redcolor3);
imagecolordeallocate($img2,$redcolor);
imagecolordeallocate($img1,$bluecolor);
imagecolordeallocate($img1,$background);
imagecolordeallocate($img2,$bluecolor3);




print "</table>\n";
/*print "<pre>$hissi3,$spid1,$min3,$max3 \n";
var_dump($history31);
print "</pre>";*/
print "<p>highest rating ever achieved: <b>$maxratingever</b></p>";
$timediff= time()-$time1;
//print "<p><b>DIFFERENCE: $timediff SECONDS</b></p>";
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
if($spid1!=998 and $spid2!=998)
{	print "<h3>rating over time of the two selected players</h3>";
	$n1=$_GET['pl1'];
	$n2=$_GET['pl2'];
	print "<p>you selected the players $n1 and $n2</p><br>\n";
	print <<<E
	<img src="/exp6/temppic1.png" alt="t"><br>
	<img src="/exp6/temppic2.png" alt="t"><br>
	<img src="/exp6/temppic3.png" alt="t"><br>
	
E;



}

?>
<?php
include "footer1.php";
?>

</body>
</html>
