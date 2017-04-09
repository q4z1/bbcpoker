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
include "husc/huscfun1.php"; // getseason()
$season=getseason();
include "husc/s$season/warning.html";
include "husc/huscnav1.php";

?>

<?php
print <<<E
<h1>View Games</h1>
E;

$phase=getphase($season);
$round=getround($season);
$maxround=$round;
$isfinished=-1;
if($maxround==-1) $isfinished=isfinished($season);
if(isset($_GET['r'])) $round=(int)$_GET['r'];
if($isfinished==1) 
{
  $maxround=getfinishedround($season);
  if($round<=0) $round=$maxround;
}

if($round>0) print "<h2>Season $season - Round $round</h2>";

if($round<=0) print "<h2>Season $season</h2>";
if($round<0) print "<p>There are no played or scheduled games to see, sorry.</p>";
$rr=twodigit($round);
$fn1="husc/s$season/plan$rr.txt";
$fn2="husc/s$season/results$rr.txt";
$exist=0;
$trans1=array();
$trans2=array();
$trans1["1"]="1";
$trans1["0"]="0";
$trans1["+"]="+";
$trans1["-"]="-";
$trans1["?"]="?";
$trans2["1"]="win";
$trans2["0"]="lose";
$trans2["+"]="forfeit win";
$trans2["-"]="forfeit loss";
$trans2["?"]="unknown";
$trans1=$trans2;
if(file_exists($fn1) and file_exists($fn2)) $exist=1;
if($round>=0 and $exist==0) print "<p>Sorry, we could not find data for round $round.</p>";
if($round>=0)
{
  print "<p>You can also look at one of the followinging rounds:<br>\n";
  for($i1=1;$i1<=$maxround;$i1++)
  {
    print " <a href=\"husc/viewgames.php?s=$season&r=$i1\">$i1</a> \n";
  }
  print "</p>";
}
if($round>=0 and $exist==1)
{
  $t1="<table border=1><tr><td>#</td><td>Date and Time</td><th>Player 1</th>";
  $t1.="<th>Player 2</th><th>Result 1</th><th>Result 2</th></tr>\n";
  $data1=file_get_contents($fn1);
  $data2=file_get_contents($fn2);
  $data3=explode("\n",$data1);
  $data4=explode("\n",$data2);
  $c1=count($data3);
  $c2=count($data4);
  $i1=0;
  $i2=0;
  $error=0;
  while($i1<$c1 and $i2<$c2)
  {
    $data5=explode(" ",$data3[$i1]);
    $data6=explode(" ",$data4[$i2]);
    if(count($data5)!=5) {$i1++;continue;}
    if(count($data6)!=5) {$i2++;continue;}
    if($data5[0]!=$data6[0]) $error=1;
    if($data5[1]!=$data6[1]) $error=1;
    if($data5[2]!=$data6[2]) $error=1;
    if($error==1) break;
    $gamet=date("D, d M Y H:i T",strtotime("$data5[3] $data5[4]")); // human time)
    $pname1=getplayername((int)$data5[1]);
    $pname2=getplayername((int)$data5[2]);
    $res1=$trans1[$data6[3]];
    $res2=$trans1[$data6[4]];
    $t1.="<tr><td>$data5[0]</td><td>$gamet</td><td>$pname1</td><td>$pname2</td><td>$res1</td><td>$res2</td></tr>\n";
    $i1++;
    $i2++;
  }
  $t1.="</table><p>The results are mostly entered by the players.</p>\n";
  if($error==0) print $t1;
  else print "<p>There was an error with this page</p>";
}
?>

<?php
include "footer1.php";
?>

</body>
</html>