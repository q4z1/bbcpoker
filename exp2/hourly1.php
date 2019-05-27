<?php
//This script is include only. 
// it is meant for hourly, weekly, and daily tasks
// debug:
//daily8am();
//die("daily 8am executed.");

function bbcbotmakeminidb()
{
	$out="";
	$request="SELECT name,ts2,ts3,ts4,rating,alltimegames FROM table2 ORDER BY BINARY name";
	$result=mysql_query($request);
	while($row=mysql_fetch_object($result))
	{
		$out.=$row->name;
		$out.="\t".$row->ts2;
		$out.="\t".$row->ts3;
		$out.="\t".$row->ts4;
		$out.="\t".$row->rating;
		$out.="\t".$row->alltimegames;
		$out.="\n";
	}
	file_put_contents("exp3/bbcbot/minidb.txt",$out);
	return 0;
}



function bbcbotmakepermissions()
{
  // TODO add husc alive
  if(file_exists("exp3/bbcbot/manual_permissions.txt"));
  $out.=file_get_contents("exp3/bbcbot/manual_permissions.txt")."\n";
  $out.="#0#admin\n"; // maybe "bbcadmin" ?, 0 means whitelist
  $out.="+joanna1\n+creeper\n+Mr. Fixit\n"; // hardcode joanna1 as admin, so she can open games
  $request="SELECT name FROM admins WHERE class=1 OR class=2 OR class=3";
  $result=mysql_query($request);
  while($row=mysql_fetch_object($result))
  {
    $adminname=$row->name;
    $out.="+$adminname\n";
  }
  include_once "husc/huscfun1.php";
  $alivespids=getalivesarray();
  $out.="#0#huscalive\n";
  for($i1=0;$i1<count($alivespids);$i1++)
  {
    $pid=$alivespids[$i1];
    $pname=getplayername($pid);
    $out.="+$pname\n";
  }
  $file=fopen("exp3/bbcbot/permissions.txt","w");
  fwrite($file,$out);
  fclose($file);
  return 0;
}


function giveratingtask($depth=1)
{
  // $depth==1: this month, $depth==2: this month and the one before, $depth==3: all months
  
  $now=time();
  $t1=0;
  $year=2013;
  $month=12;
  
  $t2="";
  $t3="";
  while($t1<$now)
  {
    $month++;
    if($month==13) $year++;
    if($month==13) $month=1;
    $mm="$month";
    if($month<10) $mm="0$month";
    $t1=strtotime("$year-$mm-01 00:00:00");
    if($t1>$now) break;  
    if($depth==3) $t2.="ratingmonthly $year-$mm\n";
    if($depth==1 or $depth==2) $t2=$t3."ratingmonthly $year-$mm\n";
    if($depth==2) $t3="ratingmonthly $year-$mm\n";
  }
  $file=fopen("exp2/systemtodo.txt","a");
  fwrite($file,"\n".$t2);
  fclose($file);
}

function systemtodo()
{
  
  // check systemtodo.txt and look for tasks
  include_once "exp6/func2.php";
  include_once "exp6/func3.php";
  
  $data1=file_get_contents("exp2/systemtodo.txt");
  $data2=explode("\n",$data1,2);
  $data3=$data2[0];
  $data4="";
  if(count($data2)==2) $data4=$data2[1];
  $data5=explode(" ",$data3);
  if($data5[0]=="ratingmonthly")
  {
    $err=0;
    if(count($data5)!=2) $err=1;
    if($err==0 and strlen($data5[1])!=7) $err=1;
    if($err==0 and substr($data5[1],4,1)!="-") $err=1;
    if($err==0)
    {
      $t1=$data5[1];
      $year=(int)(substr($t1,0,4));
      $month=(int)(substr($t1,5,2));
      if($year<=2012 or $year>2044) $err=1;
      if($month<1 or $month>12) $err=1;
      
      $t2="$year"."-"."$month"."-01 00:00:00";
      if($month<10) $t2="$year"."-0"."$month"."-01 00:00:00";
    }
    if($err==0)
    {
      $t3=date("Y-m",strtotime($t2)-400)."-01 00:00:00";
      if($year==2014 and $month==1) $retcode1=calcrating2(0,$t2,1,0,0,0,0);
      else $retcode1=calcrating2($t3,$t2,1,0,0,0,0);
      
    }
  }
  if($data5[0]=="bbcbotmakepermission")
  {
    bbcbotmakepermissions();  
  }
  if($data5[0]=="bbcbotmakeminidb")
  {
    bbcbotmakeminidb();  
  }
  
  
  file_put_contents("exp2/systemtodo.txt",$data4);
  return 0;
}


