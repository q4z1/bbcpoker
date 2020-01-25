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
<?php
include "exp5/func1.php";

print "<h1>Registrations - Admin Page</h1>";
$dtarr = calcnextdate(1);
$adminmode = 0;//bit 0:right pw, bit 1:confirmode, bit 2:after input
$request = "SELECT name, id, ip, settings FROM registr WHERE tpos=99 AND plantime='$dtarr[0]' AND step=1";
$result = mysql_query($request);
$c=0;
$conarray = array();
$cida = array();
$conipa = array();
$consa = array();
while($row = mysql_fetch_object($result))
{
	$conarray[$c]=$row->name;
	$cida[$c]=$row->id;
	$conipa[$c]=$row->ip;
	$consa[$c]=$row->settings;
	$c++;
}
if($c>0) $adminmode=2;
if($c>0) $needconfirm=1;
else $needconfirm=0;

session_start();
if($_SESSION['upc'] == 1)
//if($userpass == "nelly" or $userpass=="supernoob" or $userpass=="sp0ck") 
{	
	if($adminmode==2) $adminmode=3;
	if($_POST['mm7'] != "") $adminmode =7;
	if($_POST['mm1'] != "") $adminmode =1;
	if($_POST['mm3'] != "") $adminmode =3;
	if($_POST['mm5'] != "") $adminmode =5;
	
}
else if($userpass!="0") print "<h2>Wrong Password</h2>";


if($adminmode==0 or $adminmode==1)
{
	print '<p>Choose a Game and check registrations:</p>
	<form action="/exp5/reg8.php" method="post">';

	$step=(int)$_POST['step'];
	if($step != 2 and $step != 3 and $step != 4) $step = 1; //add $step != 4
	print "Step ";
	for($i=1;$i<5;$i++)//change $i<4
	{
		$ctext="";
		if($step==$i) $ctext=" checked=\"true\"";
		print "<input type=\"radio\" name=\"step\" value=\"$i\"$ctext>$i";
	}		
	print ' &nbsp;&nbsp;or <a href="/exp5/reg8.php">refresh this page</a>';
	print '<p><input type="Submit" name="mm1" value="See Games"></p>
</form>';
	
}
if($adminmode==1 and $needconfirm==1) 
{	print '<form action="/exp5/reg8.php" method="post">';
	print '<p>Password: <input type="password" name="pass" value="';
	print $_POST['pass']; print '">
<input type="Submit" name="mm3" value="Check confirmations"></p>
</form>';
}
if($adminmode==2)
{
	print '<h2>Dear Admin, please check the player confirmations</h2>
<form action="/exp5/reg8.php" method="post">';
	print '<p>Password: <input type="password" name="pass" value="';
	print $_POST['pass']; print '">
<input type="Submit" name="mm3" value="Log In"></p>
</form>';

}

if($adminmode==7)
{
	$i2=0;//number of actions
	$aca = array();
	for($i1=0;$i1<count($conarray);$i1++)
	{
		$aca[$i1] = (int)$_POST["c$cida[$i1]"];
		if($aca[$i1]==0) continue;
		$i2++;
		
		if($aca[$i1]==1)//confirm
		{
			//ask for tpos
			$tpos2 = 0;
			for($i3=1;$i3<7;$i3++)//max 6 tables :D
			{
				$i4 = 10*$i3 +1;
				$request = "SELECT MAX(tpos) FROM registr WHERE step=1 AND plantime='$dtarr[0]' AND tpos<$i4 ";
				$result = mysql_query($request);
				$row = mysql_fetch_array($result);
				if(count($row)==0) {$tpos2 = $i4-10;break;}
				if($row[0] < $i4-1) {$tpos2 = $row[0]+1;break;}
			}
			if($tpos2 <=0 )$tpos2=1;
			$consa[$i1] -= 4;
			$request = "UPDATE registr Set tpos=$tpos2,settings=$consa[$i1] WHERE id = $cida[$i1]";
			$result = mysql_query($request);
			if($result===false)$aca[$i1]=3;
			continue;
		}//reject ??
		$consa[$i1] += 4;
		$request = "UPDATE registr Set tpos=222,settings=$consa[$i1] WHERE id = $cida[$i1]";
		$result = mysql_query($request);
		if($result===false)$aca[$i1]=3;
	}
	if($i1==1) print "<p>You (successfully) made the following decision: </p>";
	else print "<p>You successfully made the following decisions for $i2 players: </p>";
	print "<table><tr><th>Name</th><th>Action</th></tr>";
	for($i1=0;$i1<count($conarray);$i1++)
	{
		print "<tr><td>$conarray[$i1]</td>";
		if($aca[$i1]==1) print "<td>Confirmed</td></tr>";
		if($aca[$i1]==2) print "<td>Rejected</td></tr>";
		if($aca[$i1]==3) print "<td>Failed - error :(</td></tr>";
	}
	print "</table>";
	print '<form action="/exp5/reg8.php" method="post">';
	print '<p>Password: <input type="password" name="pass" value="';
	print $_POST['pass']; print '">
<input type="Submit" name="mm0" value="Continue"></p>
</form>';
}

