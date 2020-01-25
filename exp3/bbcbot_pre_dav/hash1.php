<?php
//die("not active");
header('Content-Type: text/plain');
require_once($_SERVER['DOCUMENT_ROOT'].'/defines.php');
ini_set('include_path', ROOT_DIR);
require_once $_SERVER['DOCUMENT_ROOT'].'/defines.php';
chdir(ROOT_DIR);

// $regulartaskcount=1;
// include "exp2/regulartasks.php"; Connection to database not needed

$prefix="exp3/bbcbot/";
$hash2filename=$prefix."hash2.txt";

// NOTE: the following fileslist should not contain names that contain spaces
$fileslist=array("gameslist.txt","permissions.txt","fixedcommands.txt","minidb.txt", "weclist.txt");
// end fileslist

$data1=file_get_contents("exp3/bbcbot/gameslist.txt");
$data2=explode("\n",$data1);

for($i1=0;$i1<count($data2);$i1++)
{
  //var_dump($fileslist);
  $data3=explode("#",$data2[$i1]);
  if(count($data3)<5) continue;
  if(strpos($data3[1]," ")===false) $fileslist[]=$data3[1]."_settings.txt";
  //print "check..";
}

$hash2a=array();

for($i1=0;$i1<count($fileslist);$i1++)
{
  $f1=$prefix.$fileslist[$i1];
  if(!file_exists($f1)) continue;
  $t1=md5_file($f1);
  $hash2a[]="$t1 ".$fileslist[$i1];
}

$hash2=implode("\n",$hash2a);

file_put_contents($hash2filename,$hash2);

print md5_file($hash2filename);

?>
