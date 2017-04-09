<?php
$auth=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  $upc=(int)$_SESSION['upc'];
  if ($upc == 1 or $upc==2 or $upc==3 or $upc == 7 or $upc==8 )
    $auth = 1;
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
include "husc/huscnav1.php";
include "husc/huscfun1.php"; // getseason()
?>

<?php


$season=getseason();


print <<<E
<h1>HUSC Registration Page</h1>
<h2>Season $season</h2>
E;

$phase=getphase($season);

$error=0;
if($phase != 2) $error=1;
if($auth!=1) $error=2;
if($error==0 and $_POST['check1']!=1) $error=3;
$pid=(int)$_POST['playerid'];
//check if playerid matches name
if($_SESSION['user3']!=getplayername($pid)) $error=4;
if($error==0 and isregistered($season,$pid)) $error=5; 
if($error==0 and getbbcgames($pid)<10) $error=6;

$t1=(int)$_POST['t1'];
$now=time();
if($now-$t1 <=5 or $now-$t1 >= 1440) $error=7;


if($error==0) // all checks complete, start with actual registration
{
  $registrationstatus=5;
  $upc=(int)$_SESSION['upc'];
  if($upc==1 or $upc==2 or $upc==3 or $upc==7) $registrationstatus=4;
  $row=date("Y-m-d H:i:s") . " $pid $registrationstatus\n";
  $file=fopen("husc/s$season/registrations.txt","a");
  fwrite($file,$row);
  fclose($file);
  writelog($season,201,$pid,$registrationstatus);
  print "<p>It looks like your registration was successful</p>";
}
else print "<p>It looks like there is an <b>ERROR</b> with your registration (code: $error). Please reade the Schedule and the Code of Conduct carfully.</p>";

?>

<?php
include "footer1.php";
?>

</body>
</html>