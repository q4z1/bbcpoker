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
include "exp6/func2.php";
?>
<h1>Manipulate Tickets - second page</h1>
<p>this page is only for admins</p>

<?php
/*function tickedit($action, $pid, $gameno = -1,$newval = -1, $reason=-1)
{	//TAKE CARE OF STEP
	//already have connection to DB
	if($action < 3 or $action==4 or $action ==10) return 0;
	if($pid < 1025) return 0;
	chdir("/home/www/bbc/");
	$file = fopen("exp4/ticketlog1.txt","a");
	if($action <13)
	{
		if($newval !=-1 or $reason !=-1) return 0;
		$request = "SELECT ts2, ts3 FROM table2 WHERE playerid = $pid"; 
		$result = mysql_query($request);
		while($row = mysql_fetch_object($result)){$ts2 = $row->ts2;$ts3 = $row->ts3;}
		if($action==3) $newval = $ts2 + 1;
		if($ts2 >= 10 and $action ==3) {$newval=10;$action = 4;}
		if($action==5) $newval = $ts2 - 1;
		if($action==6) $newval = $ts2;
		if($action==7) $newval = $ts2 - 1;
		if($action==8) $newval = $ts2 - 1;
		if($action==9) $newval = $ts3 + 1;
		if($ts3 >= 10 and $action ==9) {$newval=10;$action = 10;}
		if($action==11) $newval = $ts3 - 1;
		if($action==12) $newval = $ts3 - 1;
		if($newval>10 or $newval<0) return 0;
		$request = "UPDATE table2 Set ts2 = $newval WHERE playerid = $pid";
		if($action>8) $request = "UPDATE table2 Set ts3 = $newval WHERE playerid = $pid";
		$result = mysql_query($request);
		if($result===false) return 0;
		$timeint = time();
		$entry = "$timeint#$action#$pid#$newval#$gameno\n";
		fwrite($file,$entry);
		return 1;
	}
	if($action > 12 and $action < 21) return 0;
	if($action > 30) return 0;
	if($gameno>0) return 0;
	$request = "SELECT ts2, ts3, ts4 FROM table2 WHERE playerid = $pid"; 
	$result = mysql_query($request);
	while($row = mysql_fetch_object($result)){$ts2 = $row->ts2;$ts3 = $row->ts3;}
	if($action==21)$newval=$ts2+1;	
	if($action==22)$newval=$ts3+1;
	if($action==23)$newval=$ts2-1;	
	if($action==24)$newval=$ts3-1;	
	if($action==29)$newval=$ts4+1;	
	if($action==30)$newval=$ts4-1;	
	$X = 3;
	if(($action < 27 or $action==29 or action==30) and ($reason == "" or $reason==-1)) return 0;
	if(($action==27 or $action==28) and $reason != -1) return 0;
	if($reason==-1) $reason = " - ";
	if(strpos($reason,"\n") !== false or strpos($reason,"#") !== false) return 0;
	if($action==21 or $action ==23 or $action == 25 or $action == 27) {$X = 2;
	$request = "UPDATE table2 Set ts2 = $newval WHERE playerid = $pid";}
	if($action==22 or $action==24 or $action==26 or $action==28)
		$request = "UPDATE table2 Set ts3 = $newval WHERE playerid = $pid";
	if($action==29 or $action==30)
		$request = "UPDATE table2 Set ts4 = $newval WHERE playerid = $pid";
	$result = mysql_query($request);
	if($result===false) return 0;
	$timeint = time();
	$entry = "$timeint#$action#$pid#$newval#$reason\n";
	fwrite($file,$entry);
	return 1;
}*/
$error=0;
$pname = $_POST['player'];
$pid =0;
$action = $_POST['action'];
$action = (int)$action;
$reason = $_POST['reason'];
if(($action < 21 or $action>30) and ($action>28 or $action<25))$error=124;

$pname = trim($pname);

if($userpass!="nelly" and $userpass != "supernoob" and $userpass !="sp0ck")
{ 	//if password is wrong
	$error = 121;
	$errorinfo1 = " ";
}

if($error==0) // get playerid
{
	$request = "SELECT playerid FROM table2 WHERE name LIKE '$pname'";
	$c=0;
	$result = mysql_query($request)
		OR die("Error: $request <br>".mysql_error());
	while($row = mysql_fetch_object($result))
	{
		$c++;
		$pid=$row->playerid;
	}
	if($c==0) {$error=122;$errorinfo1=$pname;}
	if($error==0 and $pid<1025) {$error=123;$errorinfo1=$pname;}
}
if($error==0)
{
	if(tickedit($action,$pid,-1,-1,$reason)==0) {$error=125;$errorinfo1="$action,$pid";} //change tickets
}


if($error!=0) include("exp2/error.php");
else print "it looks like you succeeded with your request";

?>


<?php
include "footer1.php";
?>

</body>
</html>
