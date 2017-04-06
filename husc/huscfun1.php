<?php

/* NOTE: this file only contains php functions used */


function getseason() // looks at $_GET['s'], otherwise file
{
  $season=(int)$_GET['s'];
  if($season<=0 or !file_exists("husc/s$season/status.txt")) $season=(int)file_get_contents("husc/relevantseason.txt");
  return $season;
}

function getphase($season) // returns number of phase. if error or no phase, -1
{
  if(!file_exists("husc/s$season/status.txt")) return -1;
  $t1=file_get_contents("husc/s$season/status.txt");
  if(substr($t1,0,5)=="phase")
  {
    return (int)substr($t1,5);
  }
  return -1;
}

function getround($season) // returns number of round. if error or no round, -1
{
  if(!file_exists("husc/s$season/status.txt")) return -1;
  $t1=file_get_contents("husc/s$season/status.txt");
  if(substr($t1,0,5)=="round")
  {
    return (int)substr($t1,5);
  }
  return -1;
}

function writelog($season,$actionid,$n2=0,$n3=0,$n4=0,$n5=0) // writes stuff into the public logfile with timestampt
{
  $file=fopen("husc/s$season/actionlog.txt","a");
  $d=date("Y-m-d H:i:s");
  fwrite($file,"$actionid $d $n2 $n3 $n4 $n5\n");
  fclose($file);
}


function setphase($season,$phase) // sets phase in status.txt and writes it to log, does not check if allowed to do so
{
  $file=fopen("husc/s$season/status.txt","w");
  fwrite($file,"phase $phase");
  fclose($file);
  if($phase==1)
  {
    $hash1=md5(file_get_contents("husc/s$season/schedule.txt"));
    $hash2=md5(file_get_contents("husc/s$season/codeofconduct.html"));
    $hash3=substr($hash1,0,8);
    $hash4=substr($hash2,0,8);
    writelog($season,101,$hash3,$hash4,0,0);
  }
  if($phase==2) writelog($season,102,0,0,0,0);
  if($phase==3) writelog($season,103,0,0,0,0);
  if($phase==4) writelog($season,104,0,0,0,0);
  if($phase==5) writelog($season,105,getstartlives($season),0,0,0);
  return;
}

function setround($season,$round) // writes status.txt, calls writelog()
{
  $oldround=getround($season);
  $file=fopen("husc/s$season/status.txt","w");
  fwrite($file,"round $round");
  fclose($file);
  writelog($season,109,$oldround,$round);
  return;
}

function weekday1($string) // returns weekday code 0-6 (sunday-saturday), -1 if error
{
  if($string=="monday") return 1;
  if($string=="tuesday") return 2;
  if($string=="wednesday") return 3;
  if($string=="thursday") return 4;
  if($string=="friday") return 5;
  if($string=="saturday") return 6;
  if($string=="sunday") return 0;
  return -1;
}

function istime($string) // checks for format HH:mm
{
  if(strlen($string)!=5) return False;
  $zerofive=array("0","1","2","3","4","5");
  $zeronine=array("0","1","2","3","4","5","6","7","8","9");
  if(!in_array(substr($string,0,1),$zerofive)) return False;
  if(!in_array(substr($string,3,1),$zerofive)) return False;
  if(!in_array(substr($string,1,1),$zeronine)) return False;
  if(!in_array(substr($string,4,1),$zeronine)) return False;
  if(substr($string,2,1)!=":") return False;
  return True;
}


function isschedgood($season) // checks if schedule.txt has the correct format. returns False or True
{
  if(!file_exists("husc/s$season/schedule.txt")) return false;
  $data=file_get_contents("husc/s$season/schedule.txt");
  $data1=explode("\n",$data);
  $c1=count($data1);
  // search for "weekly":
  $weekly=-1;
  for($i1=0;$i1<$c1;$i1++)
  {
    if(substr($data1[$i1],0,7)=="weekly ")
    {
      $data2=explode(" ",$data1[$i1],3);
      if(count($data2)!=3 or $data2[2]!="rounds") return false;
      $weekly=(int)$data2[1];
      break;
    }
  }
  if($weekly <=0) return False;
  for($round=1;$round<=$weekly;$round++)
  {
    // search for number of slots
    $slots =-1;
    for($i1=0;$i1<$c1;$i1++)
    {
      $data2=explode(" ",$data1[$i1]);
      if(count($data2)!=4) continue;
      if($data2[0]!="round") continue;
      if($data2[1]!="$round") continue;
      if($data2[2]!="slots") continue;
	  $slots=(int)$data2[3];
	  break;
    }
    if($slots<=0) return false;
    // check if start and end are there
    $start=-1;
    $end=-1;
    for($i1=0;$i1<$c1;$i1++)
    {
      $data2=explode(" ",$data1[$i1]);
      if(count($data2)!=5) continue;
      if($data2[0]!="round") continue;
      if($data2[1]!="$round") continue;
      if($data2[2]!="start" and $data2[2]!="end") continue;
	  if(weekday1($data2[3])==-1) return False;
      if(!istime($data2[4])) return False;
	  if($data2[2]=="start") $start=1;
	  if($data2[2]=="end") $end=1;
	  if($start+$end==2) break;
    }
    if($start==-1 or $end==-1) return False;
    
    // check for each slot if data is there
    for($slot=1;$slot<=$slots;$slot++)
    {
      // check if there is data for this slot
      $bb=-1;
      for($i1=0;$i1<$c1;$i1++)
      {
        $data2=explode(" ",$data1[$i1]);
        if(count($data2)!=6) continue;
        if($data2[0]!="round") continue;
        if($data2[1]!="$round") continue;
        if($data2[2]!="slot") continue;
	    if($data2[3]!="$slot") continue;
	    $bb=1;
        if(weekday1($data2[4])==-1) return False;
        if(!istime($data2[5])) return False;
        break;
      }
      if($bb==-1) return False;
    }  
  } 
  return true;
}

