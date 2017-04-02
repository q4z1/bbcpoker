<?php
//this .php file is only for functions used by other .php files
/*mysql_connect("localhost","bbcpoker","baguette")or die ("Internal MYSQL-ERROR");
mysql_select_db("bbcpoker") or die ("The Database does not exist");
*/

function bbcday($date,$gameid=0)
{
	if(gettype($date)!="integer") $date=strtotime($date);
	$hour=(int)date("H",$date);
	$t1=$date+3600*(12-$hour);
	$bbcday=(int)floor($t1/86400);
	 //offset maybe?
	
	if($bbcday==15918 or $bbcday==15826)
	{
		if($gameid<1981 or $gameid>2177) return $bbcday;
		//15948 is bbc game with date, id 2178
		$t3=(int)floor((2177-$gameid)/2);
		//print "debug(gameid,t3) = ($gameid,$t3)\n";
		return 15947-$t3;
	}
	return $bbcday;
}

function day2unix($day)
{
	if($day>15947) return $day*86400+8*3600; // 8 hours for security
	if($day>15911) return 1375308001;
	return 1367359201;
}

function glickog($rd,$q)
{
	$pi=3.1415926535;
	return 1.0/sqrt(1.0+3.0*$q*$q*$rd*$rd/$pi*$pi);
}


function glickoe($r1,$r2,$rd2,$q)
{
	return 1.0/(1.0+exp(-glickog($rd2,$q)*($r1-$r2)*$q));
}

function glickomain($ratings,$rds,$rlgs,$thisday,$maxrd=350,$glickoc=16.0,$q=0.0,$minrd=30) // player with index 0 is winner, 9 is place 10
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


function getplayername1($playerid)
{
	$playerid=(int)$playerid;
	$request="SELECT name FROM table2 WHERE playerid=$playerid";
	$result=mysql_query($request);
	while($row=mysql_fetch_object($result)) return $row->name;
}


