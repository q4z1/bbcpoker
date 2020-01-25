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

<h1> Remove your registration </h1>
<form action="/exp5/reg6.php" method="post">
<table>
<tr><td>Enter your poker-heroes Nickname: </td><td>
<?php
include "exp5/func1.php";
$name = $_COOKIE['user1'];
print '<input type="text" name="pname" value="' . $name . '">';
?>
</td></tr>
<tr><td>Enter your deregistration code:</td>
<td><input type="text" name="code"></td></tr>
<tr><td><br></td><td></td></tr>
<tr><td colspan=2>Select the game:</td></tr>

<?php
for($i1=1;$i1<4;$i1++)
{
	$now=time();
	//print "<tr><td>";
	$rarr=getrdates($i1);
	$c1=count($rarr);
	for($i2=0;$i2<$c1;$i2++)
	{
		$datetext2 = date("D, d M Y H:i T",$rarr[$i2]);
		$t1="s$i1" . "t" . $rarr[$i2];
		if($i1!=1 and $rarr[$i2]<$now+90*60) continue;
		print <<<E
		<tr><td>
		<input type="radio" value="$t1" name="pt">Step $i1</td>
		<td><b>$datetext2</b></td></tr>
E;
		
	}
	
	
}
/*
$i1=3;
print "<tr><td>
<input type=\"radio\" value=\"s$i1". "d\" name=\"pt\">Step $i1</td>";
$retarray=calcnextdate($i1);
print "<td><b>$retarray[1]</b>";
print "<input type=\"hidden\" name=\"plan$i1\" value=\"$retarray[0]\"></td></tr>";
*/

?>


</table>
<input type="submit" name="submit" value="remove registration!">
</form>
<p>Deregistration is possible until 90 minutes before the game in step 2, 3 and 4 - no need to de-reg for step1</p>
<p><a href="/exp4/shoutbox1.php">If you forgot your deregistration code, no problem, you can still ask us here latest 30 minutes before game starts</a></p>

<?php
include "footer1.php";
?>

</body>
</html>
