<?php
//set cookie
if($_POST['submit']!="")
{
	$user = $_POST['pname'];
	setcookie("user1","$user",0,'/');
}
?>
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

<h1> Remove your registration </h1>

<?php

include "exp5/func1.php";
$error =0;
$errorinfo1="none";
if($_POST['submit']=="") $error=330;

$step=0;
if($error==0)
{
	$code=$_POST['code'];
	$pname1=$_POST['pname'];
	$pname2=mysql_real_escape_string( $pname1);
	$pt1=$_POST['pt'];
	//$plan3 = $_POST['plan3'];
	//$retar = calcnextdate(3);
	//if($retar[0]!=$plan3) $error=332;
	//if($retar[0]!=$plan3) $errorinfo1="step3";
	$request = "SELECT playerid, name FROM table2 WHERE name='$pname2'";
	$result = mysql_query($request);
	$c=0;
	while($row = mysql_fetch_object($result))
	{
		$c++;
		$pid = $row->playerid;
		//print "<p>$pname</p>";
		if($pid < 1024) $error=333;
		if($pid < 1024) $errorinfo1=$pid;
		if($pid == 1024) $error=333;
		//if($pid == 1028) $error=300;//Rezos
		if($pid == 1050) $error=333;//Gabesz
		
	}
	if($c==0) $error=334;
	$step=0;
	//if($pt1=="s3d") $step=3;

}


$dt3="";
for($i1=0;$i1<4 and $error==0 and $step!=4;$i1++)
{
	$rar1=getrdates($i1);
	$c1=count($rar1);
	for($i2=0;$i2<$c1 and $error==0;$i2++)
	{
		$dt2 = date("D, d M Y H:i T",$rar1[$i2]);
		$t1="s$i1" . "t" . $rar1[$i2];
		if($_POST["pt"]==$t1)
		{			
			$dt3=date("Y-m-d H:i:s",$rar1[$i2]);
			if($i1>1 and $rar1[$i2]<time()+90*60) {$error=337;continue;}
			$code=registrationcode($i1,$pid,$dt3);
			if($code != $_POST['code']) $error=335;
			if($code==-1) $error=336;
			if($error!=0) break;
			$step=$i1;
		}
	}
}

if($error==0)
{
	//$request = "SELECT name, settings, step, plantime FROM registr WHERE id=$pid";
	$sqlcond = "step=$step AND plantime='$dt3' AND tpos<61";
	$request="SELECT * FROM registr WHERE $sqlcond AND name='$pname2'";
	
	$result = mysql_query($request);
	$c=0;
	while($row = mysql_fetch_object($result))
	{
		//$remname=$row->name;
		$settings=$row->settings;
		$id1=$row->id;
		$c++;
	}
	if($c==0)$error=333;
	$settings += 16;
	$request = "UPDATE registr Set settings=$settings, tpos=333 WHERE id=$id1";
	$result = mysql_query($request);
	if($result===false) $error=333;
// slide up
	for($i1=0;$i1<200;$i1++)
	{
		$freepos=-1;
		$request="SELECT COUNT(tpos),MAX(tpos) FROM registr WHERE $sqlcond";
		$result = mysql_query($request);
		$row = mysql_fetch_array($result);
		if($row[0] == $row[1]) break;
//		print "<p>$row[0] :: $tpos2 ::";
		$request="SELECT COUNT(*) FROM registr WHERE $sqlcond AND tpos=1";
		$result = mysql_query($request);
		$row = mysql_fetch_array($result);
		if($row[0]==0)$freepos=1;
		else
		{
			$request = "SELECT (tpos+1) AS freepos FROM registr
WHERE (tpos+1) NOT IN (SELECT tpos FROM registr WHERE $sqlcond)
ORDER BY freepos
limit 1 ";
			$result = mysql_query($request);
			$row = mysql_fetch_array($result);
			$freepos = $row[0];
		}
		if($freepos<0 or $freepos>=$tpos2) $err3 = 1;
		$request = "UPDATE registr SET tpos=tpos-1 WHERE $sqlcond AND tpos>$freepos";
		$result = mysql_query($request);
		if($result===false) $error=333;
	}
}

if($error==0)
print "<p>It seems like you succeeded with your request! :) </p>";
else
{
	include "exp2/error.php";
}


?>


<?php
include "footer1.php";
?>

</body>
</html>