function calcrating1($from=0,$to=0,$makefile=0,$makedb=0,$returnarray=0,$q=0.0,$glickoc=16,$maxrd=350,$minrd=30,$ptable=0,$user1=0,$user2=0,$startrating=2500)
{
	// this function calculates the rating changes from $from to $to, using glicko. 
	// from and to have the format Y-m-d H:i:s , there should be a file for from, unless from is zero
	// makefile=1: results are stored in file, makedb=!: results go into mysql database
	// q,c,maxrd,minrd are game constants
	// ptable=1 prints games during calculation
	// user1,user2=playerid tracks results of players and returns it
	// @TODO test this shit
	// deal with standard parameters now:
	if($to==0) $to=date("Y-m-d H:i:s"); //now
	if($q<=0.0) $q=log(10)/400.0;
	$pname=array();
	$rating=array();
	$rd=array();
	$lastgame=array();
	if($from != 0)
	{
		$fname1="exp2/rating/r".str_replace(" ","_",$from).".txt";
		$file=fopen($fname1,"r");
		if($file===FALSE) return 0; //error, couldnt read file
		$data1=fread($file,filesize($fname1)); //read the entire file
		$data2=explode("\n",$data1); // make array of rows
		for($i1=0;$i1<len($data2);$i1++)
		{
			$row=explode(" ",$data2[$i1]); // format is   "playerid,rating,rd,lastgame"
			if(count($row)!=4) continue;
			$pid=(int)$row[0];
			$rating[$pid]=round((float)$row[1],2);
			$rd[$pid]=round((float)$row[2],2);
			$lastgame[$pid]=(int)$row[3];
			$pname[$pid]=getplayername1($pid);
		}
		fclose($file);
		$request="SELECT * FROM table1 
WHERE datetime>='$from' and datetime<'$to' 
ORDER BY datetime ASC, id ASC";
	}
	$maxpid=1200;
	if($ptable==1) print "<table border=1>\n";
	$history1=array($startrating);
	$history2=array($startrating);
	if(array_key_exists($user1,$rating)) $history1=array($rating[$user1]);
	if(array_key_exists($user2,$rating)) $history2=array($rating[$user2]);
	$hisi=1;
	if($from==0) $request="SELECT * FROM table1 
WHERE datetime<'$to' 
ORDER BY datetime ASC, id ASC";
	$result=mysql_query($request);
	while($row=mysql_fetch_object($result))
	{
		$vday=bbcday($row->datetime,$row->id);
		$place=array(0,$row->p1,$row->p2,$row->p3,$row->p4,$row->p5,$row->p6,$row->p7,$row->p8,$row->p9,$row->p10);
		$NN = 10; //number of players in the game
 		if($place[10]==1024) $NN=9;
 		if($NN==9 and $place[9]==1024) $NN=8;
		if($NN==8 and $place[8]==1024) $NN=7; //this should never happen...
		if($NN==7 and $place[7]==1024) $NN=6; //this should never happen...
		$lrat=array();
		$lrd=array();
		$llg=array();
		$hisbool=0;
		for($i1=1;$i1<=$NN;$i1++) // global to local
		{
			$pid=(int)$place[$i1];
			if( ! array_key_exists($pid,$rating))
			{
				if($pid>$maxpid) $maxpid=$pid;
				// create new player
				$rating[$pid]=$startrating;
				$rd[$pid]=$maxrd;
				$lastgame[$pid]=0; // last game doesnt matter for new player
				$pname[$pid]=getplayername1($pid);
			}
			$lrat[$i1-1]=$rating[$pid];
			$lrd[$i1-1]=$rd[$pid];
			$llg[$i1-1]=$lastgame[$pid];
			if($user1==$pid) $hisbool=1; // we need to record user1
			if($user2==$pid) $hisbool+=2; // we need to record user2	
		}
		$ret=glickomain($lrat,$lrd,$llg,$vday,$maxrd,$glickoc,$q,$minrd); // apply rating locally
		$nrat=$ret[0];
		$nrd=$ret[1];
		
		if($ptable==1)
		{
			print "<tr><td>".$row->step . "</td>";
			for($i1=0;$i1<10;$i1++)
			{
				if($i1>=$NN)
				{
					print "<td></td>";
					continue;
				}
				$pid=$place[$i1+1];
				print "<td>$pname[$pid] | $nrd[$i1] </td>";
			}
			print "</tr>\n<tr><td>".$row->gameno."</td>";
			for($i1=0;$i1<10;$i1++)
			{
				if($i1>=$NN)
				{
					print "<td></td>";
					continue;
				}
				$pid=$place[$i1+1];
				$dif=sprintf("%.2f",$nrat[$i1]-$rating[$pid]);
				print "<td>$nrat[$i1] ($dif) </td>";
			}
			print "</tr>\n";
		}
		for($i1=0;$i1<$NN;$i1++) // local to global
		{
			$pid=$place[$i1+1];
			$rating[$pid]=$nrat[$i1];
			$rd[$pid]=$nrd[$i1];
			$lastgame[$pid]=$vday;
		}
		if($hisbool!=0)
		{
			$history1[$hisi]=$rating[$user1];
			$history2[$hisi]=$rating[$user2];
			if($history2[$hisi]==0) $history2[$hisi]=$history2[$hisi-1];
			if($history1[$hisi]==0) $history1[$hisi]=$history1[$hisi-1];
			$hisi++;
		}
	}
	
	if($ptable==1) print "</table>";
	
	if($makefile==1)
	{
		$fname2="exp2/rating/r".str_replace(" ","_",$to).".txt";
		$file=fopen($fanem2,"w");
		if($file===FALSE) return 0;
		// create array now
		for($i1=1002;$i1<$maxpid+200;$i1++)
		{
			if(!array_key_exists($i1,$rating)) continue;
			$tt="$i1 $rating[$i1] $rd[$i1] $lastgame[$i1]\n";
			fwrite($file,$tt);
		}
		fclose($file);
	}
	if($makedb==1)
	{
		for($i1=1002;$i1<$maxpid+200;$i1++)
		{
			$d1=(int) $rating[$i1];
			$d2=(int) $rd[$i1];
			$d3=(int) $lastgame[$i1];
			$request="UPDATE table2 Set
rating=$d1,
rd=$d2,
rlg=$d3
WHERE playerid=$i1";
			$result=mysql_query($request);
			if($result===FALSE) die("mysql error: $request");
		}
		
	}
	if($user1!=0 or $user2!=0)
	{
		$mode=3;
		if($user1==0) $mode=2;
		if($user2==0) $mode=1;
		$hismin=min($history1);
		$hismin2=min($history2);
		if($mode==3) $hismin=min($hismin,$hismin2);
		if($mode==2) $hismin=$hismin2;
		$hismax=max($history1);
		$hismax2=max($history2);
		if($mode==3) $hismax=max($hismax,$hismax2);
		if($mode==2) $hismax=$hismax2;
		$img3=imagecreate($hisi*2+40,220);
		$background = imagecolorallocate( $img3,255, 255, 255);
		$bluecolor=imagecolorallocate($img3,0,0,255);
		$redcolor=imagecolorallocate($img3,255,0,0);
		$yold1=floor(210.0+200.0*($hismin-$history1[0])/((float)$hismax-$hismin+1));
		$yold2=floor(210.0+200.0*($hismin-$history2[0])/((float)$hismax-$hismin+1));
		for($i1=1;$i1<$hisi;$i1++)
		{
			$ynew1=floor(210.0+200.0*($hismin-$history1[$i1])/((float)$hismax-$hismin+1));
			$ynew2=floor(210.0+200.0*($hismin-$history2[$i1])/((float)$hismax-$hismin+1));
			if($mode==2 or $mode==3) imageline($img3,$i1*2+5,$yold2,$i1*2+7,$ynew2,$redcolor);
			if($mode==1 or $mode==3) imageline($img3,$i1*2+5,$yold1,$i1*2+7,$ynew1,$bluecolor);
			$yold1=$ynew1;
			$yold2=$ynew2;
		}
		imagestring($img3,1,$hisi*3+10,4,$hismax,$redcolor);
		imagestring($img3,1,$hisi*3+10,206,(string)((int)$hismin),$bluecolor);
		imagepng($img3,"exp6/temppic4.png");
	}
	if($returnarray==1)
	{
		return array($rating,$rd,$lastgame);
	}
	
	return 1; //if succesful
}

