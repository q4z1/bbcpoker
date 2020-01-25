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
if($firsttime==0) 
{	
	//print ":::: $firsttime";
	function incpc($pc,$place)
	{
		if($place<1 or $place>10) return $pc;
		$pcarray = explode(",",$pc);
		if(count($pcarray)!=10) return "error";
		$place--;
		$c1 = $pcarray[$place];
		$c2 = (int)$c1;
		$c2++;
		$pcarray[$place]=(string)$c2;
		return implode(",",$pcarray);
	}
	$firsttime=1;
}
function tickedit($action, $pid, $gameno = -1,$newval = -1, $reason=-1)
{	//already have connection to DB
	if($action < 3 or $action==4 or $action ==10) return 0;
	if($pid < 1025) return 1; //
	require_once $_SERVER['DOCUMENT_ROOT'].'/defines.php';
chdir(ROOT_DIR);
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
	if($action > 28) return 0;
	if($gameno>0) return 0;
	$request = "SELECT ts2, ts3 FROM table2 WHERE playerid = $pid"; 
	$result = mysql_query($request);
	while($row = mysql_fetch_object($result)){$ts2 = $row->ts2;$ts3 = $row->ts3;}
	if($action==21)$newval=$ts2+1;	
	if($action==22)$newval=$ts3+1;
	if($action==23)$newval=$ts2-1;	
	if($action==24)$newval=$ts3-1;	
	$X = 3;
	if($action < 27 and ($reason == "" or $reason==-1)) return 0;
	if($action > 26 and $reason != -1) return 0;
	if($reason==-1) $reason = " - ";
	if(strpos($reason,"\n") !== false or strpos($reason,"#") !== false) return 0;
	if($action==21 or $action ==23 or $action == 25 or $action == 27) {$X = 2;
	$request = "UPDATE table2 Set ts2 = $newval WHERE playerid = $pid";}
	else $request = "UPDATE table2 Set ts3 = $newval WHERE playerid = $pid";
	$result = mysql_query($request);
	if($result===false) return 0;
	$timeint = time();
	$entry = "$timeint#$action#$pid#$newval#$reason\n";
	fwrite($file,$entry);
	return 1;
}

//$season=2;
$pnames=$_POST["place"];
$step=(int)$_POST["step"];
$newp1= $_POST["newplayer"];
$newp2=array(0,0,0,0,0,0,0,0,0,0);
$pid=array(0,0,0,0,0,0,0,0,0,0);
print " <br>";
print "<h1>test2</h1>";
$error = 0;//no errors yet
if($userpass!="nelly" and $userpass!="supernoob" and $userpass!="creeper"and $userpass!="nahajasaki" and 
$userpass!="Rezos" and $userpass!="Jastura" and $userpass!="l0stman" and $userpass!="MasterG84" and
 $userpass!="SchlumpfineX" and $userpass!="akia" and $userpass!="ElmoEGO" and 
 $userpass!="joe4135" and $userpass!="Gary_Ch" and $userpass!="sp0ck")
{ 	//if password is wrong
	$error = 1;
	$errorinfo1 = " - ";
}
if($error==0)
{
	if($step<1 or $step>3) $error =3;
	if(count($pnames) != 10) $error=2;
	else if($pnames[1]=="0") $error=8;
	if(count($pnames) ==0) $error=7;
	if($step>1 and count($newp1)>0) $error=6;
	//punish - errors
	$punish = $_POST['punish'];
	$date2 = $_POST['date'];
	$time2 = $_POST['time'];
	if(strtotime("$date2 $time2") === false) $error=51;
	if(strtotime("$date2 $time2")>time()) $error=51;
	if($step==1) 
	{
		for($i=0;$i<4;$i++) if($punish[$i] != "") $error=52;
	}
}

for($i=0;$i<10;$i++) $pnames[$i] = trim($pnames[$i]);
for($i=0;$i<4;$i++) $punish[$i] = trim($punish[$i]);
for($i=0;$i<4;$i++) if($punish[$i] == "0") $punish[$i]="";

$i2=0;
for($i=1;$i<=10;$i++)
{	
	if((string)$i == $newp1[$i2])
	{	
		$i2++;
		$newp2[$i-1]=1;
	}
}
//further error detecting without database: double usernames, 0 not at the end 
for($i=0;$i<10 and $error==0;$i++)
{
	for($i2=$i+1;$i2<10;$i2++)// $i2 > $i
	{
		if($pnames[$i]==$pnames[$i2] and $pnames[$i]!="0"){$error=4;$errorinfo1=$pnames[$i];}
		if($pnames[$i]=="0" and $pnames[$i2]!="0"){$error=5;$errorinfo1=$pnames[$i2];}
	}
	$i2=count($punish);
	
}
	
