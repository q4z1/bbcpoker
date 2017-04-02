<?php
print '<!DOCTYPE html>
<html>';
ini_set('include_path', '/home/www/bbc/');
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
<h1>View Time Preferences of Players</h1>
E;
$phase=getphase($season);
print "<h2>Season $season</h2>\n"; // TODO: CSS vertical table
$view=0;
if($phase==-1 or $phase>=4) $view=1;
$c1=0;
$tt="";
if($view==1)
{

  $schedrounds=getschedrounds($season);
  $pids=getparticipantsarray($season);
  $c1=count($pids);
  if($c1<1) $view=0;
}
$pa2=array("","good","ok","bad");

for($sround=1;$sround<=$schedrounds and $view==1;$sround++)
{
  $sroundchar=chr(64+$sround);
  $tt.="<h3>Round $sroundchar</h3>\n";
  $tt.="<table border=1>\n";
  $tt1="<tr><td></td>";
  $tt2="<tr><th>Players</th>";
  $times=getdatesarray($season,$sround);
  for($i1=0;$i1<count($times);$i1++)
  {
	$date1=explode(" ",$times[$i1]);
	if(count($date1)!=2) {$view=0;continue;}
    $tt1.="<td>$date1[0]</td>";
    $tt2.="<td>$date1[1]</td>";
  }
  $tt.=$tt1."</tr>\n".$tt2."</tr>\n";
  for($i1=0;$i1<$c1 and $view==1;$i1++)
  {
    $pid=$pids[$i1];
    $prefs=getschedprefarray($season,$pid,$sround);
    $pname = getplayername($pid);
    $tt.="<tr><td>$pname</td>";
    if(count($prefs)!=count($times)) $view=1;
    for($i2=0;$i2<count($prefs);$i2++)
    {
      $tt1=$pa2[$prefs[$i2]];
      $tt.="<td>$tt1</td>";
    }
    $tt.="</tr>\n";
  }
  $tt.="</table>\n";
}

if($view==0) print "<p>Sorry, we didnt found enough data</p><!-- or it was an ERROR--></p>";
if($view==1) print $tt;



?>

<?php
include "footer1.php";
?>

</body>
</html>