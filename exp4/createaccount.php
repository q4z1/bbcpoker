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
include_once "exp6/func3.php";
?>


<?php

if($_POST['submit']=="")
{
  print <<<E
<h1>Create a BBC account</h1>
<form action="exp4/createaccount.php" method="post">
<br>
<b>BBC-ID</b>: <input type="text" name="bbcid"><br>
<b>Name</b> (in game): <input type="text" name="nick"><br>
<b>Password</b>: <input type="password" name="pword17"><br>
<b</b>Repeat Password: <input type="password" name="pword19"><br>
<p>You can only create a BBC account, if you have 10 or more played BBC games.</p>
<p>About the password: the pasword can and maybe should be different from the one you use in pokerth. 
The owner of this website will not know your password (unless by an accident) </p>
<p>Your BBC-id can be found on your bbc profile page.</p>
<input type="submit" name="submit" value="submit registration!">

</form>

E;

}

function getbbcgames($pid) // gets total number of bbc games given a playerid
{
  $pid=(int)$pid;
  $request="SELECT alltimegames FROM table2 WHERE playerid=$pid LIMIT 1";
  $result=mysql_query($request);
  if(!$result) return -1;
  return (int)mysql_fetch_array($result)[0];
}


if($_POST['submit']=="submit registration!")
{
  $pw1=$_POST['pword17'];
  $pw2=$_POST['pword19'];
  $nick=$_POST['nick'];
  $playerid=(int)$_POST['bbcid'];
  // check 1: check if name and bbcid match
  $request="SELECT name FROM table2 WHERE playerid=$playerid";
  $error=0;
  if($playerid<1024) $error=1;
  $result=mysql_query($request);
  if($row=mysql_fetch_object($result))
  {
    $bbcnick=$row->name;
    if($bbcnick != $nick) $error=2;
  }
  else $error=1;
  // check 2: check if name OR bbcid is already in use
  
  if($error==0)
  {
    $nick2=mysql_real_escape_string($nick);
    $request="SELECT COUNT(*) FROM admins WHERE name='$nick2'";
    $result=mysql_query($request);
    $row=mysql_fetch_array($result);
    if((int)$row[0]!=0) $error=3;
    $request="SELECT COUNT(*) FROM admins WHERE playerid=$playerid";
    $result=mysql_query($request);
    $row=mysql_fetch_array($result);
    if((int)$row[0]!=0) $error=4;
    if(getbbcgames($playerid)<10) $error=8;
  }
  // check 3: check if passwords are equal or too short
  if($pw1 !== $pw2 or strlen($pw1)<3) $error=5;
  if($error!=0) print "<h2>There was a mistake with your registration: $error</h2>";
  if($error==0) 
  {
    $salt=makesalt(16);
    $hash1=md5($pw1);
    $hash2=md5($salt.$hash1);
    $now=date("Y-m-d H:i:s",time());
    $nick2=mysql_real_escape_string($nick);
    $request="INSERT INTO `admins`
    (name,salt,hash,class,playerid,regtime) 
    VALUES 
    ('$nick2','$salt','$hash2',8,$playerid,'$now')";
    $result=mysql_query($request);
    print "<h2>It seems like your registration was successful</h2>";
  }
}

?>

<?php
include "footer1.php";
?>

</body>
</html>