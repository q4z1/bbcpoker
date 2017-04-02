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


<?php
include "exp5/func1.php";

$step = (int)$_GET['s'];

// @TODO : if step==0, select the step wich game is next, or has higher step number


$pgame = (int)$_GET['g'];
if($step<1 or $step>4) $step=1;
if($step<4)
{
	$gamea=getrdates($step);
	$i2=count($gamea);
	$now=time();
	$timet2= date("Y-m-d H:i:s",$now+600);
	if($step==1) $timet1=date("Y-m-d H:i:s",$now-40*3600);
	if($step==2) $timet1=date("Y-m-d H:i:s",$now-86400*8);
	if($step==3) $timet1=date("Y-m-d H:i:s",$now-86400*17);
	//$timet2= date("Y-m-d H:i:s",time()+$tahead);
	$request= "SELECT * FROM dates WHERE step=$step and date>'$timet1' and date<'$timet2' ORDER BY DATE DESC";
	//$past=array();
	
	
	
	$result=mysql_query($request);
	$i1=-1;
	while($row = mysql_fetch_object($result))
	{
		$gamea[$i1] = strtotime($row->date);
		$i1--;
	}
	$i1++;//lowest array index
	$tc=array("");
	//if($pgame<$i1 or $pgame>=$i2) $pgame=0;
	//if ($i2==0) $pgame=-1;
	$tc[$pgame]=" class=\"current\"";
	$text1="future game";
	print "\n<nav class=\"nav\">\n<ul>";
	for($i3=$i2-1;$i3>=$i1;$i3--)
	{
		if($i3==-1)$text1="last game";
		if($i3==0)$text1="next game";
		if($i3<-1)$text1="old game";
		print "<li$tc[$i3]><a href=\"exp5/reg3.php?s=$step&g=$i3\">$text1</a></li>\n";
	
	}
	print "\n</ul></nav>\n";
	$unixgamet=$gamea[$pgame];
	$dtarr=array(date("Y-m-d H:i:s",$unixgamet),date("D, d M Y H:i T",$unixgamet));

}
/*if($step==3)
{
	if($pgame>2 or $pgame<1) $pgame=1;
	if($pgame==1) $t1=" class=\"current\"";
	if($pgame==2) $t2=" class=\"current\"";
	$t2="";
	print "<nav class=\"nav\">
	<ul><li$t1><a href=\"exp5/reg3.php?s=3&amp;g=1\">Next Game</a></li>
	<li$t2><a href=\"exp5/reg3.php?s=3&amp;g=2\">Old Game</a></li>
	</ul></nav>";
	if($pgame==1) $dtarr=calcnextdate($step);
	else $dtarr=calcnextdate($step,$pgame-1);

}*/

if($step==4)
{
	if($pgame>0 or $pgame<-1) $pgame=0;
	if($pgame==0) $t1=" class=\"current\"";
	if($pgame==-1) $t2=" class=\"current\"";
	print "<nav class=\"nav\">
	<ul><li$t1><a href=\"exp5/reg3.php?s=4&amp;g=0\">Next Game</a></li>
	<li$t2><a href=\"exp5/reg3.php?s=4&amp;g=-1\">Last Game</a></li>
	</ul></nav>";
	if($pgame==0)
	{
		$t1=getrdates(4)[0];
		if($_GET['debug']==1) print "<pre>".var_dump($t1)."...".strtotime("2030-04-04 00:00:03")."</pre>";
		if($t1==strtotime("2030-04-04 00:00:03")) 
		{	

			$t3=date("D, d M Y H:i T", strtotime(step4date()));
			$dtarr=array("2030-04-04 00:00:03","unknown (maybe $t3)");
			
		}
		else $dtarr=array(date("Y-m-d H:i:s",$t1),date("D, d M Y H:i T",$t1));
	}
	if($pgame==-1)
	{
		$now2=date("Y-m-d H:i:s",time()+600);
		$request = "SELECT IFNULL(MAX(plantime),0) FROM registr WHERE step=4 AND plantime<'$now2'"; // get old date
		$result = mysql_query($request);
		$row=mysql_fetch_array($result);
		if($row[0]==0) $dtarr=array("2000-04-04 00:00:04","No game is here");
		else
		{	
			$lastgame = strtotime($row[0]);
			$dtarr=array(date("Y-m-d H:i:s",$lastgame),date("D, d M Y H:i T", $lastgame));
		}
	}
	/*if($pgame==1) $dtarr=calcnextdate($step);
	else $dtarr=calcnextdate($step,$pgame-1);
	*/
}


