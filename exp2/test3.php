<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$auth1=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc']==2 or $_SESSION['upc']==3)
    $auth1 = 1;
} //$_COOKIE['PHPSESSID'] != ""


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
include_once "exp6/func2.php";
?>


<?php


if($firsttime==0)  // first time defined this function (bad programming, you should use include_once)
{	
	//print ":::: $firsttime";
	function incpc($pc,$place)  // increment placecount
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

$pnames=$_POST["pl"];
//die(var_export($pnames, true));
$step=(int)$_POST["step"];
$newp1= $_POST["newplayer"];
$newp2=array(0,0,0,0,0,0,0,0,0,0);
$pid=array(0,0,0,0,0,0,0,0,0,0);
print " <br>";
print "<h1>Upload done!</h1>";
$error = 0;//no errors yet

if($auth1==0) 
{
  $error=1;
  $errorinfo1 = " - ";
  
}


if($error==0)
{
	if($step<1 or $step>4) $error =3; 
	if(count($pnames) != 10) $error=2; // all rows should have an input
	//else if($pnames[1]=="0") $error=8; // second place not 0 player? (dont understand my code here...)
	if(count($pnames) ==0) $error=7; // different error
	//if($step>1 and count($newp1)>0) $error=6; // no new players in step2,3,4
	//punish - errors
	$punish = $_POST['punish'];
	$date2 = $_POST['date'];
	$time2 = $_POST['time'];
	$temp1=strtotime("$date2 $time2");
	if($temp1 === false) $error=51;
	if($temp1>time()) $error=51; // date in the future
	if($step==1) 
	{
		for($i=0;$i<4;$i++) if($punish[$i] != "") $error=52; // now punish in step1
	}
}

for($i=0;$i<10;$i++) $pnames[$i] = trim($pnames[$i]); // trim names
for($i=0;$i<4;$i++) $punish[$i] = trim($punish[$i]); // trim punish names
for($i=0;$i<4;$i++) if($punish[$i] == "0") $punish[$i]=""; //cannot punish player 0

/* @XXX: not longer needed - bbcbot shows known players for step >1 / step = 1 new players will be handled differently
$i2=0;  
for($i=1;$i<=10;$i++) // set the entry to 1 in $newp2 if player was checked as new player
{	
	if((string)$i == $newp1[$i2])
	{	
		$i2++;
		$newp2[$i-1]=1;
	}
}
*/
/*
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
*/
if($error==0)
{
	for($i=0;$i<10;$i++) 
	{
		$pnames[$i] = mysql_real_escape_string($pnames[$i]); // i hope we do that always 
	}
	
	for($i=0;$i<=9 and $error==0;$i++)
	{
		$request = "SELECT playerid, ts2, ts3, ts4 FROM table2 WHERE name = '$pnames[$i]'";
		$c=0;
		$result = mysql_query($request)
			OR die("Error: $request <br>".mysql_error()); // what if empty result? no error, just 0 rows as result :)
		while($row = mysql_fetch_object($result))
		{
			$c++;
			$pid[$i]=$row->playerid;
			if($pid[$i]==1998) $pid[$i]=1992; // DerBloedmann fix // HACK
			if($pid[$i]==2523) $pid[$i]=2456; // Suiy^obi fix // HACK
			$ts2 = $row->ts2;
			$ts3 = $row->ts3;
			$ts4 = $row->ts4;
		}
		$errorinfo1=$pnames[$i];
		//if($c==0 and $newp2[$i]==0) $error=14; // not found in db and not new player
		if($error==0 and $step==2 and $ts2 <1 and $pid[$i]>1024) $error=49; //
		if($error==0 and $step==3 and $ts3 <1 and $pid[$i]>1024) $error=50;
		if($error==0 and $step==4 and $ts4 <1 and $pid[$i]>1024) $error=50; // step4
		//if($c==1 and $newp2[$i]==1) $error=15;//maybe not?
		if($c>1) $error=16; // more than one result
		if($error !=0) continue;
		if($c!=0 ) continue;
		// start creating new player
		$maxpid=1;
		$request = "SELECT MAX(playerid) FROM table2";
		$result = mysql_query($request)
			OR die("Error: $request <br>".mysql_error());
		//while($row = mysql_fetch_object($result)) {$maxpid= $row->playerid;print "$maxpid";}
		$result = mysql_fetch_array($result);
		$maxpid=$result[0];
		if($maxpid==0) {$error=20;continue;}
		if($maxpid==1) {$error=21;continue;}
		$maxpid++; // determine playerid
		$request = "INSERT INTO table2 
(playerid, name)
VALUES
($maxpid, '$pnames[$i]')"; // others are default
		$result = mysql_query($request)OR die("Error: $request <br>".mysql_error());
		if(!$result) $error = 17;
		//$newp2[$i]=0;//not a new player now
		$i--; // do the loop again for that player, i dont know why => @XXX: because of the error values?
	}
}
//get punish id
//TICKET
//$i2=count($punish);
$punishid=array(0,0,0,0);
for($i=0;$i<4 and $error==0;$i++)
{
	$request = "SELECT playerid, ts2, ts3, ts4 FROM table2 WHERE name = '$punish[$i]'";
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
		$ts4 = $row->ts4;
		
	}
	if($c==0) {$error=53;$errorinfo1=$punish[$i];}
	if($punishid[$i]<1025) {$error=54;$errorinfo1=$punish[$i];}
	if($error==0 and $step==2 and $ts2<1) $error=47;
	if($error==0 and $step==3 and $ts3<1) $error=48;
	if($error==0 and $step==4 and $ts4<1) $error=48;
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
		if($pid[$i1]==$punishid[$i3]) $error=55; // punished player cannot be among the players
		if($i1 <$i2 and $i1!=$i3 and $punishid[$i1]==$punishid[$i3] and $punishid[$i1]!=0) $error=56; 
	}
}



