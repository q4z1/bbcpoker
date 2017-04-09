<?php
$adminmode=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc']==2 or $_SESSION['upc']==3 or $_SESSION['upc']==7 or $_SESSION['upc']==8)
    $auth = 1;
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


$season=getseason();

print <<<E
<h1>Enter your results - Season $season</h1>
E;
//print "<h2>Season $season</h2>";
if($auth==0) print "<p>Sorry, you cannot see this page. You need to be logged in to see this page</p>";
$participant=0;
if($auth==1)
{ 
  $pname=$_SESSION['user3'];
  $pid=getplayerid($pname);
  $phase=getphase($season);
  if(isparticipant($season,$pid)) $participant=1;
  if($participant==0) print "<p>Sorry, you are not a participant this season, therefore this page is useless for you</p>";
  if($participant==1)
  {  
    $round=getround($season);
    if($round>0) $oppid=getopponent($season,$round,$pid);
    else $oppid=-1;
    if($oppid==-1)
    {
      print "<p>It looks like you have no game right now - so you cannot enter any results - sorry.</p>";
    }
    $radioname="r$round"."o$oppid";
  }
}
if($auth==1 and $participant==1 and $oppid!=-1 and $_POST['action']==1)
{

  $error=0;
  $rdata=$_POST[$radioname];
  if($rdata!="r?" and $rdata!="r1" and $rdata!="r0" and $rdata!="r-" and $rdata!="r+") $error=1;
  if($error==0) 
  {
    $rd2=substr($rdata,1,1);
    $retcode=editresult($season,$round,$pid,$rd2,0);
    if($retcode!=2) $error=2;
  }
  if($error==1 or $error==2) print "<p><b>There was an error with your input, sorry</b></p>\n";
  if($error==0) print "<p>It looks like you successfully entered your result</p>\n";
  if($error==0) writewarning($season);
  checkendofround1($season);
}


if($auth==1 and $participant==1 and $oppid!=-1 )
{
  print "<p>Welcome <b>$pname</b>. Here you can enter your results for your current HUSC game.</p>
  <p><small>You can change your results later, but every change of results will be logged and avaliable to everyone. 
  So do not change your result more often than necessarry</small></p>\n";
  $error=0;
  
  $opname=getplayername($oppid);
  $unixgamet=getgametime($season,$round,$pid,$oppid);
  $gamet=date("D, d M Y H:i T",$unixgamet); // human time
  print "<h2>Game against $opname, $gamet</h2>";
  $resarray=getresult($season,$round,$pid,$oppid);
  $res=$resarray[0];
  if($res=="1") $cc1="checked";
  if($res=="0") $cc0="checked";
  if($res=="-") $ccminus="checked";
  if($res=="+") $ccplus="checked";
  if($res=="?") $ccq="checked";
  $buttontext="Change Result";
  if($res=="?") $buttontext="Submit new result";
  print <<<E
  <form action="husc/editresults.php?s=$season" method="post">
  <input type="radio" name="$radioname" value="r?" $ccq>Unknown result<br>
  <input type="radio" name="$radioname" value="r1" $cc1>We played a game and i won<br>
  <input type="radio" name="$radioname" value="r0" $cc0>We played a game and i lost<br>
  <input type="radio" name="$radioname" value="r+" $ccplus>I was there but my opponent was not (forfeit win)<br>
  <input type="radio" name="$radioname" value="r-" $ccminus>I was not there for the game (forfeit loss)<br>
  <input type="hidden" name="action" value=1>
  <input type="submit" name="submit" value="$buttontext">
  </form>
  <p><small>By the way: currently the result of your opponent for this game says: <b>$resarray[1]</b></small></p>
E;
  
}
?>

<?php
include "footer1.php";
?>

</body>
</html>