function noobratingmain($ratings,$gamec,$probdiff=3000,$startspeed=80,$minspeed=40,$mingames=30,$winbonus=1.17) 
{
    /* 
    this is the core function of the bbc rating. 
    it was programmed by some supernoob :)
    
    $ratings and $gamec are arrays of the same size
    the size is the number of players
    player with index 0 is winner, 9 is place 10
    gamec is the number of games played before this game
    */
    if(count($ratings) !=count($gamec) or count($ratings)>=11) return 0; //error
    $NN=count($ratings); // $NN is the number of players in the game
    $newrating=$ratings; // newrating will be return array
    for($i1=0;$i1<$NN;$i1++)
    {    
        $expect=0.0; // $expect is the expected score for each player
        for($i2=0;$i2<$NN;$i2++)
        {
            if($i1==$i2) continue;
            $diff = (float)($ratings[$i2]-$ratings[$i1]); //compare rating difference with each player
            if($i2==0 or $i1==0) $expect += $winbonus/(1.0 + pow(2.0,$diff/$probdiff)); // add expected score for winners
            if($i2!=0 and $i1!=0) $expect += 1.0/(1.0 + pow(2.0,$diff/$probdiff)); // add expected score for non-winners
        }
        if($gamec[$i1]<$mingames) $speed2 = (float)$startspeed + (float)($minspeed-$startspeed)*($gamec[$i1])/($mingames); 
        if($gamec[$i1]>=$mingames)$speed2=$minspeed; // determine the "speed" according to parameters
        $wonpoints=$NN-$i1-1; //
        if($i1==0) $wonpoints=$winbonus*9.0; // winners get more points
        $newrating[$i1] =$ratings[$i1] + (int) ceil($speed2*($wonpoints - $expect) -0.5); //round the rating points to integer
    }
    return $newrating;
}