if($error==0)
{
	for($i=0;$i<10;$i++)
	{
		$pnames[$i] = mysql_real_escape_string($pnames[$i]);
	}
	
	for($i=0;$i<=9 and $error==0;$i++)
	{
		$request = "SELECT playerid, ts2, ts3 FROM table2 WHERE name = '$pnames[$i]'";
		$c=0;
		$result = mysql_query($request)
			OR die("Error: $request <br>".mysql_error());
		while($row = mysql_fetch_object($result))
		{
			$c++;
			$pid[$i]=$row->playerid;
			$ts2 = $row->ts2;
			$ts3 = $row->ts3;
		}
		$errorinfo1=$pnames[$i];
		if($c==0 and $newp2[$i]==0) $error=14;
		if($error==0 and $step==2 and $ts2 <1 and $pid[$i]>1024) $error=49;
		if($error==0 and $step==3 and $ts3 <1 and $pid[$i]>1024) $error=50;
		//if($c==1 and $newp2[$i]==1) $error=15;//maybe not?
		if($c>1) $error=16;
		if($error !=0) continue;
		if($c!=0 ) continue;
		$maxpid=1;
		$request = "SELECT MAX(playerid) FROM table2";
		$result = mysql_query($request)
			OR die("Error: $request <br>".mysql_error());
		//while($row = mysql_fetch_object($result)) {$maxpid= $row->playerid;print "$maxpid";}
		$result = mysql_fetch_array($result);
		$maxpid=$result[0];
		if($maxpid==0) {$error=20;continue;}
		if($maxpid==1) {$error=21;continue;}
		$maxpid++;
		$request = "INSERT INTO table2 
(playerid, name)
VALUES
($maxpid, '$pnames[$i]')";
		$result = mysql_query($request)OR die("Error: $request <br>".mysql_error());
		if(!$result) $error = 17;
		$newp2[$i]=0;//not a new player now
		$i--;
	}
}
//get punish id
//TICKET
//$i2=count($punish);
$punishid=array(0,0,0,0);
for($i=0;$i<4 and $error==0;$i++)
{
	$request = "SELECT playerid, ts2, ts3 FROM table2 WHERE name = '$punish[$i]'";
	$c=0;
	$punishid[$i]=0;
	if($punish[$i]=="") continue;
	$result = mysql_query($request)
		OR die("Error: $request <br>".mysql_error());
	while($row = mysql_fetch_object($result))
	{
		$c++;
		$punishid[$i]=$row->playerid;
		$ts2 = $row->ts2;
		$ts3 = $row->ts3;
		
	}
	if($c==0) {$error=53;$errorinfo1=$punish[$i];}
	if($punishid[$i]<1025) {$error=54;$errorinfo1=$punish[$i];}
	if($error==0 and $step==2 and $ts2<1) $error=47;
	if($error==0 and $step==3 and $ts3<1) $error=48;
}

//ERROR detection about "reserved but missing" input
if(count($punishid) != count($punish) and $error==0) {$error=57;
$c1 = count($punishid); 
$c2 = count($punish);
$errorinfo1="$c1 ; $c2"; }
$i2 = count($punishid);
for($i1=0;$i1<10 and $error==0;$i1++)
{
	for($i3=0;$i3<$i2;$i3++)
	{
		if($pid[$i1]==$punishid[$i3]) $error=55;
		if($i1 <$i2 and $i1!=$i3 and $punishid[$i1]==$punishid[$i3] and $punishid[$i1]!=0) $error=56;
	}
}



