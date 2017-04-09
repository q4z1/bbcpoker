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
<h1>View HUSC Ranking</h1>
E;
$phase=getphase($season);
$round=getround($season)-1;
$maxround=$round;
$isfinished=-1;
if($maxround==-2) $isfinished=isfinished($season);
if(isset($_GET['r'])) $round=(int)$_GET['r'];
if($isfinished==1) 
{
  $maxround=getfinishedround($season);
  if($round<0) $round=$maxround;
}

if($round>=0) print "<h2>Season $season - Round $round</h2>";
if($round<0) print "<h2>Season $season</h2>";
if($round<0) print "<p>There is no ranking to see, sorry.</p>";
$rr=twodigit($round);
$fn1="husc/s$season/ranking$rr.txt";
$exist=0;
if(file_exists($fn1)) $exist=1;
if($round>=0 and $exist==0) print "<p>Sorry, we could not find data for round $round.</p>";
if($round>=0)
{
  print "<p>You can also look at one of the followinging rounds:<br>\n";
  for($i1=0;$i1<=$maxround;$i1++)
  {
    print " <a href=\"husc/viewranking.php?s=$season&r=$i1\">$i1</a> \n";
  }
  print "</p>";
}
if($round>=0 and $exist==1)
{
  $t1="<table border=1><tr><th>#</th><th>Player</th>";
  $t1.="<th>Lives</th><th>won games</th><td><small>Won played games</small></td>";
  $t1.="<td>C4</td><td>C5</td><td>C6</td><td>C7</td><td>C8</td><td>C9</td><td>C10</td><td>C11</td><td>C12</td><td>Seed</td></tr>\n";
  $data1=file_get_contents($fn1);
  $data2=explode("\n",$data1);
  $c1=count($data2);
  $i1=0;
  $error=0;
  for($i1=0;$i1<$c1;$i1++)
  {
    $data3=explode(" ",$data2[$i1]);
    if(count($data3)!=15) continue;
    $pname=getplayername($data3[1]);
    $t1.="<tr><th>$data3[0]</th><td>$pname</td>";
    for($i2=2;$i2<15;$i2++)
    {
      $t1.="<td>".$data3[$i2]."</td>";
    }
    $t1.="</tr>\n";
  }
  $t1.="</table>\n<p>C3 - C12 are the criteria according to the rules</p>";
  print $t1;
}
?>

<?php
include "footer1.php";
?>

</body>
</html>