function calcrating2($from=0,$to=0,$makefile=0,$makedb=0,$ptable=0,$user1=0,$user2=0,
$probdiff=3000,$startspeed=80,$minspeed=35,$mingames=30,$winbonus=1.17,$startrating=5000)
{
	// this function calculates the rating changes from $from to $to, using noobrating(c). 
	// from and to have the format Y-m-d H:i:s , there should be a file for from, unless from is zero
	// makefile=1: results are stored in file, makedb=!: results go into mysql database
	// $probdiff. $startspeed, $minspeed,$mingames
	// ptable=1 prints games during calculation
	// user1,user2=playerid tracks results of players and creates a graph *.png
	// @TODO test this shit
	// deal with standard parameters now:
	if($to==0) $to=date("Y-m-d H:i:s"); //now
	$pname=array();
	$rating=array();
	$gamec=array();
	if($from != 0)
	{
		$fname1="exp2/rating/r".str_replace(" ","_",$from).".txt";
		$file=fopen($fname1,"r");
		if($file===FALSE) return 0; //error, couldnt read file
		$data1=fread($file,filesize($fname1)); //read the entire file
		$data2=explode("\n",$data1); // make array of rows
		for($i1=0;$i1<count($data2);$i1++)
		{
			$row=explode(" ",$data2[$i1]); // format is   "playerid,rating,games_played"
			if(count($row)!=3) continue; // ignore empty rows
			$pid=(int)$row[0];
			$rating[$pid]=(int)$row[1];
			$gamec[$pid]=(int)$row[2];
			$pname[$pid]=getplayername1($pid);
		}
		fclose($file);
		$request="SELECT * FROM table1 
WHERE datetime>='$from' and datetime<'$to' 
ORDER BY datetime ASC, id ASC";
	}
	$maxpid=1200;
	if($ptable==1) print "<table border=1>
<tr><td>game</td><th>Place 1</th><th>Place 2</th><th>Place 3</th><th>Place 4</th>
<th>Place 5</th><th>Place 6</th><th>Place 7</th><th>Place 8</th>
<th>Place 9</th><th>Place 10</th></tr>\n";
	$history1=array($startrating);
	$history2=array($startrating);
	if(array_key_exists($user1,$rating)) $history1=array($rating[$user1]);
	if(array_key_exists($user2,$rating)) $history2=array($rating[$user2]);
	$hisi=1;
	if($from==0) $request="SELECT * FROM table1 
WHERE datetime<'$to' 
ORDER BY datetime ASC, id ASC";
	$result=mysql_query($request);
	while($row=mysql_fetch_object($result))
	{
		$place=array(0,$row->p1,$row->p2,$row->p3,$row->p4,$row->p5,$row->p6,$row->p7,$row->p8,$row->p9,$row->p10);
		$NN = 10; //number of players in the game
 		if($place[10]==1024) $NN=9;
 		if($NN==9 and $place[9]==1024) $NN=8;
		if($NN==8 and $place[8]==1024) $NN=7; //this should never happen in reality
		if($NN==7 and $place[7]==1024) $NN=6; //this should never happen...
		$lrat=array(); // local rating
		$lgc=array(); // local game count
		$hisbool=0; // user1 or user2 is in the game
		for($i1=1;$i1<=$NN;$i1++) // global to local
		{
			$pid=(int)$place[$i1];
			if( ! array_key_exists($pid,$rating))
			{
				if($pid>$maxpid) $maxpid=$pid;
				// create new player
				$rating[$pid]=$startrating;
				$gamec[$pid]=0; // starts from the beginning
				$pname[$pid]=getplayername1($pid);
			}
			$lrat[$i1-1]=$rating[$pid];
			$lgc[$i1-1]=$gamec[$pid];
			if($user1==$pid) $hisbool=1; // we need to record user1
			if($user2==$pid) $hisbool+=2; // we need to record user2	
		}
		//function noobratingmain($ratings,$gamec,$probdiff=3000,$startspeed=80,$minspeed=40,$mingames=30,$winbonus=1.15) 
		$nrat=noobratingmain($lrat,$lgc,$probdiff,$startspeed,$minspeed,$mingames,$winbonus);
		// TODO increase games count after game
		for($i1=0;$i1<$NN;$i1++)
		{
			$pid=$place[$i1+1];
			$gamec[$pid] += 1;
		}
		if($ptable==1)
		{
			print "<tr><td>".$row->step . "</td>";
			for($i1=0;$i1<10;$i1++)
			{
				if($i1>=$NN)
				{
					print "<td></td>";
					continue;
				}
				$pid=$place[$i1+1];
				print "<td>$pname[$pid] ($gamec[$pid]) </td>";
			}
			print "</tr>\n<tr><th>".$row->gameno."</th>";
			for($i1=0;$i1<10;$i1++)
			{
				if($i1>=$NN)
				{
					print "<td></td>";
					continue;
				}
				$pid=$place[$i1+1];
				$dif=$nrat[$i1]-$rating[$pid];
				print "<td>$nrat[$i1] ($dif) </td>";
			}
			print "</tr><tr><td colspan=11></td></tr>\n";
		}
		for($i1=0;$i1<$NN;$i1++) // local to global
		{
			$pid=$place[$i1+1];
			$rating[$pid]=$nrat[$i1];
		}
		if($hisbool!=0)
		{
			$history1[$hisi]=$rating[$user1];
			$history2[$hisi]=$rating[$user2];
			if($history2[$hisi]==0) $history2[$hisi]=$history2[$hisi-1];
			if($history1[$hisi]==0) $history1[$hisi]=$history1[$hisi-1];
			$hisi++;
		}
	}
	if($ptable==1) print "</table>";
	if($makefile==1)
	{
		$fname2="exp2/rating/r".str_replace(" ","_",$to).".txt";
		
		$file=fopen($fname2,"w");
		if($file===FALSE) return 0;
		// create array now
		for($i1=1002;$i1<$maxpid+200;$i1++)
		{
			if(!array_key_exists($i1,$rating)) continue;
			$tt="$i1 $rating[$i1] $gamec[$i1]\n";
			fwrite($file,$tt);
		}
		fclose($file);
	}
	if($makedb==1)
	{
		for($i1=1002;$i1<$maxpid+200;$i1++)
		{
			if(!array_key_exists($i1,$rating)) continue;
			$d1=(int) $rating[$i1];
			$request="UPDATE table2 Set
rating=$d1
WHERE playerid=$i1";
			$result=mysql_query($request);
			if($result===FALSE) die("mysql error: $request");
		}
	}
	if($user1!=0 or $user2!=0)
	{
		$mode=3;
		if($user1==0) $mode=2;
		if($user2==0) $mode=1;
		$hismin=min($history1);
		$hismin2=min($history2);
		if($mode==3) $hismin=min($hismin,$hismin2);
		if($mode==2) $hismin=$hismin2;
		$hismax=max($history1);
		$hismax2=max($history2);
		if($mode==3) $hismax=max($hismax,$hismax2);
		if($mode==2) $hismax=$hismax2;
		$img3=imagecreate($hisi*3+40,220);
		$background = imagecolorallocate( $img3,255, 255, 255);
		$bluecolor=imagecolorallocate($img3,0,0,255);
		$redcolor=imagecolorallocate($img3,255,0,0);
		$yold1=floor(210.0+200.0*($hismin-$history1[0])/((float)$hismax-$hismin+1));
		$yold2=floor(210.0+200.0*($hismin-$history2[0])/((float)$hismax-$hismin+1));
		for($i1=1;$i1<$hisi;$i1++)
		{
			$ynew1=floor(210.0+200.0*($hismin-$history1[$i1])/((float)$hismax-$hismin+1));
			$ynew2=floor(210.0+200.0*($hismin-$history2[$i1])/((float)$hismax-$hismin+1));
			if($mode==2 or $mode==3) imageline($img3,$i1*3+5,$yold2,$i1*3+7,$ynew2,$redcolor);
			if($mode==1 or $mode==3) imageline($img3,$i1*3+5,$yold1,$i1*3+7,$ynew1,$bluecolor);
			$yold1=$ynew1;
			$yold2=$ynew2;
		}
		imagestring($img3,1,$hisi*3+10,4,$hismax,$redcolor);
		imagestring($img3,1,$hisi*3+10,206,(string)((int)$hismin),$bluecolor);
		imagepng($img3,"exp6/temppic5.png");
	}
	return 1; // if succesful
}


function dectohex($number)
{
  return sprintf("%x",$number%16);
}

function make_seed()
{
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000);
}
 


function makesalt($length=16)
{
  $now=time();
  srand(make_seed()+rand());
  $s1=$now%65536;
  $ret="";
  for($i=0;$i<$length;$i++)
  {
    $s1=($s1*$now*rand(1,19)+rand(1,64))%65536;
    $n=($s1+rand(1,128))%16;
    $ret .= dectohex($n);
  }
  return $ret;
}




?>