function sched2html($season) // returns a html-string, that is a human readable version of the schedule.txt
{
  $ret="<p>This is the planned Schedule <small>(all times are in the BBC time zone)</small></p>\n";
  $eret= "<p><b>Error:</b> Scheduling file could not be read or contains errors</p>";
  $data=file_get_contents("husc/s$season/schedule.txt");
  $data1=explode("\n",$data);
  $c1=count($data1);
  $weekly=-1;
  for($i1=0;$i1<$c1;$i1++)
  {
    if(substr($data1[$i1],0,7)=="weekly ")
    {
      $data2=explode(" ",$data1[$i1],3);
      if(count($data2)!=3 or $data2[2]!="rounds") return $eret;
      $weekly=(int)$data2[1];
      break;
    }
  }
  if($weekly <=0) return $eret;
  for($round=1;$round<=$weekly;$round++)
  {
    $roundchar=chr(64+$round);
    
    // search for number of slots
    $slots =-1;
    for($i1=0;$i1<$c1;$i1++)
    {
      $data2=explode(" ",$data1[$i1]);
      if(count($data2)!=4) continue;
      if($data2[0]!="round") continue;
      if($data2[1]!="$round") continue;
      if($data2[2]!="slots") continue;
	  $slots=(int)$data2[3];
	  break;
    }
    if($slots<=0) return $eret;
    $mingame=(int)($slots/2+1);
    $ret .= "<p><b>Round $roundchar</b>: You should be able to play at least at <b>$mingame</b> out of the following $slots dates:</p>\n";
    // check if start and end are there
    $start=-1;
    $end=-1;
    for($i1=0;$i1<$c1;$i1++)
    {
      $data2=explode(" ",$data1[$i1]);
      if(count($data2)!=5) continue;
      if($data2[0]!="round") continue;
      if($data2[1]!="$round") continue;
      if($data2[2]!="start" and $data2[2]!="end") continue;
	  if(weekday1($data2[3])==-1) return $eret;
      if(!istime($data2[4])) return $eret;
	  if($data2[2]=="start") $start=1;
	  if($data2[2]=="end") $end=1;
	  if($start+$end==2) break;
    }
    if($start==-1 or $end==-1) return $eret;
    $ret .= "<ul>\n";
    // check for each slot if data is there
    for($slot=1;$slot<=$slots;$slot++)
    {
      // check if there is data for this slot
      $bb=-1;
      for($i1=0;$i1<$c1;$i1++)
      {
        $data2=explode(" ",$data1[$i1]);
        if(count($data2)!=6) continue;
        if($data2[0]!="round") continue;
        if($data2[1]!="$round") continue;
        if($data2[2]!="slot") continue;
	    if($data2[3]!="$slot") continue;
	    $bb=1;
        if(weekday1($data2[4])==-1) return $eret;
        if(!istime($data2[5])) return $eret;
        $ret .="<li>$data2[4] $data2[5]</li>\n";
        break;
      }
      if($bb==-1) return $eret;
    }  
    $ret .= "</ul>\n";
  } 
  return $ret;
}

function getplayerid($name) // returns -1 if not found
{
  $name2=mysql_real_escape_string($name);
  $request="SELECT playerid FROM table2 WHERE name='$name2'";
  $result=mysql_query($request);
  $c=0;
  while($row=mysql_fetch_object($result))
  {
    $c++;
    return $row->playerid;
  }
  return -1;
}

function getregistrationstatus($season,$pid) // registrations.txt
{
  $data1=file_get_contents("husc/s$season/registrations.txt");
  $data2=explode("\n",$data1);
  $c2=count($data2);
  for($i1=0;$i1<$c2;$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=4) continue;
    if($data3[2]!=$pid) continue;
    return (int)$data3[3];
  }
  return -1;
}
function setregistrationstatus($season,$pid,$newstatus) // registrations.txt, writelog 
{
  $data1=file_get_contents("husc/s$season/registrations.txt");
  $data2=explode("\n",$data1);
  $newstatus=(int)$newstatus;
  $c2=count($data2);
  for($i1=0;$i1<$c2;$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=4) continue;
    if($data3[2]!=$pid) continue;
    $oldstatus=(int)$data3[3];
    if($oldstatus==$newstatus) return;
    $data3[3]="$newstatus";
    $data2[$i1]=implode(" ",$data3);
  }
  $data1=implode("\n",$data2);
  if($data1!="") $data1.="\n";
  writelog($season,202,$pid,$newstatus,$oldstatus);
  file_put_contents("husc/s$season/registrations.txt",$data1);
  return;
}

function isregistered($season,$pid) // checks if there is already a registration
{
  $regstatus=getregistrationstatus($season,$pid);
  if($regstatus>=2 and $regstatus<=7) return true;
  return false;
}


function getbbcgames($pid) // gets total number of bbc games given a playerid
{
  $pid=(int)$pid;
  $request="SELECT alltimegames FROM table2 WHERE playerid=$pid LIMIT 1";
  $result=mysql_query($request);
  if(!$result) return -1;
  return (int)mysql_fetch_array($result)[0];
}

function getplayername($pid) 
{
  $pid=(int)$pid;
  $request="SELECT name FROM table2 WHERE playerid=$pid LIMIT 1";
  $result=mysql_query($request);
  if($row=mysql_fetch_object($result)) return $row->name;
  return FALSE;
}

