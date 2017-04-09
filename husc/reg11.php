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
include "husc/huscfun1.php"; // getseason()
$season=getseason();
include "husc/s$season/warning.html";
include "husc/huscnav1.php";

?>

<?php
$season=getseason();

print <<<E
<h1>HUSC Registration Page</h1>
<h2>Season $season</h2>
E;

$phase=getphase($season);

if($phase != 2) print "<p>Before wasting your time: you cannot register right now</p><br>\n";

print "<p>This is the page where you can register for the season $season of HUSC. In order to register, you need to do the following things:</p>\n";
print <<<E
<ol>
<li>Create a BBC Account <a href="exp4/createaccount.php">HERE</a> <small>If you have already a bbc account, skip this Step</small></li>
<li>Login <a href="login.php">HERE</a></li>
<li>Read this page</li>
<li>Register for HUSC Season $season at the bottom of the page</li>
</ol>


E;
//if($auth==0) print "Before you register, you need a BBC account, you can get one <a href=\"exp4/createaccount.php\">here</a> or <a href=\"login.php\">login here</a>.</p>";



if($phase >=1) print sched2html($season);
if($phase >=1) include "husc/s$season/codeofconduct.html";

if($auth==1 and $phase==2)
{
  $error=0;
  $pid=getplayerid($_SESSION['user3']);
  if($pid==-1) $error=1;
  if($error==0)
  {
    if(getbbcgames($pid)<10) $error=1;
	if(isregistered($season,$pid)) $error=1;
  }
  if($error==0)
  {
    $playername=$_SESSION['user3'];
    $t1=time(); // now
    print <<<E
    <form action="husc/reg12.php?s=$season" method="post">
    <input type="checkbox" name="check1" value="1">I confirm that i read everything above.<br>
    <input type="submit" name="submit" value="Register $playername for HUSC season $season">
    <input type="hidden" name="playerid" value="$pid">
    <input type="hidden" name="t1" value="$t1">
    </form>
E;
  }
}
if($phase!=2) print "<p>Sorry, registration is not open right now.</p>";
if($phase==2 and $auth!=1) print "<p>You cannot register now, because you are not logged in. You can <a href=\"login.php\">login here</a>.</p>";

?>

<?php
include "footer1.php";
?>

</body>
</html>