<?php
//this .php file is only for functions used by other .php files
/*mysql_connect("localhost","bbcpoker","baguette")or die ("Internal MYSQL-ERROR");
mysql_select_db("bbcpoker") or die ("The Database does not exist");
*/

function blocker($insertgame=0)
{
	$text = file_get_contents("exp6/blockedgamenumbers.txt");
	$now=time();
	$returnarray=array(0,0,0,0,0,0,0);
	$rows=explode("\n",$text);
	$c1=count($rows);
	$i2=0;
	$update=1;
	if($insertgame==0) $update=0;
	for($i1=0;$i1<$c1 and $i2<6;$i1++)
	{
		$data1=explode("**",$rows[$i1]);
		if(count($data1)!=4) continue;
		$returnarray[$i2]=$data1;
		$i2=$i2+1;
		$then=strtotime($data1[2]);
		if($then<$now or $then>15*60+$now)
		{	
			$returnarray[$i2-1]=0;
			$update=1;
		}
	}	
	if($update==1)
	{
		if($insertgame!=0)
		{
			for($i1=0;$i1<6;$i1++)
			{	
				if($returnarray[$i1]==0)
				{
					$returnarray[$i1]=array($insertgame[0],$insertgame[1],date("Y-m-d H:i:s",$now+12*60),$insertgame[2]);
					break;
				}
			}
		}
		$text="";
		for($i1=0;$i1<6;$i1++)
		{
			if($returnarray[$i1]==0) continue;
			$text .= implode($returnarray[$i1],"**") . "\n";
		}
		$file=fopen("exp6/blockedgamenumbers.txt","w");
		fwrite($file,$text);
		fclose($file);
	}
	return $returnarray;
}

function isblocked($blockarray,$step,$gameno,$userpass="")
{
	for($i1=0;$i1<6;$i1++)
	{
		if($blockarray[$i1][0]==$step and $blockarray[$i1][1]==$gameno and $blockarray[$i1][3]!=$userpass) return 1;		
	}
	return 0;	
}


function createdates($weekday,$time,$step,$start=-50,$end=-5,$wnum=0,$wcycle=1) //
{
	// $weekday is a number from 0(sunday) to 6 (saturday)
	// $end and $start are measured in days relatively to now
	// $time in the format "19:30:00"
	// wcycle means it is every $wcylce weeks, with $wnum as offset (with modulo)
	$step=(int)$step;
	if($step<1 or $step>3 or $weekday<0 or $weekday>6) return -1;
	if($end==-5 or $end>200) $end=42;
	if($start<-40) $start=32;
	$now=time();
	for($i1=$start;$i1<=$end;$i1++)
	{
		$time1=$now+86400*$i1;
		$wday2 = (int)date("w",$time1);
		if($wday2!=$weekday) continue;
		$wnum2=(int)(($time1-$wday2*86400)/(7*86400));
		if($wnum2%$wcycle!=$wnum) continue;
		$timetext=date("Y-m-d",$time1) . " $time" ;
		$request="SELECT COUNT(*) FROM dates WHERE step=$step AND date='$timetext'";
		$result=mysql_query($request);
		$r1=mysql_fetch_array($result);
		$r2=$r1[0];
		if($r2>=1) continue;
		$request = "INSERT INTO dates 
		(date, step, status) 
		VALUES 
		('$timetext',$step,0)";
		$result=mysql_query($request) or die("ERRRORRR: " . mysql_error());
	}
}

function tickedit($action, $pid, $gameno = -1,$newval = -1, $reason=-1)
{	//TAKE CARE OF STEP
	//already have connection to DB
	// return value: 1 - ok, 0 - error
	if($action < 3 or $action==4 or $action ==10 or $action==15) return 0;
	if($pid < 1025) return 1;
	chdir("/home/www/bbc/");
	$file = fopen("exp4/ticketlog1.txt","a");
	if($action <18)
	{
		if($newval !=-1 or $reason !=-1) return 0;
		$request = "SELECT ts2, ts3, ts4 FROM table2 WHERE playerid = $pid"; 
		$result = mysql_query($request);
		while($row = mysql_fetch_object($result)){$ts2 = $row->ts2;$ts3 = $row->ts3;$ts4 = $row->ts4;}
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
		if($action==13) $newval=$ts3;
		if($action==14) $newval=$ts4+1;
		if($ts4 >= 10 and $action==14) {$newval=10;$action=15;}
		if($action==16) $newval=$ts4-1;
		if($action==17) $newval=$ts4-1;
		if($newval>10 or $newval<0) return 0;
		
		$request = "UPDATE table2 Set ts2 = $newval WHERE playerid = $pid";
		if($action>8) $request = "UPDATE table2 Set ts3 = $newval WHERE playerid = $pid";
		if($action>13) $request = "UPDATE table2 Set ts4 = $newval WHERE playerid = $pid";
		$result = mysql_query($request);
		if($result===false) return 0;
		$timeint = time();
		$entry = "$timeint#$action#$pid#$newval#$gameno\n";
		fwrite($file,$entry);
		fclose($file);
		return 1;
	}
	if($action > 17 and $action < 21) return 0;
	if($action > 30) return 0;
	if($gameno>0) return 0;
	$request = "SELECT ts2, ts3, ts4 FROM table2 WHERE playerid = $pid"; 
	$result = mysql_query($request);
	while($row = mysql_fetch_object($result)){$ts2 = $row->ts2;$ts3 = $row->ts3;$ts4 = $row->ts4;}
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
	return 1; // return successful
}





?>