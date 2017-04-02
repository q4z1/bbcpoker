<?php

die("good night");

/*
ini_set('include_path', '/home/www/bbc/');
$regulartaskcount=1;
include "exp2/regulartasks.php";
include "exp5/func1.php";
include_once "exp6/func3.php";



$data1=file_get_contents("secret/passwords.txt");

$data2=explode("\n",$data1);

$now=date("Y-m-d H:i:s",time());


for($i1=0;$i1<count($data2);$i1++)
{
  $row=$data2[$i1];
  $data3=explode(":",$row);
  if(count($data3)!=2) continue;
  
  // read md5, make salt and new hash, then --->>> database
  
  $name=mysql_real_escape_string($data3[0]);
  $hash1=$data3[1];
  $salt=makesalt(16);
  $newhash=md5($salt.$hash1);
  $request="INSERT INTO admins
(name,salt,hash,class)
VALUES
(\"$name\",\"$salt\",\"$newhash\",0)
";
  mysql_query($request) or die("ERROR"); 
  print "$name...<br>\n";
}
print "<br><b>done</b><br>\n";
*/
?>