function protectedseasonreset($season)
{
  return false; // disable this function
  $season=(int)$season;
  
  $n1=pow(3,$season);
  $n2=date("m-d-H");
  $n3=date("Y-m-d");
  
  if($n3 != "2015-03-12") return false;
  $code1="abc".$n1."def".$season."ghi".$n2;
  //print "check,$code1,,,";
  if($_GET['srcode'] != $code1) return false;
  
  // create folder
  if(!is_dir ("husc/s$season")) mkdir("husc/s$season");
  // create empty files
  setphase($season,0);
  unlink("husc/s$season/actionlog.txt");
  unlink("husc/s$season/participants.txt");
  file_put_contents("husc/s$season/registrations.txt","");
  // remove more files
  return true;
}

function startnewseason($season) // folder should not exist, create empty files & co.
{
  $season=(int)$season;
  if($season<0) return -1;
  if(is_dir("husc/s$season")) return -2;
  mkdir("husc/s$season");
  file_put_contents("husc/s$season/actionlog.txt","");
  file_put_contents("husc/s$season/registrations.txt","");
  file_put_contents("husc/s$season/warning.html","");
  setphase($season,0);
  return 3; //success
}


function countreg4($season,$rs1,$rs2) // counts how often occurs registration status $rs1 or $rs2
{
  $data1=file_get_contents("husc/s$season/registrations.txt");
  $data2=explode("\n",$data1);
  $counter=0;
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=4) continue;
    $s= (int)$data3[3];
    if($s==$rs1 or $s==$rs2) $counter++;
  }
  return $counter;
}

function countdeclinedregistrations($season) //
{
  return countreg4($season,2,3);
}

function countundecidedregistrations($season) //
{
  return countreg4($season,4,5);
}

function countacceptedregistrations($season) //
{
  return countreg4($season,6,7);
}

function reg2participants($season) // creates participants.txt from accepted registrations + seeding
// returns true on success, false on error
// maybe better test that function
{
  $data1=file_get_contents("husc/s$season/registrations.txt");
  $data2=explode("\n",$data1);
  $pids=array();
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=4) continue;
    $s= (int)$data3[3];
    if($s==6 or $s==7)
    {
      $pids[]=(int)$data3[2]; //append to participants list
    }
  }
  
  // $pids contains all participants now, lets get the rating
  $ratings=array();
  $year=(int)date("Y");
  $month=(int)date("m");
  $c1=count($pids);
  for($i1=0;$i1<$c1;$i1++)
  {
    $ratings[$pids[$i1]]=getmonthlyrating($pids[$i1],$year,$month);
    if($ratings==0) return false;
  }
  // now do the sorting (insertion sort)
  for($i1=0;$i1<$c1;$i1++)
  {
    $t1=$pids[$i1];
    $t2=$ratings[$t1];
    for($i2=$i1;0<$i2 && $ratings[$pids[$i2-1]]<$t2;$i2--)
    {
      $pids[$i2]=$pids[$i2-1];
    }
    $pids[$i2]=$t1;
    
  }// hopefully this will work
  $parfile="";
  for($i1=0;$i1<$c1;$i1++)
  {
    $pid=$pids[$i1];
    $seed=$c1-$i1;
    $r=$ratings[$pid];
    $parfile.="$pid $r $seed\n";
  }
  file_put_contents("husc/s$season/participants.txt",$parfile);
  return true;
}

function getmonthlyrating($pid,$year,$month) // jan=1,dec=12
{
  $month=(int)$month;
  $year=(int)$year;
  if($year<2012 or $year>2099) $year=0;
  if($month<1 or $month>12) $year=0;
  if($month<10) $month="0$month";
  else $month="$month";
  $filename="exp2/rating/r$year-$month-01_00:00:00.txt";
  if(!file_exists($filename)) return 0;
  $data1=file_get_contents($filename);
  $data2=explode("\n",$data1);
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=3) continue;
    if("$pid"!=$data3[0]) continue;
    return (int)$data3[1];
  }
  return 0;
}

function getschedrounds($season) // schedule.txt
{
  $data=file_get_contents("husc/s$season/schedule.txt");
  $data1=explode("\n",$data);
  $c1=count($data1);
  for($i1=0;$i1<$c1;$i1++)
  {
    if(substr($data1[$i1],0,7)=="weekly ")
    {
      $data2=explode(" ",$data1[$i1],3);
      if(count($data2)!=3 or $data2[2]!="rounds") return 0;
      return (int)$data2[1];
    }
  }
  return 0;
}

function gettimeslots($season,$round) // schedule.txt
{
  $data=file_get_contents("husc/s$season/schedule.txt");
  $data1=explode("\n",$data);
  $c1=count($data1);
  for($i1=0;$i1<$c1;$i1++)
  {
  	$data2=explode(" ",$data1[$i1]);
  	if(count($data2)!=4) continue;
  	if($data2[0]!="round") continue;
  	if($data2[1]!="$round") continue;
  	if($data2[2]!="slots") continue;
    return (int)$data2[3];
  }
  return 0;
}

function getparticipantsarray($season) // participants.txt
{
  if(!file_exists("husc/s$season/participants.txt")) return array();
  $data1=file_get_contents("husc/s$season/participants.txt");
  $data2=explode("\n",$data1);
  $ret=array();
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=3) continue;
    $ret[]=(int)$data3[0];
  }
  return $ret;
}