//$pid has now all pid
if($error==0) //write in game result
{	
	$errorinfo1="none";
	$gameno=0;
	//get season number
	$request = "SELECT MAX(gameno) FROM table1 WHERE step = '3'";
	$result=mysql_query($request);
	$row=mysql_fetch_array($result);
	$season = 1 + $row[0];
	//get new gameno
	$request = "SELECT MAX(gameno) FROM table1 WHERE step = '$step'";
	$result=mysql_query($request);
	$row=mysql_fetch_array($result);
	$gameno = 1+$row[0];
	for($i=$gameno-1;$i>$gameno-5 and $i>0 and $error==0;$i--)//compare if identical to 4 previous games
	{
		$request= "SELECT * from table1 WHERE step = '$step' and gameno = '$i'";
		$result=mysql_query($request);
		$c=0;
		while($row = mysql_fetch_object($result))
		{
			$c++;
			if($row->p1 == $pid[0] and $row->p2 == $pid[1] and $row->p3 == $pid[2] and $row->p4 == $pid[3] 
					and $row->p5 == $pid[4] and $row->p6 == $pid[5] and $row->p7 == $pid[6] 
					and $row->p8 == $pid[7] and $row->p9 == $pid[8] and $row->p10 == $pid[9] ) $error=9;
		}	
		$errorinfo1="$i, $gameno";
		if($error==0 and $c==0) $error = 23;
	}
	$datetime = "$date2 $time2";
	$now=date("Y-m-d H:i:s");
	$request = "INSERT INTO table1 
(step, gameno,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,datetime,season,inputtime)
VALUES
($step, $gameno,$pid[0],$pid[1],$pid[2],$pid[3],$pid[4],$pid[5],$pid[6],$pid[7],$pid[8],$pid[9],'$datetime',$season,'$now')";
	if($error==0) $result = mysql_query($request);// OR die("Error: $request <br>".mysql_error());
	if($error==0 and !$result) $error = 18;
}
if($error==0) 
{
	//update POINTS
	function calcscore($points, $games)//number of games
	{	
		if($games<=0 or $points<=0) return 0;
		$coefficient = 1 + log((float)$games, 2);//logarithm with base 2
		$score =(float)$points* $coefficient /(float)$games;
		return (int)($score*1000);
	}
	for($i=0;$i<10;$i++)
	{
		$request="SELECT * FROM table2 WHERE playerid='$pid[$i]'";
		$result=mysql_query($request);
		while($row=mysql_fetch_object($result))//read data
		{
			$a1pc=$row->a1placecount;
			$a2pc=$row->a2placecount;
			$a3pc=$row->a3placecount;
			$s1pc=$row->s1placecount;
			$s2pc=$row->s2placecount;
			$s3pc=$row->s3placecount;
			$alltimepoints=$row->alltimepoints;
			$saisonpoints=$row->saisonpoints;
			$agames=$row->alltimegames;
			$sgames=$row->saisongames;
		}	
		if($step==1) $a1pc=incpc($a1pc,$i+1);//change data
		if($step==2) $a2pc=incpc($a2pc,$i+1);
		if($step==3) $a3pc=incpc($a3pc,$i+1);
		if($step==1) $s1pc=incpc($s1pc,$i+1);
		if($step==2) $s2pc=incpc($s2pc,$i+1);
		if($step==3) $s3pc=incpc($s3pc,$i+1);
		$alltimepoints += $step*(10-$i);
		$saisonpoints += $step*(10-$i);
		if($step==3)
		{
		$alltimepoints += (10-$i);
		$saisonpoints += (10-$i);
		}
		$agames++;
		$sgames++;
		$ascore = calcscore($alltimepoints,$agames);
		$sscore = calcscore($saisonpoints,$sgames);
		$request="UPDATE table2 Set 
a1placecount = '$a1pc',
a2placecount = '$a2pc',
a3placecount = '$a3pc',
s1placecount = '$s1pc',
s2placecount = '$s2pc',
s3placecount = '$s3pc',
alltimepoints = $alltimepoints,
saisonpoints = $saisonpoints,
alltimegames = $agames,
saisongames = $sgames,
alltimescore = $ascore,
saisonscore = $sscore
WHERE playerid='$pid[$i]'";
		$result=mysql_query($request)OR die("Error: $request <br>".mysql_error());
		if(!$result) $error = 19;
	}
}
if($error==0) //input tickets
{
//function tickedit($action, $pid, $gameno = -1,$newval = -1, $reason=-1)
	if($step==1)
	{
		if(tickedit(3,$pid[0],$gameno) ==0) {$error=58;}
		if(tickedit(3,$pid[1],$gameno)==0) $error=58;
	}
	if($step==2)
	{
		for($i=3;$i<10;$i++) if(tickedit(5,$pid[$i],$gameno)==0) $error=58;
		if(tickedit(6,$pid[2],$gameno)==0) $error=58;
		if(tickedit(7,$pid[1],$gameno)==0) $error=58;
		if(tickedit(7,$pid[0],$gameno)==0) $error=58;
		for($i=0;$i<count($punishid);$i++) if(tickedit(8,$punishid[$i],$gameno)==0) $error=58;
		if(tickedit(9,$pid[1],$gameno)==0) $error=58;
		if(tickedit(9,$pid[0],$gameno)==0) $error=58;
	}
	if($step==3)
	{
		for($i=0;$i<10;$i++) if(tickedit(11,$pid[$i],$gameno)==0) $error=58;
		for($i=0;$i<count($punishid);$i++) if(tickedit(12,$punishid[$i],$gameno)==0) $error=58;
	}
}
if($error==0 and $step==3) //START a new SEASON
{
	$request="UPDATE table2 Set 
s1placecount = '0,0,0,0,0,0,0,0,0,0',
s2placecount = '0,0,0,0,0,0,0,0,0,0',
s3placecount = '0,0,0,0,0,0,0,0,0,0',
saisonpoints = 0,
saisonscore = 0,
saisongames = 0
WHERE playerid > 1023";
		$result=mysql_query($request)OR die("Error: $request <br>".mysql_error());
		if(!$result) $error = 19;	
}

if($error!=0) include("exp2/error.php");
else print "<p>It looks like there was no error.<br>
<a href=\"/exp6/getcode1.php?s=$step&amp;g=$gameno\">Click here to get Code for forum Report</a>
</p>";
print "<br> <br> ";
print "Your input was:<br>";
$i = 1;
$i2=0;
for($i=1;$i<=10;$i++)
{
	print "$i. Place: ";
	print $pnames[$i-1];
	if((string)$i == $newp[$i2])
	{	
		$i2++;
		print "<br>This is a new Player!";
	}
	print "\n <br>";
}
print "<br>Players reserved but missing:";
for($i=0;$i<4;$i++) print " $punish[$i], "; 

end:
?>
<?php
include "footer1.php";
?>
</body>
</html>