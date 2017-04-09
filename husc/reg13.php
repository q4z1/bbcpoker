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
<h1>View Registrations</h1>
<h2>Season $season</h2>
E;
$decreg=countdeclinedregistrations($season);
$undreg=countundecidedregistrations($season);
$accreg=countacceptedregistrations($season);
$totreg=$decreg+$undreg+$accreg;
print <<<E
<h3>Accept or Decline Players</h3>
<p>Currently we have</p>
<ul>
<li>$decreg declined registrations</li>
<li>$undreg undecided registrations</li>
<li>$accreg accepted registrations</li>
<li>so $totreg total registrations</li>
</ul>
<p>Note: the &quot;Confirmed account&quot; is not up to date. It is the state of the account at the date of registration</p>
<table border=1>
<tr><th>Name</th><th>Reg.Time</th><th>Confirmed account</th><th>Status</th></tr>
E;

$data1=file_get_contents("husc/s$season/registrations.txt");
$data2=explode("\n",$data1);
for($i1=0;$i1<count($data2);$i1++)
{
  
  $data3=explode(" ",$data2[$i1]);
  if(count($data3)!=4) continue;
  $pid=(int)$data3[2];
  if($pid < $minpid) $minpid=$pid;
  if($pid > $maxpid) $maxpid=$pid;
  $name=getplayername($pid);
  $time=$data3[0] ." ". $data3[1];
  $status=(int)$data3[3];
  $confirmedaccount="no";
  if($status==2 or $status==4 or $status==6) $confirmedaccount="yes";
  $sts="";
  if($status==2 or $status==3) $sts="declined";
  if($status==4 or $status==5) $sts="undecided";
  if($status==6 or $status==7) $sts="accepted";
  print "<tr><td>$name</td><td>$time</td><td>$confirmedaccount</td><td>$sts</td></tr>\n";
}
print "</table>\n";

?>

<?php
include "footer1.php";
?>

</body>
</html>