function setdefaultschedpref($season) // create and write the default schedprefxxxx.txt (everywhere 2) for every from participants.txt
{
  $pids=getparticipantsarray($season);
  $rounds=getschedrounds($season);
  $t="";
  for($i1=1;$i1<$rounds;$i1++)
  {
    $slots=gettimeslots($season,$i1);
    for($i2=1;$i2<$slots;$i2++)
    {
      $t.="$i1 $i2 2\n";
    }
    $t.="$i1 $slots 2\n";
  }
  $slots=gettimeslots($season,$i1);
  for($i2=1;$i2<$slots;$i2++)
  {
    $t.="$rounds $i2 2\n";
  }
  $t.="$rounds $slots 2";
  
  for($i1=0;$i1<count($pids);$i1++)
  {
    $pid=$pids[$i1];
    $file="husc/s$season/schedpref$pid.txt";
    file_put_contents($file,$t);
  }
  return;
}

function isparticipant($season,$pid)
{
  return in_array($pid,getparticipantsarray($season));
}

function getschedprefarray($season,$pid,$round) // all time slots for that round. index=slot-1
{
  $slots=gettimeslots($season,$round);
  $ret=array_fill(0,$slots,0);
  $fname="husc/s$season/schedpref$pid.txt";
  $data1=file_get_contents($fname);
  $data2=explode("\n",$data1);
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=3) continue;
    if($data3[0]!=$round) continue;
    $slot=(int)$data3[1];
    $ret[$slot-1]=(int)$data3[2];
  }
  return $ret;
}

function getdatesarray($season,$round) // array of strings like (string)"monday 18:50".  index=slot-1
{
  $slots=gettimeslots($season,$round);
  $ret=array_fill(0,$slots,"");
  $data1=file_get_contents("husc/s$season/schedule.txt");
  $data2=explode("\n",$data1);
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=6) continue;
    if($data3[0]!="round" or $data3[1]!=$round) continue;
    if($data3[2]!="slot") continue;
    $slot=(int)$data3[3];
    $ret[$slot-1]=$data3[4]." ".$data3[5];
  }
  return $ret;
}

function setschedpref($season,$playerid,$round,$slot,$pref) // $pref is preference (1,2,3). 
           // this function calls writelog($season,301,$playerid,$round,$slot,$pref)
{
  $data1=file_get_contents("husc/s$season/schedpref$playerid.txt");
  $data2=explode("\n",$data1);
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=3) continue;
    
    if($data3[0]!=$round or $data3[1]!=$slot) continue;
    $data2[$i1]="$round $slot $pref";
    break;
  }
  $data1=implode("\n",$data2);
  file_put_contents("husc/s$season/schedpref$playerid.txt",$data1);
  writelog($season,301,$playerid,$round,$slot,$pref);
  return;
}

function setstartlives($season,$lives) // write lives.txt
{
  file_put_contents("husc/s$season/lives.txt",$lives);	
  return;
}
function getstartlives($season) // returns number of lives based on lives.txt, -1 if error
{
  if(!file_exists("husc/s$season/lives.txt")) return -1;
  return (int)file_get_contents("husc/s$season/lives.txt");
}

function ranking($season,$round) // writes the rankingyy.txt file (yy = $round with leading zeros), 
           // based on all the resultsyy.txt up to yy=$round-1. $round can be 0
{
  $round=(int)$round;
  if($round<0) return -1;
  if($round==0)
  {
    $fname="husc/s$season/participants.txt";
    $pidindex=0;
    $seedindex=2;
  }
  if($round > 0)
  {
    $rr=twodigit($round-1);
    $fname="husc/s$season/ranking$rr.txt";
    $pidindex=1;
    $seedindex=14; 
  }
  if(!file_exists($fname)) return -1;
  $data1=file_get_contents($fname);
  $data2=explode("\n",$data1);
  $c1=count($data2);
  $pids=array();
  $crit=array();
  $critcount=13;
  for($i1=0;$i1<$c1;$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=$seedindex+1) continue;
    $pid=(int)$data3[$pidindex];
    $seed=(int)$data3[$seedindex];
    $crit[$pid]=array(1,2,3,4,0,0,0,0,0,0,0,0,$seed); // array of size 13 for criteria
    $pids[]=$pid;
  }
  
  
  $c1=count($pids);
  
  $gamestext="";
  for($i1=1;$i1<=$round;$i1++)
  {
    $t="$i1";
    if($i1<10) $t="0$i1";
    $gamestext.=file_get_contents("husc/s$season/results$t.txt")."\n";
  }
  $data1=explode("\n",$gamestext);
  $poplist=array();
  $aoplist=array();  
  $startlives=getstartlives($season);

  $pw=array(); //played win
  $pl=array(); //played loss
  $dw=array(); //default win
  $dl=array(); //default loss
  for($i1=0;$i1<$c1;$i1++)
  {
    $poplist[$pids[$i1]]=array();
    $aoplist[$pids[$i1]]=array();
    $pw[$pids[$i1]]=0;
    $pl[$pids[$i1]]=0;
    $dw[$pids[$i1]]=0;
    $dl[$pids[$i1]]=0;
    
  }
  for($i1=0;$i1<count($data1);$i1++)
  {
    $data2=explode(" ",$data1[$i1]);
    if(count($data2)!=5) continue;
    $pid1=(int)$data2[1];
    $pid2=(int)$data2[2];
    // maybe check if pid1,pid2 are participants
    $res1=$data2[3];
    $res2=$data2[4];
    $t="$res1"."$res2";
    if(!($t==="01" or $t==="10" or $t=="+-" or $t=="-+" or $t=="--")) return -1;
    if($t==="01" or $t==="10") 
    {  
      $poplist[$pid1][]=$pid2;
      $poplist[$pid2][]=$pid1;
    }
    $aoplist[$pid1][]=$pid2;
    $aoplist[$pid2][]=$pid1;
    
    if($res1==="0") $pl[$pid1]++;
    if($res1==="1") $pw[$pid1]++;
    if($res1=="+") $dw[$pid1]++;
    if($res1=="-") $dl[$pid1]++;
    if($res2==="0") $pl[$pid2]++;
    if($res2==="1") $pw[$pid2]++;
    if($res2=="+") $dw[$pid2]++;
    if($res2=="-") $dl[$pid2]++;
  }
  for($i1=0;$i1<$c1;$i1++)
  {
    $pid=$pids[$i1];
    $crit[$pid][0]=$startlives-$pl[$pid]-$dl[$pid];
    $crit[$pid][1]=$pw[$pid]+$dw[$pid];
    $crit[$pid][2]=$pw[$pid];
    $crit[$pid][3]=$dl[$pid];
    $c2=count($poplist[$pid]);
    for($i2=0;$i2<$c2;$i2++)
    {
      $oppid=$poplist[$pid][$i2];
      $crit[$pid][4]+=$pw[$oppid]+$dw[$oppid];
      $crit[$pid][5]+=$startlives-$pl[$oppid]-$dl[$oppid];
      $crit[$pid][8]+=$pw[$oppid];
      $crit[$pid][9]+=$dl[$oppid];
    }
    $c3=count($aoplist[$pid]);
    for($i2=0;$i2<$c3;$i2++)
    {
      $oppid=$aoplist[$pid][$i2];
      $crit[$pid][6]+=$pw[$oppid]+$dw[$oppid];
      $crit[$pid][7]+=$startlives-$pl[$oppid]-$dl[$oppid];
      $crit[$pid][10]+=$pw[$oppid];
      $crit[$pid][11]+=$dl[$oppid];
    }
  }
  // insertion sort in $pid according to $crit
  for($i1=0;$i1<$c1;$i1++)
  {
    $t1=$pids[$i1];
    for($i2=$i1;0<$i2;$i2--)
    {
      // compare: if $pids[$i2-1] >'> $t1: break 
      $b=0;
      for($i3=0;$i3<$critcount;$i3++)
      {
        $t2=$crit[$t1][$i3];
        $t3=$crit[$pids[$i2-1]][$i3];
        if($t3==$t2) continue;
        if($t3>$t2) $b=-1;
        else $b=1;
        break;
      }
      if($b==-1) break;
      if($b==0) return -1;
      $pids[$i2]=$pids[$i2-1];
    }
    $pids[$i2]=$t1;
  } // i hope this works
  
  
  
  $t="";
  for($i1=0;$i1<$c1;$i1++)
  {
    $pos=$i1+1;
    $pid=$pids[$i1];
    $t .= "$pos $pid";
    for($i2=0;$i2<$critcount;$i2++)
    {
      $t2=$crit[$pid][$i2];
      $t.=" $t2";
    }
    $t.="\n";
  }
  $rr=$round;
  if($rr<10) $rr="0$rr";
  else $rr="$rr";
  $fname="husc/s$season/ranking$rr.txt";
  file_put_contents($fname,$t);
  return 2; //good
}


