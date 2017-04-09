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

$season=getseason();
$phase=getphase($season);
if($_GET['action']=="1" and ($phase==2 or $phase==2))
{
  $succcount=0;
  $minpid=(int)$_POST['minpid'];
  $maxpid=(int)$_POST['maxpid'];
  for($i1=$minpid;$i1<=$maxpid;$i1++)
  {
    if(!isset($_POST["rs$i1"])) continue;
    $newstatus=(int)$_POST["rs$i1"];
    if($newstatus<2 or $newstatus > 7) continue;
    $pid=$i1;
    setregistrationstatus($season,$pid,$newstatus);
    $succcount++;
  }
  print "<h2>The registration status for $succcount people was updated</h2>\n";
}

print <<<E
<h1>HUSC Admin control</h1>
E;
print "<h2>Season $season</h2>";

if($phase!=2 and $phase!=3) print "<p>You cannot do anything here right now (wrong phase)</p>";
$minpid=9999;
$maxpid=999;
if($phase==2 or $phase==3)
{
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
  <form action="husc/admincontrol2.php?s=$season&action=1" method="post">
  <table border=1>
  <tr><th>Name</th><th>Reg.Time</th><th>Confirmed account</th><th>Declined</th><th>Undecided</th><th>Accepted</th></tr>
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
    $cc2="";
    $cc4="";
    $cc6="";
    if($status==2 or $status==3) $cc2="checked";
    if($status==4 or $status==5) $cc4="checked";
    if($status==6 or $status==7) $cc6="checked";
    $radios="<td><input type=\"radio\" name=rs$pid value=3 $cc2></td>
    <td><input type=\"radio\" name=rs$pid value=5 $cc4></td>
    <td><input type=\"radio\" name=rs$pid value=7 $cc6></td>";
    if($confirmedaccount=="yes") $radios="<td><input type=\"radio\" name=rs$pid value=2 $cc2></td>
    <td><input type=\"radio\" name=rs$pid value=4 $cc4></td>
    <td><input type=\"radio\" name=rs$pid value=6 $cc6></td>";
    print "<tr><td>$name</td><td>$time</td><td>$confirmedaccount</td>$radios</tr>\n";
  }
  print "</table>\n";
  $minpid -=3;
  $maxpid +=3;
  print "<input type=\"hidden\" name=\"maxpid\" value=$maxpid>\n";
  print "<input type=\"hidden\" name=\"minpid\" value=$minpid>\n";
  print "<input type=\"submit\" name=\"submit\" value=\"Submit Changes\">\n";
  print "</form>";
  
}
else print "<p>Sorry, at the moment there is nothing to do here.</p>";

?>

<?php
include "footer1.php";
?>

</body>
</html>