if($adminmode==3)
{
	print '<h2>Confirmation of new Players</h2>
<p>Please check the following table and decide for each player, if she/he can play:</p>
<form action="/exp5/reg8.php" method="post">
<table border=1>
<tr><th>#</th><th>Name</th><th>PokerTh</th><th>IP</th><th>Confirm</th><th>Reject</th></tr>
';
	for($i1=0;$i1<count($conarray);$i1++)
	{
		print "<tr><td>";
		print $i1+1;
		print "</td><td>";
		$pn2 = $conarray[$i1];
		print $pn2;
		print "</td><td><a href=\"https://www.pokerth.net/leaderboard/$pn2\">Link</a></td>";
		print "<td><small>$conipa[$i1]</small></td>";
		print '<td><input type="radio" name="c';
		print $cida[$i1];
		print '" value=1></td>';
		print '<td><input type="radio" name="c';
		print $cida[$i1];
		print '" value=2></td></tr>';
	}
	print "</table>";
	print '<p>Password: <input type="password" name="pass" value="';
	print $_POST['pass']; print '">
<input type="Submit" name="mm7" value="Go!"></p>
</form>';
}

$step = $_POST['step'];
if($step<1 or $step>4) $step=1; //change or $step>3
if($step==2 or $step==3 or $step==4) $dtarr=calcnextdate($step); //step==4
if($adminmode==1)
{
	print "<h2>Registrated Players in Step $step ($dtarr[1])</h2>";
	$request = "SELECT MAX(tpos) FROM registr WHERE step=$step AND plantime='$dtarr[0]' AND tpos<61";
	$result = mysql_query($request);
	$row = mysql_fetch_array($result);
	$i2 = (int)$row[0];
	print '<form action="/exp5/reg8.php" method="post">';
	for($i1=1;$i1*10< $i2+10 or $i1==1;$i1++)
	{	
		$cs = 4;
		if($step==1)$cs = 5;
		
		print "<br><table border=1><tr><th colspan=$cs>";
		if($i2-$i1*10+10 < 6 and $i1>1) print "Substitutes";
		else print "Table $i1";
		print "</th><td>Remove</td><!--<td>Swap/rotate</td>--></tr>";
		for($i3=1;$i3<=10;$i3++)
		{
			$i4=$i3+10*$i1-10;
			$request="SELECT name,ip,settings,id FROM registr WHERE step=$step AND plantime='$dtarr[0]' AND tpos=$i4";
			$result = mysql_query($request);
			$c=0;
			while($row=mysql_fetch_object($result))
			{
				$c++;
				$pname = $row->name;
				$ip = $row->ip;
				$id = $row->id;
				$settings = $row->settings;
			}
			if($c==0) print "<tr><td>$i3</td><td colspan=$cs>";
			else 
			{
				print "<tr><td>$i3.</td><td>";
				if($settings==1 or $settings==3) print"<span style=\"color:#FF00FF\">new player</span>";
				if($step==1)print "</td><td>";
				print "$pname</td><td>";
        $pname = rawurlencode($pname);
				print "<small>$ip</small>";
				print "</td>";
				print "<td><a href=\"https://www.pokerth.net/component/pthranking/?view=pthranking&layout=profile&username=$pname\" target=\"blank\">Ranking-Page</a>";
				print "</td><td><input type=\"checkbox\" name=\"remove[]\" value=\"$id\"></td>";
			}
			print "<!--<td><input type=\"checkbox\" name=\"rotate[]\" value=\"$id\"></td>--></tr>";
		}
		
		print "</table>";
	}
	//print '<p><input type="radio" name="ropt" value=2 checked>Rotate forward
//<input type="radio" name="ropt" value=3>Rotate backwards
//<!--<input type="radio" name="ropt" value=1>only allow swaps--></p>';
	//print '<input type="hidden" name="step" value=$step>';
	print '<p>Password: <input type="password" name="pass" value="';
	print $_POST['pass']; print '">
<input type="Submit" name="mm5" value="Go!"></p>
</form>';
	print "</form>";
}
if($adminmode==5)
{
	$remove = $_POST['remove'];
	//$rotate = $_POST['rotate'];
	//$ropt = $_POST['ropt'];
	//$step = $_POST['step'];
	$rec = count($remove);
	$roc = count($rotate);
	$err2 = array();
	$remname = array();
	$tplan=-1;
	for($i1=0;$i1<$rec;$i1++)
	{
		$id = $remove[$i1];
		$request = "SELECT name, settings, step, plantime FROM registr WHERE id=$id";
		$result = mysql_query($request);
		$c=0;
		while($row = mysql_fetch_object($result))
		{
			$remname[$i1]=$row->name;
			$settings=$row->settings;
			$step = $row->step;
			$tplan = $row->plantime;
			$c++;
		}
		if($c==0)$err2[$i1]=1;
		if($c==0) continue;
		$err2[$i1]=0;
		$settings += 16;
		$request = "UPDATE registr Set settings=$settings, tpos=333 WHERE id=$id";
		$result = mysql_query($request);
		if($result===false) $err2[$i1]=2;
	}
	
		//slide up - aufrutschen - 
	$sqlcond = "step=$step AND plantime='$tplan' AND tpos<61";
	for($i1=0;$i1<200 and $tplan!=-1;$i1++)
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
		if($result===false) $err3=2;
	}
	
	
	
	//print "<p>$rec::$roc::$ropt</p>";
	print '<p>You wanted to do the following actions. Unless there is an error, 
you were successful.</p>';
	print '<table><tr><th>Player</th><th>Action</th></tr>';
	for($i1=0;$i1<$rec;$i1++)
	{
		print "<tr><td>$remname[$i1]</td>";
		if($err2[$i1]==0) print "<td>removed</td>";
		else print "<td>removal failed</td>";
		print "</tr>";
	}
	print "</table>";
	
	print '<form action="/exp5/reg8.php" method="post">';
	print '<p>Password: <input type="password" name="pass" value="';
	print $_POST['pass']; print '">
<input type="Submit" name="mm0" value="Continue"></p>
</form>';

}




?>


<?php
include "footer1.php";
?>

</body>
</html>