function schedule1($season,$player1,$player2,$round) // returns weekday and time. $playerx is given by id
           // return could look like (string)"monday 18:50"
{
  $fn1="husc/s$season/schedpref$player1.txt";
  $fn2="husc/s$season/schedpref$player2.txt";
  if(!file_exists($fn1) or !file_exists($fn2)) return -1;
  $pref1=getschedprefarray($season,$player1,$round);
  $pref2=getschedprefarray($season,$player2,$round);
  $d=getdatesarray($season,$round);
  if(count($pref1)!=count($pref2) or count($d)!=count($pref1)) return -1;
  $minval=9;
  $minslot=-2;
  for($i1=0;$i1<count($pref1);$i1++)
  {
    $pp1=(int)$pref1[$i1];
    $pp2=(int)$pref2[$i1];
    if($pp1 <1 or $pp2<1 or $pp1 > 3 or $pp2>3) return -1;
    if($pp1==3) $pp1=4;
    if($pp2==3) $pp2=4;
    if($pp1+$pp2<$minval)
    {
      $minval=$pp1+$pp2;
      $minslot=$i1+1;
    }
  }
  
  if($minslot==-2) return -1;
  return $d[$minslot-1];
}

function twodigit($i)
{
  $j=(int)$i;
  if($j>=0 and $j<10) return "0$j";
  return "$j";
}


function getschedroundstart($season,$round) // returns (string)"monday 14:00"
{
  $data1=file_get_contents("husc/s$season/schedule.txt");
  $data2=explode("\n",$data1);
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=5) continue;
    if($data3[0]!="round" or $data3[1]!=$round) continue;
    if($data3[2]!="start") continue;   
    return "$data3[3]"." "."$data3[4]";
  }
  return -1;
}

function getschedroundend($season,$round) // returns (string)"monday 14:00"
{
  $data1=file_get_contents("husc/s$season/schedule.txt");
  $data2=explode("\n",$data1);
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=5) continue;
    if($data3[0]!="round" or $data3[1]!=$round) continue;
    if($data3[2]!="end") continue;   
    return "$data3[3]"." "."$data3[4]";
  }
  return -1;
}





