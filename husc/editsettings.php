<?php
$adminmode=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc']==2 or $_SESSION['upc']==3 or $_SESSION['upc']==7 or $_SESSION['upc']==8)
    $auth = 1;
} //$_COOKIE['PHPSESSID'] != ""
$regulartaskcount=1;
ini_set('include_path', '/home/www/bbc/');
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
<h1>Change Preferenes for dates</h1>
E;
print "<h2>Season $season</h2>";
if($auth==0) print "<p>Sorry, you cannot see this page. You need to be logged in to see this page</p>";
$participant=0;
$isfinished=isfinished($season);
if($auth==1)
{ 
  $pname=$_SESSION['user3'];
  $pid=getplayerid($pname);
  $phase=getphase($season);
  if(isparticipant($season,$pid)) $participant=1;
  if($participant==0) print "<p>Sorry, you are not a participant this season, therefore this page is useless for you</p>";
  if($participant==1 and $isfinished==1) print "<p>This season is over, therefore this page cannot be used. cu next season.</p>";
}
if($auth==1 and $participant==1 and $_GET['action']==1 and $isfinished==-1)
{
  // receive data
  $error=0;
  $round=(int)$_GET['round'];
  $rounds=getschedrounds($season);
  $roundchar=chr(64+$round);
  if($round<1 or $round>$rounds) $error=1;
  if($_POST["submit$round"]!="Submit settings for round $roundchar") $error=1;
  $changecount=0;
  if($error==0)
  {
    $slots=gettimeslots($season,$round);
    $prefs=getschedprefarray($season,$pid,$round);
	$times=getdatesarray($season,$round);
	
	$count3=0;
	for($slot=1;$slot<=$slots;$slot++)
	{
	  if((int)$_POST["r$round"."s$slot"]>2 ) $count3++;
	  if((int)$_POST["r$round"."s$slot"]<1 ) $count3++;
	}
	if($count3 > ($slots-1)/2) $error=2;
	$changetext="<ul>";
    for($slot=1;$slot<=$slots and $error==0;$slot++)
    {
      $oldpref=(int)$prefs[$slot-1];
      $newpref=(int)$_POST["r$round"."s$slot"];
      if($newpref<1 or $newpref>3) {$error=1;break;}
      if($newpref==$oldpref) continue;
      setschedpref($season,$pid,$round,$slot,$newpref);
      $t=$times[$slot-1];
      $changetext.="<li>We changed your choice for $t <small>($oldpref to $newpref)</small></li>\n";
      $changecount++;
    }
    if($changecount==0) $changetext="<p>You changed nothing</p>";
    else $changetext.="</ul>";
  }
  if($error==1) print "<p><b>There was an error with your input, sorry</b></p>\n";
  if($error==2) print "<p><b>Hey! You took the option &quot; I can't play &quot; too often - not good</b></p>\n";
  if($error==0) print "<p>It looks like you were successful:</p>\n$changetext";
}


if($auth==1 and $participant==1 and $isfinished==-1)
{
  print "<p>Welcome <b>$pname</b>. Here you can change your preferences for the dates and times, on which HUSC can take place</p>\n";
  $rounds=getschedrounds($season);
  $error=0;
  for($round=1;$round<=$rounds;$round++)
  {
    $roundchar=chr(64+$round);
    $slots=gettimeslots($season,$round);
	$prefs=getschedprefarray($season,$pid,$round);
	$times=getdatesarray($season,$round);
	if(count($times)!=$slots or count($prefs)!=$slots) {$error=1;break;}
	$max3=($slots-1)/2;
	$plural="s";
	if($max3==1) $plural="";
	print <<<E
	<h3>Round $roundchar</h3>
	<form action="husc/editsettings.php?s=$season&action=1&round=$round" method="post">
	<p><b>Important:</b> The option &quot;i can't play&quot; can be chosen at most <b>$max3</b> time$plural</p>
	<table border=1>
	<tr><th>Date</th><th colspan=3>Preference</th></tr>
	<tr><td></td><th>Good</th><th>Ok</th><th>I can't play</th></tr>
E;
    for($slot=1;$slot<=$slots;$slot++)
    {
      print "<tr><td>";
      print $times[$slot-1];
      $cc=array("","","","");
      $cc[$prefs[$slot-1]]="checked";
      $rn="r$round"."s$slot";
      print <<<E
      </td>
      <td><input type="radio" name="$rn" value=1 $cc[1]></td>
      <td><input type="radio" name="$rn" value=2 $cc[2]></td>
      <td><input type="radio" name="$rn" value=3 $cc[3]></td>
      </tr>
E;
      
    }
    print <<<E
    </table>
    <input type="submit" name="submit$round" value="Submit settings for round $roundchar">
    </form>
E;
  }
}
?>

<?php
include "footer1.php";
?>

</body>
</html>