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

<h1>Rating calculator</h1>

<p>This should be an example calculator for a possible bbc rating. The aim of the rating is to estimate the current strength of a player as good as possible.<br>
This page uses the rating 5.
</p>
<h3>small explanation</h3>
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
The number of games each player has played so far can also have an influence on the rating points, therefor there is an extra column.<br>
If you want to know, how things look like if you consider many games, here is an example computation: <a href="http://bbcpoker.bplaced.net/exp6/rating5.php">(click here)</a>.
it uses all BBC games in 2013. you can even get a graph of players of their rating over time.
</p>

<h2>Example calculation</h2>
<h3>Input</h3>
<form action="exp4/formula5.php" method="get">
<b>System parameters:</b><br>
<?php
//$startrating=(int)$_GET['startrating'];
$probdiff=(int)$_GET['dif'];
$startspeed=(int)$_GET['startspeed'];
$minspeed=(int)$_GET['minspeed'];
$mingames=(int)$_GET['mingames'];
$winbonus=(float)$_GET['winbonus'];
$winbonus=round($winbonus,3);
$cplayer=(int)$_GET['p'];
if($cplayer<2 or $cplayer>10) $cplayer=10;
$ratings=array();
$gamec=array();
for($i1=1;$i1<=$cplayer;$i1++)
{
	$ratings[$i1-1]=(int)$_GET["r$i1"];
	if($ratings[$i1-1]==0) $ratings[$i1-1]=5000;
	$gamec[$i1-1]=(int)$_GET["g$i1"];
	if($gamec[$i1-1]<=0) $gamec[$i1-1]=0;
}

// deal with default values:
//if($startrating==0) $startrating=5000;
if($probdiff==0) $probdiff=3000;
if($startspeed==0) $startspeed=80;
if($minspeed==0) $minspeed=35;
if($mingames==0) $mingames=30;
if($winbonus==0.0) $winbonus=1.17;


//$intext1='<input type="text" size=5 name="'
print <<<E
Difference between Players:<input type="text" value="$probdiff" name="dif" size=8><br>
Speed at Start: <input type="text" name="startspeed" value=$startspeed>
Normal Speed: <input type="text" name="minspeed" value=$minspeed><br>
first games: <input type="text" name="mingames" value=$mingames><br>
Bonus for Winner: <input type="text" name="winbonus" value=$winbonus>
<table><tr><td>#</td><th>Rating</th><th>Games played</th></tr>
E;

for($i1=1;$i1<11;$i1++)
{
	$i2=$i1-1;
	print "<tr><td>";
	if($i1==1) print "Winner";
	else print "Place $i1";
	print "</td><td><input type=\"text\" name=\"r$i1\" size=6 value=\"$ratings[$i2]\">";
	print "</td><td><input type=\"text\" name=\"g$i1\" size=6 value=\"$gamec[$i2]\"></td></tr>\n";
}
print "</table>";
print "Number of Players (2-10): <input type=\"text\" name=\"p\" value=\"$cplayer\" size=3>";

?>
<br><input type="submit" value="Calculate!">
</form>
<h3>Results</h3>
<?php
include("exp6/func3.php");

$newr=noobratingmain($ratings,$gamec,$probdiff,$startspeed,$minspeed,$mingames,$winbonus);

print "<table border><tr><th>Place</th><th>Old Rating</th><th>New Rating</th><th>Difference</th><td><small>played games</small></td></tr>\n";
for($i1=1;$i1<=count($newr);$i1++)
{
	$i2=$i1-1;
	$rdif=$newr[$i2]-$ratings[$i2];
	$gcn=$gamec[$i2]+1;
	print "<tr><td>$i1</td><td>$ratings[$i2]</td><td>$newr[$i2]</td><td>$rdif</td><td>$gcn</td></tr>\n";
}
print "</table>";

?>

<h2>Source code</h2>
<pre style="text-align:left">
function noobratingmain($ratings,$gamec,$probdiff=3000,$startspeed=80,$minspeed=40,$mingames=30,$winbonus=1.17) 
{
    /* 
    this is the core function of the bbc rating. 
    it was programmed by some supernoob :)
    
    $ratings and $gamec are arrays of the same size
    the size is the number of players
    player with index 0 is winner, 9 is place 10
    gamec is the number of games played before this game
    */
    if(count($ratings) !=count($gamec) or count($ratings)>=11) return 0; //error
    $NN=count($ratings); // $NN is the number of players in the game
    $newrating=$ratings; // newrating will be return array
    for($i1=0;$i1<$NN;$i1++)
    {    
        $expect=0.0; // $expect is the expected score for each player
        for($i2=0;$i2<$NN;$i2++)
        {
            if($i1==$i2) continue;
            $diff = (float)($ratings[$i2]-$ratings[$i1]); //compare rating difference with each player
            if($i2==0 or $i1==0) $expect += $winbonus/(1.0 + pow(2.0,$diff/$probdiff)); // add expected score for winners
            if($i2!=0 and $i1!=0) $expect += 1.0/(1.0 + pow(2.0,$diff/$probdiff)); // add expected score for non-winners
        }
        if($gamec[$i1]<$mingames) $speed2 = (float)$startspeed + (float)($minspeed-$startspeed)*($gamec[$i1])/($mingames); 
        if($gamec[$i1]>=$mingames)$speed2=$minspeed; // determine the "speed" according to parameters
        $wonpoints=$NN-$i1-1; //
        if($i1==0) $wonpoints=$winbonus*9.0; // winners get more points
        $newrating[$i1] =$ratings[$i1] + (int) ceil($speed2*($wonpoints - $expect) -0.5); //round the rating points to integer
    }
    return $newrating;
}
</pre>

<?php
include "footer1.php";
?>

</body>
</html>