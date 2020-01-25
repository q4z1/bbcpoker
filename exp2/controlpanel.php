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

<h1> Control Panel </h1>
<p> This is only for admins. if the following options are used wrong, you can damage the database. it is password protected.</p>
<p> Information: it doesnt check tickets. Also, if you delete the last game, 
you have to correct the tickets individually <a href="/exp4/input22.php">here</a></p>
<?php
//die("this script is currently disabled");

if($userpass=="nelly" or $userpass=="supernoob" or $userpass=="sp0ck")
//session_start();
//if($_SESSION && array_key_exists("upc", $_SESSION) && $_SESSION["upc"] != 1)
{

$error=0;

function recheck($option1 = 1)
{
	//$option1 == 1 means check only 
	//$option1 == 2 means recalc
	function calcpc($step,$season,$pid)
	{
		$pcarray=array(0,0,0,0,0,0,0,0,0,0); 	
		$seasontext="";
		if($season >0) $seasontext=" AND season='$season'";
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
	$request = "SELECT IFNULL(MAX(gameno),0) FROM table1 WHERE step = '4'";
	$result=mysql_query($request);
	$row=mysql_fetch_array($result);
	$season = 19 + (int)$row[0]; // first season with step4 number is season 19
	$request="SELECT * FROM table2 ORDER BY playerid ASC";
	$result = mysql_query($request);
	function ptsbypc($pc)//without 
	{
		$retval=0;
		$pcarray=explode(",",$pc);
		for($i=0;$i<10;$i++) $retval += (10-$i)*((int)$pcarray[$i]);
		return $retval;
	}
	function countgames($pc1,$pc2,$pc3,$pc4) 
	{
			$retval=0;
			$pcarray=explode(",",$pc1);
			for($i=0;$i<10;$i++) $retval += (int)$pcarray[$i];
			$pcarray=explode(",",$pc2);
			for($i=0;$i<10;$i++) $retval += (int)$pcarray[$i];
			$pcarray=explode(",",$pc3);
			for($i=0;$i<10;$i++) $retval += (int)$pcarray[$i];
			$pcarray=explode(",",$pc4);
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
	while($row=mysql_fetch_object($result)	and $error==0)
	{
		$i1++;
		$pid = $row->playerid;
		if($pid <1024) $error=201;
		//if($pid ==1024) $i1=1024;
		//if($pid != $i1) $error=62;
		$pname = $row->name;
		$a1pc = $row->a1placecount;
		$a2pc = $row->a2placecount;
		$a3pc = $row->a3placecount;
		$a4pc = $row->a4placecount;
		$apoints = $row->alltimepoints;
		$agames = $row->alltimegames;
		$ascore = $row->alltimescore;
		$s1pc = $row->s1placecount;
		$s2pc = $row->s2placecount;
		$s3pc = $row->s3placecount;
		$s4pc = $row->s4placecount;
		$spoints = $row->saisonpoints;
		$sgames = $row->saisongames;
		$sscore = $row->saisonscore;
		if($pname != trim($pname)) {$error=215; $errorinfo1=$pname;}
		$recalced = calcpc(1,0,$pid);
		if($a1pc != $recalced) {$error=202; $errorinfo1 = "$pid , $recalced , " . $a1pc;}
		$recalced = calcpc(2,0,$pid);
		if($a2pc != $recalced) {$error=203; $errorinfo1 = "$pid , $recalced , " . $a2pc;}
		$recalced = calcpc(3,0,$pid);
		if($a3pc != $recalced) {$error=204; $errorinfo1 = "$pid , $recalced , " . $a3pc;}
		$recalced = calcpc(4,0,$pid);
		if($a4pc != $recalced) {$error=204; $errorinfo1 = "$pid , $recalced , " . $a4pc;}
		$recalced = calcpc(1,$season,$pid);
		if($s1pc != $recalced) {$error=205; $errorinfo1 = "$pid , $recalced , " . $s1pc;}
		$recalced = calcpc(2,$season,$pid);
		if($s2pc != $recalced) {$error=206; $errorinfo1 = "$pid , $recalced , " . $s2pc;}
		$recalced = calcpc(3,$season,$pid);
		if($s3pc != $recalced) {$error=207; $errorinfo1 = "$pid , $recalced , " . $s3pc;}	
		$recalced = calcpc(4,$season,$pid);
		if($s4pc != $recalced) {$error=207; $errorinfo1 = "$pid , $recalced , " . $s4pc;}	
		
		if($error==0 and ptsbypc($a1pc)+2*ptsbypc($a2pc)+3*ptsbypc($a3pc)+4*ptsbypc($a4pc) != $apoints) {$error=208; $errorinfo1 = "$pid , $apoints";}
		if($error==0 and ptsbypc($s1pc)+2*ptsbypc($s2pc)+3*ptsbypc($s3pc)+4*ptsbypc($s4pc) != $spoints) {$error=209; $errorinfo1 = "$pid , $spoints";}
		if($error==0 and $agames != countgames($a1pc,$a2pc,$a3pc,$a4pc)) {$error=210;$errorinfo1 = "$pid , $agames";}
		if($error==0 and $sgames != countgames($s1pc,$s2pc,$s3pc,$s4pc)) {$error=211;$errorinfo1 = "$pid , $sgames";}
		if($error==0 and $ascore != calcscore($apoints,$agames)) {$error=212;$errorinfo1 = "$pid , $ascore";}
		if($error==0 and $sscore != calcscore($spoints,$sgames)) {$error=213;$errorinfo1 = "$pid , $sscore";}
		if($error==0) continue;
		if($error!=0 and $option1!=2) return array($error,$errorinfo1);
		if($error!=0)
		{	
			$error2=$error;
			$error=0;
		}
		$a1pc = calcpc(1,0,$pid);
		$a2pc = calcpc(2,0,$pid);
		$a3pc = calcpc(3,0,$pid);
		$a4pc = calcpc(4,0,$pid);
		$s1pc=calcpc(1,$season,$pid);
		$s2pc=calcpc(2,$season,$pid);
		$s3pc=calcpc(3,$season,$pid);
		$s4pc=calcpc(4,$season,$pid);
		$spoints = ptsbypc($s1pc) + 2*ptsbypc($s2pc) + 3*ptsbypc($s3pc) + 4*ptsbypc($s4pc);
		$apoints = ptsbypc($a1pc) + 2*ptsbypc($a2pc) + 3*ptsbypc($a3pc) + 4*ptsbypc($a4pc);
		$agames = countgames($a1pc,$a2pc,$a3pc,$a4pc);
		
		$sgames = countgames($s1pc,$s2pc,$s3pc,$s4pc);
		$ascore = calcscore($apoints,$agames);
		$sscore = calcscore($spoints,$sgames);
		$request2 = "UPDATE table2 Set
s1placecount = '$s1pc',
s2placecount = '$s2pc',
s3placecount = '$s3pc',
s4placecount = '$s4pc',
a1placecount = '$a1pc',
a2placecount = '$a2pc',
a3placecount = '$a3pc',
a4placecount = '$a4pc',
saisonpoints = '$spoints',
alltimepoints = $apoints,
alltimegames = $agames,
saisongames = $sgames,
alltimescore = $ascore,
saisonscore = $sscore 
WHERE playerid='$pid'";
		$result2=mysql_query($request2) or die("ERRORRRRR");

	}
	
	if($error!=0) return array(214,"programming");
	if($error2 !=0 ) return array($error2,$errorinfo1);
	return -1;
}

if($_POST["recalc"]!="") 
{
	$retval = recheck(2);
	if($retval == -1) print "<p>No error found - therefore no recalculation</p>";
	else 
	{	
		$error=$retval[0];
		$errorinfo1=$retval[1];
		include "exp2/error.php";
		print "<p>But dont worry, the database is recalculated, the error should be gone</p>";
	}
}
if($_POST["check"]!="")
{
	$retval = recheck(1);
	if($retval == -1) print "<p>No error was found! - congratulations!</p>";
	else
	{
		$error=$retval[0];
		$errorinfo1=$retval[1];
		include "exp2/error.php";
	}	
}

if($_POST["dellast"]!="") 
{
	$error=0;
	$step = $_POST['dellaststep'];
	if($step != 1 and $step != 2 and $step != 3 and $step!=4)
	{
		$error=220;
		$errorinfo1 = $step;
		include "exp2/error.php";
	}
	else
	{
		$request = "SELECT MAX(gameno) FROM table1 WHERE step='$step'";
		$result=mysql_query($request);
		$row=mysql_fetch_array($result);
		$gameno = $row[0];
		$request = "DELETE FROM table1 WHERE step='$step' AND gameno='$gameno' ";
		$result=mysql_query($request) or die("strange error..... sry");
		recheck(2);
		print "<p>The Game BBC $gameno Step $step was deleted. The database was recalculated</p>";
	}
}

//if($_POST["recalc"]!="") {print "This is in Progress - deactivated";$_POST["recalc"]="";}
	


}
elseif ($userpass=="0")
{
print "

<form action=\"exp2/controlpanel.php\" method=\"post\">
Password: <input type=\"password\" name=\"pass\"><br><br>
<input type = \"Submit\" name=\"check\" value=\"Check for errors\"><br><br>
<input type = \"Submit\" name=\"recalc\" value=\"recalculate ranking\"><br><br>
<input type = \"Submit\" name=\"dellast\" value=\"Delete Last Game\">
of Step <input type=\"text\" name=\"dellaststep\" value=\"1\" maxlength=1 size=2><br><br>


</form>

";


}
else 
{
print "<p><b>Your Password is not ok</b></p>";

}


?>
<?php
include "footer1.php";
?>

</body>
</html>