//if($pgame<1 or $pgame>3) $pgame=1;
//if($step==1 and $pgame>5) $pgame=1;
//if($step==2 and $pgame>3) $pgame=1;

$t4=strtotime($dtarr[0]);
if($dtarr[0]=="2030-04-04 00:00:03") $t4=strtotime(step4date());
date_default_timezone_set("America/New_york");
$t5=date("D, M jS g:i a T",$t4);


print "<h1>Registered Players in Step $step </h1>\n<h2>$dtarr[1] </h2>\n";
print "<h5>$t5</h5>\n";
date_default_timezone_set("Europe/Paris");

$sqlcond = "step=$step AND plantime='$dtarr[0]' AND tpos<61";
$request = "SELECT MAX(tpos) FROM registr WHERE $sqlcond";
$result = mysql_query($request);
$row = mysql_fetch_array($result);
$i2 = (int)$row[0];


calcadmins($dtarr[0],$step);

for($i1=1;$i1*10< $i2+11 or $i1==1;$i1++) // $i1 is the table number
{	
	print "<br><table border=1><tr><td></td>";
	//if($step==1) print "<th colspan=2>";
	if($step==2) print "<td>Ts2</td>";
	if($step==3) print "<td>Ts3</td>";
	if($step==4) print "<td>Ts4</td>";
	
	// @YYY: step 4 code (1l)
	//if($step==4) continue;
	
	print "<th colspan=2>";
	print "Table $i1";
	print "</th></tr>";
	for($i3=1;$i3<=10;$i3++)
	{
		$i4=$i3+10*$i1-10;
		$request="SELECT name,settings, playerid FROM registr WHERE $sqlcond AND tpos=$i4";
		$result = mysql_query($request);
		$c=0;
		while($row=mysql_fetch_object($result))
		{
			$c++;
			$pname = $row->name;
			$settings = $row->settings;
			$pid=$row->playerid;
		}
		if($c==0 and $step==1) print "<tr><td>$i3</td><td colspan=2></td></tr>";
		if($c==0 and ($step==2 or $step==3 or $step==4)) print "<tr><td>$i3</td><td colspan=3></td></tr>";
		if($c==0) continue;
		print "\n<tr><td>$i3.</td><td>";
		if($settings==1 or $settings==3 and $step==1) print"<span style=\"color:#FF00FF\">new player</span>";
		if($step==2 or $step==3 or $step==4)
		{
			$request="SELECT ts2,ts3,ts4,name FROM table2 WHERE playerid=$pid";
			$result = mysql_query($request);
			$c=0;
			while($row=mysql_fetch_object($result))
			{
				$c++;
				$ts2=$row->ts2;
				$ts3=$row->ts3;
				$ts4=$row->ts4;
				$pname=$row->name;
			}
			if($step==2 and $c==1 and $ts2>0) print "$ts2</td><td>";
			if($step==2 and $c==1 and $ts2<=0) print "<span style=\"color:#ff2222\">$ts2 (no ticket)</span></td><td>";
			if($step==3 and $c==1 and $ts3>0) print "$ts3</td><td>";
			if($step==3 and $c==1 and $ts3<=0) print "<span style=\"color:#ff2222\">$ts3 (no ticket)</span></td><td>";
			if($step==4 and $c==1 and $ts4>0) print "$ts4</td><td>";
			if($step==4 and $c==1 and $ts4<=0) print "<span style=\"color:#ff2222\">$ts4 (no ticket)</span></td><td>";
			if($c==0) print "</td><td>";
		}
		if($settings==32) print "<span style=\"color:#ff00ff\">admin</span>";
		print "</td><td>";
		if($pid!=999) print "<a href=\"exp5/players1.php?id=$pid\">$pname</a>";
		if($pid==999) print "<a href=\"http://poker-heroes.com/profile.html?user=$pname\">$pname</a>";
		//print "<td><a href=\"http://poker-heroes.com/profile.html?user=$pname\">Poker-Heroes profile</a>";
		print "</td></tr>";
	}
	print "</table>";
}
print "<p>if your table isnt full, we try to find new players in the lobby directly before the game.<br> 
You also can become a substitute for another table</p>";
if($step==1) print "<p>Games start only with 10 players</p>";
if($step==2 or $step==3 or $step==4) print "<p>Important for Step 2, 3, and 4: <br>you can cancel until 18:00 (that means 90 minutes before the game starts) 
without losing your ticket, by posting <a href=\"exp4/shoutbox1.php\">in our Shoutbox</a></p>";

?>


<?php
include "footer1.php";
?>

</body>
</html>