//$pid has now all pid
if($error==0) //write in game result
{	
	$errorinfo1="none";
	$gameno=0;
	//get season number
	// @YYY : step4 determins the season now
	$request = "SELECT IFNULL(MAX(gameno),0) FROM table1 WHERE step = '4'";
	$result=mysql_query($request);
	$row=mysql_fetch_array($result);
	$season = 19 + (int)$row[0]; // first season with step4 number is season 19

	if(array_key_exists("reupload", $_POST) && $_POST["reupload"] != $gameno-1){
		$gameno = $_POST["reupload"];
		$now=date("Y-m-d H:i:s");
		$datetime = "$date2 $time2";
		$result = mysql_query("SELECT * from table1 WHERE step = '$step' and gameno = '$gameno';");
		$obj=mysql_fetch_object($result);
		$request = "UPDATE table1 set step = $obj->step, gameno = $obj->gameno, p1 = $pid[0], p2 = $pid[1], p3 = $pid[2], p4 = $pid[3], p5 = $pid[4], p6 = $pid[5], p7 = $pid[6], p8 = $pid[7], p9 = $pid[8], p10 = $pid[9], datetime = '$datetime', season = '$season', inputtime = '$now'  WHERE `id` = '$obj->id';";
			if($error==0) $result = mysql_query($request);// OR die("Error: $request <br>".mysql_error());
			if($error==0 and !$result) $error = 18;
			//echo $request."<hr />";
			//die(var_export($request,true));
	}else{
		$reupload = "";
		//get new gameno
		$request = "SELECT IFNULL(MAX(gameno),0) FROM table1 WHERE step = '$step'";
		$result=mysql_query($request);
		$row=mysql_fetch_array($result);
		$gameno = 1+(int)$row[0];
		for($i=$gameno-1;$i>$gameno-5 and $i>0 and $error==0;$i--)//compare if identical to 4 previous games
			// but they would need to have the exact same positions for the 10 players
			{
				$request= "SELECT * from table1 WHERE step = '$step' and gameno = '$i'";
				$result=mysql_query($request);
				$c=0;
				while($row = mysql_fetch_object($result))
				{
					$c++;
					if($row->p1 == $pid[0] and $row->p2 == $pid[1] and $row->p3 == $pid[2] and $row->p4 == $pid[3] 
							and $row->p5 == $pid[4] and $row->p6 == $pid[5] and $row->p7 == $pid[6] 
							and $row->p8 == $pid[7] and $row->p9 == $pid[8] and $row->p10 == $pid[9]) $error=9;
				}	
				$errorinfo1="$i, $gameno";
				//if($error==0 and $c==0) $error = 23; // seems to have no effect and is useless for deleted games (missing gameno)???
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
}
if($error==0) // @TODO : calculate the new score with step4 also in controlpanel
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
			$a4pc=$row->a4placecount;
			$s1pc=$row->s1placecount;
			$s2pc=$row->s2placecount;
			$s3pc=$row->s3placecount;
			$s4pc=$row->s4placecount;
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
		if($step==4) $a4pc=incpc($a4pc,$i+1);
		if($step==4) $s4pc=incpc($s4pc,$i+1);
		$alltimepoints += $step*(10-$i);
		$saisonpoints += $step*(10-$i);

    $a1 = explode(",", $a1pc);
    $a2 = explode(",", $a2pc);
    $a3 = explode(",", $a3pc);
    $a4 = explode(",", $a4pc);

		/*if($step==3)  @YYY not according to new score calculation
		{
		$alltimepoints += (10-$i);
		$saisonpoints += (10-$i);
		} */ 
		$agames++;
		$sgames++;
		$ascore = calcscore($alltimepoints,$agames);
		$sscore = calcscore($saisonpoints,$sgames);
    
    // @XXX: update roi calculation here
    $roi100 = 0;
    $roia = 0;
    // alltime games
    if($agames >= 100){
      $number_of_games_total = 100;
      $plid = $pid[$i];
      $number_of_1st = 0;
      $number_of_2nd = 0;
      $number_of_3rd = 0;
      $sql2 = "
        SELECT * FROM table1 WHERE
        (
          p1 = $plid OR
          p2 = $plid OR
          p3 = $plid OR
          p4 = $plid OR
          p5 = $plid OR
          p6 = $plid OR
          p7 = $plid OR
          p8 = $plid OR
          p9 = $plid OR
          p10 = $plid
        )
        ORDER BY datetime DESC
        LIMIT 0, $number_of_games_total
      ";
      $res2=mysql_query($sql2);
      while($r = mysql_fetch_object($res2))
      {
        if($r->p1 == $plid){
          $number_of_1st++;
        }elseif($r->p2 == $plid){
          $number_of_2nd++;
        }elseif($r->p3 == $plid){
          $number_of_3rd++;
        }
      }
      $roi_percent = 100*($number_of_1st * 45 + $number_of_2nd * 45 + $number_of_3rd * 10 - $number_of_games_total * 10) / ($number_of_games_total * 10);
      $roi100 = round($roi_percent, 2, PHP_ROUND_HALF_UP) * 100; // make an integer
      
      // roi alltime
      $number_of_1st = 0;
      $number_of_2nd = 0;
      $number_of_3rd = 0;
      $number_of_games_total = $agames;
      $number_of_1st = $a1[0] + $a2[0] + $a3[0] + $a4[0];
      $number_of_2nd = $a1[1] + $a2[1] + $a3[1] + $a4[1];
      $number_of_3rd = $a1[2] + $a2[2] + $a3[2] + $a4[2];
      $roi_percent = 100*($number_of_1st * 45 + $number_of_2nd * 45 + $number_of_3rd * 10 - $number_of_games_total * 10) / ($number_of_games_total * 10);
      $roia = round($roi_percent, 2, PHP_ROUND_HALF_UP) * 100; // make an integer
    }
     // @XXX: end roi calc
    
		$request="UPDATE table2 Set 
a1placecount = '$a1pc',
a2placecount = '$a2pc',
a3placecount = '$a3pc',
a4placecount = '$a4pc',
s1placecount = '$s1pc',
s2placecount = '$s2pc',
s3placecount = '$s3pc',
s4placecount = '$s4pc',
alltimepoints = $alltimepoints,
saisonpoints = $saisonpoints,
alltimegames = $agames,
saisongames = $sgames,
alltimescore = $ascore,
saisonscore = $sscore,
alltimeroi = $roia,
hundredroi = $roi100 
WHERE playerid='$pid[$i]'";
		$result=mysql_query($request)OR die("Error: $request <br>".mysql_error());
		if(!$result) $error = 19;
	}
}
if($error==0) //input tickets new step4 tickets
{
//function tickedit($action, $pid, $gameno = -1,$newval = -1, $reason=-1)
	if($step==1)
	{
		if(tickedit(3,$pid[0],$gameno) ==0) {$error=58;}
		if(tickedit(3,$pid[1],$gameno)==0) $error=59;
	}
	if($step==2)
	{
		for($i=3;$i<10;$i++) if(tickedit(5,$pid[$i],$gameno)==0) $error=60;
		if(tickedit(6,$pid[2],$gameno)==0) $error=61;
		if(tickedit(7,$pid[1],$gameno)==0) $error=62;
		if(tickedit(7,$pid[0],$gameno)==0) $error=63;
		for($i=0;$i<count($punishid);$i++) if(tickedit(8,$punishid[$i],$gameno)==0) $error=64;
		if(tickedit(9,$pid[1],$gameno)==0) $error=65;
		if(tickedit(9,$pid[0],$gameno)==0) $error=66;
	}
	if($step==3)
	{		
		for($i=3;$i<10;$i++) if(tickedit(11,$pid[$i],$gameno)==0) $error=61;
		if(tickedit(13,$pid[2],$gameno)==0) $error=62;
		if(tickedit(11,$pid[1],$gameno)==0) $error=63;
		if(tickedit(11,$pid[0],$gameno)==0) $error=63;
		for($i=0;$i<count($punishid);$i++) if(tickedit(12,$punishid[$i],$gameno)==0) $error=58;
		if(tickedit(14,$pid[1],$gameno)==0) $error=64;
		if(tickedit(14,$pid[0],$gameno)==0) $error=65;
	}
	if($step==4)
	{
		for($i=0;$i<10;$i++) if(tickedit(16,$pid[$i],$gameno)==0) $error=60;
		for($i=0;$i<count($punishid);$i++) if(tickedit(17,$punishid[$i],$gameno)==0) $error=58;
		
	}
}
if($error==0 and $step==4) //START a new SEASON after step4
{
	$request="UPDATE table2 Set 
s1placecount = '0,0,0,0,0,0,0,0,0,0',
s2placecount = '0,0,0,0,0,0,0,0,0,0',
s3placecount = '0,0,0,0,0,0,0,0,0,0',
s4placecount = '0,0,0,0,0,0,0,0,0,0',
saisonpoints = 0,
saisonscore = 0,
saisongames = 0
WHERE playerid > 1023";
		$result=mysql_query($request)OR die("Error: $request <br>".mysql_error());
		if(!$result) $error = 19;
}

if($error!=0) include("exp2/error.php");
else print "<p>It looks like there was no error.<br>
<a href=\"exp6/getcode1.php?s=$step&amp;g=$gameno\">Click here to get Code for forum Report</a>
</p>";

if($error==0) 
{
	include_once("exp6/func3.php");
	$stringstartmonththen=date("Y-m",time()-49*86400)."-01 00:00:00";
	calcrating2($stringstartmonththen,0,0,1,0,0,0);
	$sfile=fopen("exp2/systemtodo.txt","a");
	fwrite($sfile,"bbcbotmakeminidb\n");
	fclose($sfile);
	print "<p>The server recalculated the rating</p>";
	
}
print "<br> <br> ";
print "Your input was:<br>";
$i = 1;
$i2=0;
for($i=1;$i<=10;$i++)
{
	print "$i. Place: ";
	print $pnames[$i-1];
	/*
	if((string)$i == $newp[$i2])
	{	
		$i2++;
		print "<br>This is a new Player!";
	}
	*/
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