function pairing($season,$round) // writes the planyy.txt file (yy = $round with leading zeros) , returns -1 fail, 2=good
{
  
  $lives=array();
  $pids=array();
  $position=array();
  $agames=array();
  $pgames=array();
  $poplist=array();
  $aoplist=array();
  $aval=array(); // 1=avaliable, 2=not avaliable
  $startlives=getstartlives($season);
  // get alive players from ranking, load lives and position
  $rr=twodigit($round-1);
  $fname="husc/s$season/ranking$rr.txt";
  $data1=file_get_contents($fname);
  $data2=explode("\n",$data1);
  $c1=count($data2);
  for($i1=0;$i1<$c1;$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=15) continue;
    $ll=(int)$data3[2];
    $pos=(int)$data3[0];
    $pid=(int)$data3[1];
    if($ll<=0) continue;
    $pids[]=$pid;
    $position[$pid]=$pos;
    $lives[$pid]=$ll;
    $aval[$pid]=1;
    $agames[$pid]=$startlives-$ll+(int)$data3[3];
    $pgames[$pid]=$startlives-$ll+(int)$data3[4]-(int)$data3[5];
    $poplist[$pid]=array();
    $aoplist[$pid]=array();
  }
  $alive=count($pids);
  // check if they are sorted according to position
  for($i1=0;$i1<$alive;$i1++)
  {
    if($position[$pids[$i1]]!=$i1+1) return -1;
  }
  $avalcount=$alive;
  
  // if odd, choose bye player according to games, ranking
  if($alive%2==1)
  {
    $mpid=$pids[0];
    for($i1=1;$i1<$alive;$i1++)
    {
      $pid=$pids[$i1];
      if($agames[$pid]<$agames[$mpid]) continue;
      if($agames[$pid]==$agames[$mpid] and $pgames[$pid]<$pgames[$mpid]) continue;
      if($agames[$pid]==$agames[$mpid] and $pgames[$pid]==$pgames[$mpid] and $position[$pid]>$position[$mpid]) continue;
      $mpid=$pid;
      print "Selected: $mpid\n";
    }
    
    // $mpid selected
    $aval[$mpid]=2;
    $avalcount--;
  }
  // end step 2
  
  
  // get opponent list
  for($i1=1;$i1<$round;$i1++)
  {
    $t="$i1";
    if($i1<10) $t="0$i1";
    $gamestext.=file_get_contents("husc/s$season/results$t.txt")."\n";
  }
  $data1=explode("\n",$gamestext);
  
  for($i1=0;$i1<count($data1);$i1++)
  {
    $data2=explode(" ",$data1[$i1]);
    if(count($data2)!=5) continue;
    $pid1=(int)$data2[1];
    $pid2=(int)$data2[2];
    $res1=$data2[3];
    $res2=$data2[4];
    $t="$res1"."$res2";
    if(!($t==="01" or $t==="10" or $t=="+-" or $t=="-+" or $t=="--")) return -1;
    if($aval[$pid1]==1 and $aval[$pid2]==1)
    {
      $aoplist[$pid1][$pid2]++;
      $aoplist[$pid2][$pid1]++;
      if($t=="01" or $t=="10")
      {  
        $poplist[$pid1][$pid2]++;
        $poplist[$pid2][$pid1]++;
      }
    }
  }
  
  
  $plannedgames=array();
  while($avalcount>0)
  {
    if($avalcount%2!=0) return -1;//error
    // start step 3
    // pick highest ranked player, if found, continue in loop
    $pid1=-1;
    $pid2=-2;
    /*$mag=$alive*100+100;
    $mpg=$alive*100+100;
    $mll=$startlives*100+100;
    $mpos=$alive*100+100;*/
    for($i1=0;$i1<$alive;$i1++)
    {
      $pid=$pids[$i1];
      if($aval[$pid]!=1) continue;
      if($pid1==-1 )
      {
        $pid1=$pid;
        continue;
      }
      if($pid2==-2) 
      {  
        $pid2=$pid;
        continue;
      }
      // now compare
      $b=0;
      if($aoplist[$pid1][$pid2]<$aoplist[$pid1][$pid]) continue;
      if($aoplist[$pid1][$pid2]==$aoplist[$pid1][$pid])
      {
        if($poplist[$pid1][$pid2]<$poplist[$pid1][$pid]) continue;
        if($poplist[$pid1][$pid2]==$poplist[$pid1][$pid]) 
        {
          if($lives[$pid2]>$lives[$pid]) continue;
          if($lives[$pid2]==$lives[$pid] and $position[$pid2]>$position[$pid]) continue;
        }
      }
      $pid2=$pid;
      
    }
    if($pid1==-1) return -1;
    if($pid2==-2) return -1;
    $plannedgames[]=array($pid1,$pid2);
    $avalcount-=2;
    $aval[$pid1]=2;
    $aval[$pid2]=2; 
  }
  // ok, now we have a list of games in $plannedgames
  
  // deide what schedround to choose
  $schedrounds=getschedrounds($season);
  $now=time();
  $t1=$now+86400*1000;
  $sround=-1;
  for($i1=1;$i1<=$schedrounds;$i1++)
  {
    $t5=getschedroundstart($season,$i1);
    $t2=nextdate1($t5,$now);
    if($t2>$t1) continue;
    $sround=$i1;
    $t1=$t2;
  } 
  // we choose $sround as schedround
  $t5=getschedroundend($season,$sround);
  $endofround=nextdate1($t5,$t1); 
  
  $t4="$round ".date("Y-m-d H:i:s")." ".date("Y-m-d H:i:s",$endofround)."\n";
  $file=fopen("husc/s$season/startendround.txt","a");
  fwrite($file,$t4);
  fclose($file);
  
  $tplan="";
  $tresult="";
  for($i1=0;$i1<count($plannedgames);$i1++)
  {
    $pid1=$plannedgames[$i1][0];
    $pid2=$plannedgames[$i1][1];
    $i2=$i1+1;
    $tplan.="$i2 $pid1 $pid2 ".date("Y-m-d H:i:s",nextdate1(schedule1($season,$pid1,$pid2,$sround),$now))."\n";
    $tresult.="$i2 $pid1 $pid2 ? ?\n";
  }
  $rr=twodigit($round);
  file_put_contents("husc/s$season/plan$rr.txt",$tplan);
  file_put_contents("husc/s$season/results$rr.txt",$tresult);
  return 2; // good
}