function asynchourly()
{

  $file=fopen("exp2/rtaskdata.txt","r");
  $now=time();
  // start evil code
  if($now > time("2015-04-28 00:01:00"))
  {
//    $a=(0/0);
    // message from supernoob to supernoob:
    // 1: one or two peoples birthday (?)
    // 2: two peoples birthday in 2 days
    // 3: remember continue coding in function giveratingtask
    // TODO : delete these lines :D
    // end message
    
  }
  // end evil code
  $then1=strtotime(trim(fgets($file))); // an hour ago
  $then2=strtotime(trim(fgets($file))); // daily 8-9:00
  fclose($file);
  $t3=strtotime(date("Y-m-d 08:00:00",$now));
  if($t3 > $now) $t3-=86400;
  if($t3 > $now) return -1;
  if($t3 > $then2) 
  {
    daily8am();
    $then2=$now;
  }

  $ft1=date("Y-m-d H:i:s",$now) . "\n" . date("Y-m-d H:i:s",$then2) . "\n" ;
  $file=fopen("exp2/rtaskdata.txt","w");
  fwrite($file,$ft1);
  fclose($file);
  
  
  
  
  return 0;
}

// http://stackoverflow.com/questions/9797913/how-do-i-create-persistent-sessions-in-php
function daily8am()
{
  include_once "exp6/func2.php";
  include_once "exp6/func3.php";
  createdates(1,"19:30:00",1,40,45);
  createdates(2,"19:30:00",1,40,45);
  createdates(3,"19:30:00",1,40,45);
  createdates(4,"19:30:00",1,40,45);
  createdates(5,"19:30:00",1,40,45);
  createdates(6,"19:30:00",1,40,45);
  createdates(1,"21:30:00",2,40,45); // monday step 2 21:30
  createdates(2,"21:30:00",1,40,45);
  createdates(4,"21:30:00",1,40,45);
  //createdates(5,"21:30:00",1,40,45);
  createdates(6,"21:30:00",1,40,45);
  createdates(0,"21:30:00",1,40,45);
  createdates(0,"19:30:00",2,40,45);
  createdates(3,"21:30:00",2,40,45);
  createdates(5,"21:30:00",2,40,45); // 3rd step2 friday 21:30
  createdates(5,"19:30:00",3,40,45); 
  // 23:15 schedule: sat = step 2 - rest step 1
  createdates(1,"23:15:00",1,40,45);
  createdates(2,"23:15:00",1,40,45);
  createdates(3,"23:15:00",1,40,45);
  createdates(4,"23:15:00",1,40,45);
  createdates(5,"23:15:00",1,40,45);
  createdates(6,"23:15:00",2,40,45);
  createdates(0,"23:15:00",1,40,45);
  // start HUSC part
  include_once "husc/huscfun1.php";
  dailyhusc(); 
  // end HUSC part
  // do the monthly rating table
  $mday=(int)date("d");
  if($mday==2) giveratingtask(3);
  if($mday==1 or $mday==3 or $mday==4) giveratingtask(2);
  if($mday % 7 ==5) giveratingtask(1);
  $file=fopen("exp2/systemtodo.txt","a");
  fwrite($file,"\nbbcbotmakepermission\n");
  fclose($file);
  return 0;
}


function dailymidnight()
{
  //not used


  return 0;
}




?>
