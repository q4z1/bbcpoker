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

if($_GET['action']=="1")
{
  $success=0;
  $oldphase=getphase($season);
  if($oldphase==0 and $_GET['phase']=="1" and isschedgood($season))
  { 
    setphase($season,1);
    $success=1;
  }
  if($oldphase==1 or $oldphase==3 and $_GET['phase']=="2")
  {
    setphase($season,2);
    $success=1;
  }
  if($oldphase==2 and $_GET['phase']=="3")
  {
    setphase($season,3);
    $success=1;
  }
  if($oldphase==3 and $_GET['phase']=="4" and countundecidedregistrations($season)==0)
  {
    if(reg2participants($season)) $success=1;
    if($success==1)
    {
      setdefaultschedpref($season);
      setphase($season,4);
    }
  }
  if($oldphase==4 and $_GET['phase']=="5")
  {
    $lives=(int)$_GET['lives'];
    if($lives>=1)
    {
      setstartlives($season,$lives);
      setphase($season,5);
      $success=1;
    }
  }
  if($oldphase==5 and $_GET['phase']=="6")
  {
    $retcode1=ranking($season,0);
    writelog($season,131,0,$retcode1);
    setround($season,1);
    $retcode2=pairing($season,1);
    writelog($season,132,1,$retcode2);
    if($retcode1==2 and $retcode2==2) $success=1;
  }
  if($success==1) print "<h3>It looks like your action was successfull</h3>";
  if($success==0) print "<h3>It looks like there was an error</h3>";
}
print <<<E
<h1>HUSC Admin control</h1>
E;
print "<h2>Season $season</h2>";
$phase=getphase($season);
if($phase==-1) print "<p>It looks like there is currently nothing to do here. Maybe the tournament is already running.</p>";
else
{
  print "<h4> Phase $phase</h4>";
  if($phase==0)
  {
    if(isschedgood($season))
    print "<p>You have the option to go to phase 1, if the code of conduct and the schedule pattern are ready. In that case click the following link:<br>
    <a href=\"husc/admincontrol1.php?action=1&s=$season&phase=1\">CLICK HERE for phase 1</a></p>";
    else print "<p>It looks like your schedule pattern file (\"schedule.txt\") has some mistakes. Fix this, before advancing to phase 1.</p>";
  }
  if($phase==1)
  {
    print "<p>You have the option to go to phase 2. This will open the registration. In that case click the following link:<br>
    <a href=\"husc/admincontrol1.php?action=1&s=$season&phase=2\">CLICK HERE for phase 2</a></p>"; 
  }
  if($phase==2 or $phase==3)
  {
    $decreg=countdeclinedregistrations($season);
    $undreg=countundecidedregistrations($season);
    $accreg=countacceptedregistrations($season);
    $totreg=$decreg+$undreg+$accreg;
	$reglist="<p>Currently we have</p>
    <ul>
    <li>$decreg declined registrations</li>
    <li>$undreg undecided registrations</li>
    <li>$accreg accepted registrations</li>
    <li>so $totreg total registrations</li>
    </ul>";
  }
  if($phase==2)
  {
    print $reglist;
    print "<p>You have the option to accept/decline registrations during phase 2 and 3. 
    <a href=\"husc/admincontrol2.php\">Click here to accept/decline registrations</a><br>
    You have the option to go to phase 3. This will close the registration. In that case click the following link:<br>
    <a href=\"husc/admincontrol1.php?action=1&s=$season&phase=3\">CLICK HERE for phase 3</a></p>";
  }
  if($phase==3)
  {
    print $reglist;
    print "<p>You have the option to accept/decline registrations during phase 2 and 3. 
    <a href=\"husc/admincontrol2.php\">Click here to accept/decline registrations</a><br>
    You have the option to go to phase 2. This will open the registration again. In that case click the following link:<br>
    <a href=\"husc/admincontrol1.php?action=1&s=$season&phase=2\">CLICK HERE for phase 2</a></p>";
    if($undreg==0 and $accreg >=2)
    {
      print "<p>Since there are no undecided registrations, you also have the option to go to phase 4.
      This will determine the participants of the HUSC season (all accepted registrations). 
      It also does the seeding according to the rating at the beginning of this month 
      (better don't do this within the first day of a month - source of errors/confusion/rating table might be nonexistent)
      It is not easy to change the participation list after this point.
      If you are willing to progress, <a href=\"husc/admincontrol1.php?action=1&s=$season&phase=4\">CLICK HERE for phase 4</a></p>";
    }
    else print "<p>You can only continue to the next phase, if there are no undecided registrations left</p>";
  }
  if($phase==4)
  {
    print <<<E
    <p>Now you can tell people, to <a href="husc/editsettings.php?s=$season">change their schedule preferences</a>.</p>
    
    <p>In the next step, you need to determine the exact number of lives, everyone starts with. 
    This will bring you to phase 5. You cannot go back after you click one of the following links:</p>
    <ul>
    <li><a href="husc/admincontrol1.php?action=1&s=$season&phase=5&lives=1">go to phase 5 with <b>1</b> life</a></li>
    <li><a href="husc/admincontrol1.php?action=1&s=$season&phase=5&lives=2">go to phase 5 with <b>2</b> lives</a></li>
    <li><a href="husc/admincontrol1.php?action=1&s=$season&phase=5&lives=3">go to phase 5 with <b>3</b> lives</a></li>
    <li><a href="husc/admincontrol1.php?action=1&s=$season&phase=5&lives=4">go to phase 5 with <b>4</b> lives</a></li>
    <li><a href="husc/admincontrol1.php?action=1&s=$season&phase=5&lives=5">go to phase 5 with <b>5</b> lives</a></li>
    <li><a href="husc/admincontrol1.php?action=1&s=$season&phase=5&lives=6">go to phase 5 with <b>6</b> lives</a></li>
    <li><a href="husc/admincontrol1.php?action=1&s=$season&phase=5&lives=7">go to phase 5 with <b>7</b> lives</a></li>
    <li><a href="husc/admincontrol1.php?action=1&s=$season&phase=5&lives=8">go to phase 5 with <b>8</b> lives</a></li>
    <li><a href="husc/admincontrol1.php?action=1&s=$season&phase=5&lives=9">go to phase 5 with <b>9</b> lives</a></li>
    </ul>
E;
  }
  if($phase==5)
  {
    print <<<E
    <p>Now you can tell people, to <a href="husc/editsettings.php?s=$season">change their schedule preferences</a>.
    Make sure that you give them enough time to do so.
    If you waited long enough, you can finally start the tournament (round 1)by clicking the following link:</p>
    <p><a href="husc/admincontrol1.php?action=1&s=$season&phase=6">CLICK HERE to start HUSC season $season</a></p>    
E;
  }
}
?>

<?php
include "footer1.php";
?>
</body>
</html>