function nextdate1($w,$now=-1) // $w looks like (string)"tuesday 15:43", $now is UNIX time, returns next date like $w after $now
{
  if($now==-1) $now=time();
  $data1=explode(" ",$w);
  if(count($data1)!=2) return -1;
  if(!istime($data1[1])) return -1;
  $wcode1=weekday1($data1[0]);
  if($wcode1==-1) return -1;
  for($i1=0;$i1<17;$i1++)
  {
    $t1=$now+$i1*86400;
    $wcode2=(int)date("w",$t1);
    if($wcode1!=$wcode2) continue;
    $t2=date("Y-m-d",$t1)." $data1[1]".":00";
    $t3=strtotime($t2);
    if($t3>$now) return $t3;
  }
  return -1;
}

function getopponent($season,$round,$pid) // reads planyy.txt returns -1 if no game or error, $pid of opponent otherwise
{
  $rr=twodigit($round);
  if(!file_exists("husc/s$season/plan$rr.txt")) return -1;
  $data1=file_get_contents("husc/s$season/plan$rr.txt");
  $data2=explode("\n",$data1);
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=5) continue;
    if($pid==$data3[1]) return (int)$data3[2];
    if($pid==$data3[2]) return (int)$data3[1];
  }
  return -1;
}

function getgametime($season,$round,$pid1,$pid2) // reads planyy.txt, returns UNIX time
{
  $rr=twodigit($round);
  $data1=file_get_contents("husc/s$season/plan$rr.txt");
  $data2=explode("\n",$data1);
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=5) continue;
    if(!(($pid1==$data3[1] and $pid2==$data3[2])or($pid1==$data3[2] and $pid2==$data3[1]))) continue;
    return strtotime($data3[3]." ".$data3[4]);
  }
  return -1;
}

function getresult($season,$round,$pid1,$pid2) // reads resultsyy.txt, returns array("$res1","$res2")
{
  $rr=twodigit($round);
  $data1=file_get_contents("husc/s$season/results$rr.txt");
  $data2=explode("\n",$data1);
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=5) continue;
    if($pid1==$data3[1] and $pid2==$data3[2]) return array($data3[3],$data3[4]);
    if($pid1==$data3[2] and $pid2==$data3[1]) return array($data3[4],$data3[3]);
  }
  return -1;
}

function editresult($season,$round,$playerid,$result,$admin=0) // result is "0","1","-","+","?" , 
          // this function calls writelog($season,302,$playerid,$round,$oldresult,$result) if $admin==0,
          // and writelog($season,303,...) if $admin==1, and ..,304, if $admin=2 (means system)
{
  if($result!=="0" and $result!="1" and $result!="+" and $result!="-" and $result!="?") return -1;
  $admin=(int)$admin;
  if($admin<0 or $admin>2) return -1;
  $rr=twodigit($round);
  $data1=file_get_contents("husc/s$season/results$rr.txt");
  $data2=explode("\n",$data1);
  $b=0;
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=5) continue;
    if($playerid==$data3[1]) 
    {
      $oldresult=$data3[3];
      $data2[$i1]="$data3[0] $data3[1] $data3[2] $result $data3[4]";
      $b=1;
      break;
    }
    if($playerid==$data3[2])
    {
      $oldresult=$data3[4];
      $data2[$i1]="$data3[0] $data3[1] $data3[2] $data3[3] $result";
      $b=1;
      break;
    }
    
  }
  if($b==0) return -1;
  $data1=implode("\n",$data2);
  file_put_contents("husc/s$season/results$rr.txt",$data1);
  writelog($season,302+$admin,$playerid,$round,$oldresult,$result);
  return 2;
}

function writewarning($season) // writes warning.html
{
  $round=getround($season);
  if($round==-1) return -1;
  $rr=twodigit($round);
  $twarning1="";
  $twarning2="";
  $data1=file_get_contents("husc/s$season/results$rr.txt");
  $data2=explode("\n",$data1);
  $normalarray=array("10","01","+-","-+","--","??");
  $contraarray=array("00","0-","-0","0+","+0","11","1-","-1","1+","+1","++");
  $incomarray=array("0?","?0","1?","?1","+?","?+","-?","?-");
  
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=5) continue;
    $res1=$data3[3];
    $res2=$data3[4];
    $r="$res1"."$res2";
    if(in_array($r,$normalarray,TRUE)) continue;
    $pname1=getplayername((int)$data3[1]);
    $pname2=getplayername((int)$data3[2]);
    if(in_array($r,$contraarray))
    {
      $twarning1.="\n<p><span style=\"color: #EE22BB\"><b>WARNING:</b>Contradicting result in the game $pname1 vs $pname2</span></p>";
    }
    if(in_array($r,$incomarray,TRUE))
    {
      if($twarning2!="") $twarning2.=", ";
      $twarning2.="$pname1 vs $pname2";
    }
  }
  $twarning3="";
  if($twarning2!="") $twarning3="<p><small>The following games have incomplete results: $twarning2.</small></p>";
  file_put_contents("husc/s$season/warning.html",$twarning1.$twarning3);
  return 2;
}

function countalives($season,$round=-5) // uses rankingyy.txt
{
  if($round==-5) $round=getround($season)-1;
  $rr=twodigit($round);
  $data1=file_get_contents("husc/s$season/ranking$rr.txt");
  $data2=explode("\n",$data1);
  $retval=0;
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=15) continue;
    $ll=(int)$data3[2];
    if($ll>0) $retval++;
    if($ll<=0) return $retval;
  }
  return $retval;
}



