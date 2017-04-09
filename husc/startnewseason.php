<?php
$adminmode=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 )
    $adminmode = 1;
} //$_COOKIE['PHPSESSID'] != ""
$regulartaskcount=1;
require_once($_SERVER['DOCUMENT_ROOT'].'/defines.php');
ini_set('include_path', ROOT_DIR);
include "exp2/regulartasks.php";
print '<!DOCTYPE html>
<html>';
include "head.php";
print "<body>";
include "header1.php"; 
include "husc/huscnav1.php";
include "husc/huscfun1.php"; // getphase(), isschedgood() , getseason()

?>

<?php

if($adminmode==0) 
{
  print "<p>Sorry, you cannot access this page</p>";
  include "footer1.php";
  die("</body></html>");
}

if($_GET['action']=="1")
{
  $season=(int)$_GET['season'];
  $error=0;
  if($season<=0) $error=1;
  $retcode1=startnewseason($season);
  if($retcode1!=3) $error=2;
  if($error==0) print "<p>It looks like <b>you</b> sucessfully <b>created HUSC season $season</b></p>";
  if($error!=0) print "<p>It looks like there was an error with creating season $season: errorcode $error</p>";
  
}

print <<<E
<h1>HUSC Admin - Start new season</h1>
E;

$seasonarray=array();

$noseason=0;
$season=0;
while($season<10000)
{
  $season++;
  if(is_dir("husc/s$season"))
  {
    $noseason=0;
    continue;
  }
  print "<p><a href=\"husc/startnewseason.php?action=1&season=$season\">Click here</a> to start season $season</p>\n";
  $noseason++;
  if($noseason>5) break;
}
?>

<?php
include "footer1.php";
?>

</body>
</html>