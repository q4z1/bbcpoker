<?php
print '<!DOCTYPE html>
<html>';
ini_set('include_path', '/home/www/bbc/');
include "head.php";
$regulartaskcount=1;
include "exp2/regulartasks.php";
print "<body>";
include "header1.php"; 
include "exp5/nav1.php";
?>

<h1>Rating calculator</h1>

<p>This should be an example calculator for a possible bbc rating. The aim of the rating is to estimate the current strength of a player as good as possible.<br>
This page uses the glicko rating (not glicko 2), you can check it here: <a href="http://glicko.net/glicko/glicko.pdf">(link)</a>. At the bottom of this page i give you the main part of the php source code i am using for the rating.
</p>
<h3>small explanatino</h3>
<p>Each player has two numbers: The Rating and the &quot;RD &quot;. RD means Rating deviation. If the RD is high, it means that we are unsure about the rating of a player, and that the rating is not very accurate (this can happen if a player is new or has not played for a long time).

<h2>Example calculation</h2>
<h3>Input</h3>
<form action="exp4/formula3.php" method="get">
<b>System parameters:</b><br>
<?php
$q=(float)$_GET['q'];
$c=(float)$_GET['c'];
$maxrd=(int)$_GET['maxrd'];
$minrd=(int)$_GET['minrd'];
$cplayer=(int)$_GET['p'];
if($cplayer<2 or $cplayer>10) $cplayer=10;
$ratings=array();
$rds=array();
$lgs=array();
$lgs2=array();
$maxlg=0;
for($i1=1;$i1<=$cplayer;$i1++)
{
	$ratings[$i1-1]=round((float)$_GET["r$i1"],2);
	if($ratings[$i1-1]==0.0) $ratings[$i1-1]=2500.0;
	
	$rds[$i1-1]=round((float)$_GET["rd$i1"],2);
	if($rds[$i1-1]==0.0) $rds[$i1-1]=350.0;
	$lgs[$i1-1]=(int)$_GET["lg$i1"];
	if($lgs[$i1-1]>$maxlg) $maxlg=$lgs[$i1-1];
}
for($i1=1;$i1<=$cplayer;$i1++)
{
	$lgs2[$i1-1]=$maxlg-$lgs[$i1-1];
}
$r=$_GET['r'];
$rd=$_GET['rd'];
if($q<=0.0) $q=log(10.0)/400.0;
if($c<=0.0) $c=16.0;
$q=round($q,8);
$c=round($c,2);
if($maxrd<=0) $maxrd=350;
if($minrd<=0) $minrd=30;


//$intext1='<input type="text" size=5 name="'
print <<<E
q:<input type="text" value="$q" name="q" size=8>
c:<input type="text" value="$c" name="c" size=5>
maxRD:<input type="text" value="$maxrd" name="maxrd" maxlength=6 size=4>
minRD:<input type="text" value="$minrd" name="minrd" maxlength=6 size=4>
<table>
E;


for($i1=1;$i1<11;$i1++)
{
	$i2=$i1-1;
	print "<tr><td>";
	if($i1==1) print "Winner";
	else print "Place $i1";
	print "</td><td><input type=\"text\" name=\"r$i1\" size=6 value=\"$ratings[$i2]\">";
	print "</td><td><input type=\"text\" name=\"rd$i1\" size=6 value=\"$rds[$i2]\">";
	print "</td><td><input type=\"text\" name=\"lg$i1\" size=6 value=\"$lgs[$i2]\"></td></tr>\n";
}
print "</table>";
print "Number of Players (2-10): <input type=\"text\" name=\"p\" value=\"$cplayer\" size=3>";

?>
<br><input type="submit" value="Calculate!">
</form>
<h3>Results</h3>
<?php
include("exp6/func3.php");

$ret=glickomain($ratings,$rds,$lgs2,$maxlg,$maxrd,$c,$q,$minrd);
//var_dump($ratings);
//var_dump($rds);
//var_dump($lgs2);
//var_dump($ret);

$newr=$ret[0];
$newrd=$ret[1];
print "<table border><tr><th>Place</th><th>Old Rating</th><th>New Rating</th><th>Old RD</th><th>New RD</th><td><small>Days since Last game</small></td></tr>\n";
for($i1=1;$i1<=count($newr);$i1++)
{
	$i2=$i1-1;
	print "<tr><td>$i1</td><td>$ratings[$i2]</td><td>$newr[$i2]</td><td>$rds[$i2]</td><td>$newrd[$i2]</td><td>$lgs[$i2]</td></tr>\n";
}
print "</table>";

?>


<h2>Source code </h2>
<table><tr><td style="text-align:left">
<pre>
function glickog($rd,$q)
{
	$pi=3.1415926535;
	return 1.0/sqrt(1.0+3.0*$q*$q*$rd*$rd/$pi*$pi);
}


function glickoe($r1,$r2,$rd2,$q)
{
	return 1.0/(1.0+exp(-glickog($rd2,$q)*($r1-$r2)*$q));
}

function glickomain($ratings,$rds,$rlgs,$thisday,$maxrd=350,$glickoc=18,$q=0.0,$minrd=30) // player with index 0 is winner, 9 is place 10
{
	//ratings, rds, rlgs are arrays of the same size, rlgs means the day of the last game
	if(count($ratings)!=count($rds) or count($rds) !=count($rlgs) or count($ratings)>=11) return 0; //error
	if($q==0.0) $q=log(10)/400.0;
	$c1=count($ratings);
	//default values for constants://$maxrd=350;//$minrd=30;//$glickoc=18;//$q=log(10)/400.0;
	$rd2=array();
	$hg=array();
	$newrating=array();
	$newrd=array();
	for($i1=0;$i1<$c1;$i1++)
	{
		$rd2[$i1]=sqrt($rds[$i1]*$rds[$i1]+$glickoc*$glickoc*($thisday-$rlgs[$i1]));
		if($rd2[$i1]>$maxrd) $rd2[$i1]=$maxrd;
		if($rd2[$i1]<$minrd) $rd2[$i1]=$minrd;
	}
	for($i1=0;$i1<$c1;$i1++)
	{
		$hg[$i1]=glickog($rd,$q);
	}
	for($i1=0;$i1<$c1;$i1++)
	{
		$t1=0.0;
		$t2=0.0;
		for($i2=0;$i2<$c1;$i2++)
		{
			if($i2==$i1) continue;
			$s=0.0; //lost
			if($i1<$i2) $s=1.0; //win
			$e=glickoe($ratings[$i1],$ratings[$i2],$rd2[$i2],$q);
			$t1+=$hg[$i2]*($s-$e);
			$t2+=$hg[$i2]*$hg[$i2]*$e*(1-$e);
		}
		$d2inv=$q*$q*$t2;
		
		$newrd[$i1]=round(pow(1.0/($rd2[$i1]*$rd2[$i1])+$d2inv,-0.5),2);
		if($newrd[$i1]<$minrd) $newrd[$i1]=$minrd;
		$t3=1.0/($rd2[$i1]*$rd2[$i1])+$d2inv;
		$newrating[$i1]=round($ratings[$i1]+$q*$t1/$t3,2);
		
	}
	return array($newrating,$newrd);
}
</pre>
</td></tr></table>

<?php
include "footer1.php";
?>

</body>
</html>