function checkendofround1($season) // to be executed daily, and on request, and after every result change
{
  $round=getround($season);
  $data1=file_get_contents("husc/s$season/startendround.txt");
  $data2=explode("\n",$data1);
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=5) continue;
    if($data3[0]!=$round) continue;
    $now=time();
    $then=strtotime($data3[3]." ".$data3[4]);
    if($then>$now) return checkendofround2($season,$round);
    return checkendofround3($season,$round);
  }
  return -1;
}



function checkendofround2($season,$round) // if before the end of round
{
  if($round==-1) return -1;//error
  $normalarray=array("10","01","+-","-+","--");
  $rr=twodigit($round);
  $data1=file_get_contents("husc/s$season/results$rr.txt");
  $data2=explode("\n",$data1);
  $normal=1;
  $subs=0;
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=5) continue;
    $res1=$data3[3];
    $res2=$data3[4];
    $r="$res1"."$res2";
    if(!in_array($r,$normalarray,TRUE)) return 1; // not ready yet
  }
  // if all results are normal:
  return checkendofround4($season,$round);
  
}
function checkendofround3($season,$round) // if after the end of round
{
  if($round==-1) return -1;//error
  
  $normalarray=array("10","01","+-","-+","--");
  $blockarray=array("11","1-","-1","1+","+1","++");
  $subsarray1=array("00","0+","+0","0-","-0","0?","?0","1?","?1","-?","?-","+?","?+","??");
  $subsarray2=array("--","-+","+-","--","--","--","--","10","01","--","--","+-","-+","--");
  $rr=twodigit($round);
  $data1=file_get_contents("husc/s$season/results$rr.txt");
  $data2=explode("\n",$data1);
  $normal=1;
  $subs=0;
  $block=0;
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=5) continue;
    $res1=$data3[3];
    $res2=$data3[4];
    $r="$res1"."$res2";
    if(in_array($r,$blockarray,TRUE)) 
    {
      $block=1;
      continue;
    } // block
    if(in_array($r,$normalarray)) continue;
    $index=array_search($r,$subsarray1,TRUE);
    if($index===false) return -1;
    $rs1=substr($subsarray2[$index],0,1);
    $rs2=substr($subsarray2[$index],1,1);
    if($rs1!=$res1) editresult($season,$round,(int)$data3[1],$rs1,2);
    if($rs2!=$res2) editresult($season,$round,(int)$data3[2],$rs2,2);
  }
  if($block==1) return 3;
  return checkendofround4($season,$round);
}


function checkendofround4($season,$round) // called by --2 and --3
{
  // ready to finish round - all results should be normal
  $retcode1=ranking($season,$round);
  writelog($season,131,$round,$retcode1);
  if($retcode1==-1) return -1;
  $alive=countalives($season,$round);
  if($alive<0) return -1;
  if($alive==0 or $alive==1)
  {
    file_put_contents("husc/s$season/status.txt","finished");
    return 7;
  }
  setround($season,$round+1);
  $round=$round+1;
  $retcode2=pairing($season,$round);
  writelog($season,132,$round,$retcode2);
  writewarning($season);
  if($retcode2==-1) return -1;
  return 5;
}

function dailyhusc()
{
  $noseason=0;
  $season=0;
  $mailtext="";
  while($season<1000)
  {
    $season++;
    if(!file_exists("husc/s$season/status.txt"))
    {
      $noseason++;
      if($noseason>8) break;
      continue;
    }
    $noseason=0;
    $round=getround($season);
    if($round==-1) continue;
    
    $retcode1=checkendofround1($season);
    if($retcode1==3)
    {
      $mailtext.=<<<E
Hello admin,

the HUSC system tried to start a new round in season $season but could not do so, because there were conflicting results. please fix this.

best regards,

E;
    }
    if($retcode1==-1) $mailtext.="Hello admin\n\nthe HUSC system tried to start a new round in season $season, but there was an error.\n\nbest regards\n\n";
    $season++;
  }
  if($mailtext!="")
  {
    mail("super.noob1@aol.com", "HUSC", $mailtext, "From: BBC <automail@bbcpoker.bplaced.net>");
	//mail("nelly.lecoent@orange.fr", "HUSC", $mailtext, "From: BBC <automail@bbcpoker.bplaced.net>");
	//mail("nelly.lecoent@gmail.com", "HUSC", $mailtext, "From: BBC <automail@bbcpoker.bplaced.net>");
  }
}

function getalivesarray($season=-6)
{
  // TODO
  if($season==-6) // all seasons
  {
    $noseason=0;
    $ret=array();
    for($ss=1;$ss<3004;$ss++)
    {    
      if(!file_exists("husc/s$ss/status.txt"))
      {
        $noseason++;
        if($noseason>12) break;
      }
      $noseason=0;
      $temp=getalivesarray($ss);
      $ret=array_merge($ret,$temp);
    }
	return $ret;
  }
  $ret=array();
  $round=getround($season)-1;
  if($round==-2) return $ret;
  $rr=twodigit($round);
  $data1=file_get_contents("husc/s$season/ranking$rr.txt");
  $data2=explode("\n",$data1);
  for($i1=0;$i1<count($data2);$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=15) continue;
    $ll=(int)$data3[2];
    if($ll>0) 
    {
      $ret[]=(int)$data3[1];
    }
    if($ll<=0) return $ret;
  }
  return $ret;
}


function isfinished($season)
{
  // if finished, return 1, otherwise -1
  if(!file_exists("husc/s$season/status.txt")) return -1;
  $t1=file_get_contents("husc/s$season/status.txt");
  if($t1=="finished") return 1;
  return -1;
}

function getfinishedround($season)
{
  // get the max number of rounds if tournaament is over
  for($round=1;$round<3005;$round++)
  {
    $rr=twodigit($round);
    if(!file_exists("husc/s$season/results$rr.txt")) return $round-1;
  }
  return -1;
}
?>
