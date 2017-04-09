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
<h1>TODO - VIEW LOG FILE</h1>

<p>You can choose one of the following filters:<br>
<a href="husc/log2.php?s=$season&filter=raw">raw</a> 
<a href="husc/log2.php?s=$season&filter=result">results</a> 
<a href="husc/log2.php?s=$season&filter=pref">preferences</a> 
<a href="husc/log2.php?s=$season&filter=reg">registrations</a> 


</p>
E;

$filter=$_GET['filter'];
if($filter=="") $filter="result";
if($filter=="results") $filter="result";
if($filter !="raw" and $filter!="result" and $filter!="pref" and $filter!="reg") $filter="wrong";



$data1=file_get_contents("husc/s$season/actionlog.txt");
$data2=explode("\n",$data1);
$c2=count($data2);
$reverse=0;
if($_GET['reverse']=="1") $reverse=1;

if($filter=="raw")
{
  print "<p>The selected filter is &quot;raw&quot;</p>";
  print "<table>";
  for($i1=0;$i1<$c2;$i1++)
  {
    $i2=$i1;
    if($reverse==1) $i2=$c2-$i1-1;
    $data3=explode(" ",$data2[$i2]);
    if(count($data3)!=7) continue;
    print "<tr>";
    for($i2=0;$i2<7;$i2++)
    {  
      print "<td>$data3[$i2]</td>";
    }
    print "</tr>\n";
  }
print "</table>";
}
if($filter=="result")
{
  $pcache=array();
  print "<p>The selected filter is &quot;results&quot;</p>\n<table border=1>";
  print "<tr><td>changed by</td><th>date</th><td>time</td><th>round</th>";
  print "<th>Player</th><th>old result</th><th>new result</th></tr>";
  for($i1=0;$i1<$c2;$i1++)
  {
    $i2=$i1;
    if($reverse==1) $i2=$c2-$i1-1;
    $data3=explode(" ",$data2[$i2]);
    if(count($data3)!=7) continue;
    $aid=(int)$data3[0];
    if($aid<302 or $aid>304) continue;
    $pid=(int)$data3[3];
    if($aid==302) $aid2="[player]";
    if($aid==303) $aid2="[admin]";
    if($aid==304) $aid2="[system]";
    
    if($pcache[$pid]=="") $pcache[$pid]=getplayername($pid);
    $round=(int)$data3[4];
    $res1=$data3[5];
    $res2=$data3[6];
    print "<tr><td>$aid2</td><td>$data3[1]</td><td>$data3[2]</td><td>$round</td><td>";
    print $pcache[$pid];
    print "</td><td>$res1</td><td><b>$res2</b></td></tr>";
  }
  print "</table>";
}
if($filter=="pref")
{
  $pcache=array();
  print "<p>The selected filter is &quot;preferences&quot;</p>\n<table border=1>";
  $preftext=array(0,"good","ok","bad");
  print "<tr><th>date</th><td>time</td><th>Player</th><th>time slot</th><td>Preference</td></tr>";
  $srounds=getschedrounds($season);
  $pa1=array();
  for($sr=1;$sr<=$srounds;$sr++)
  {
    $pa1[$sr]=getdatesarray($season,$sr);
  }
  for($i1=0;$i1<$c2;$i1++)
  {
    $i2=$i1;
    if($reverse==1) $i2=$c2-$i1-1;
    $data3=explode(" ",$data2[$i2]);
    if(count($data3)!=7) continue;
    $aid=(int)$data3[0];
    if($aid!=301) continue;
    $pid=(int)$data3[3];
    if($pcache[$pid]=="") $pcache[$pid]=getplayername($pid);
    $round=(int)$data3[4];
    $slot=(int)$data3[5];
    $pref=(int)$data3[6];
    $t1=$pa1[$round][$slot-1];
    print "<tr><td>$data3[1]</td><td>$data3[2]</td><td>";
    print $pcache[$pid];
    print "</td><td>$t1</td><td>";
    print $preftext[$pref];
    print "</td></tr>\n";
  }
  print "</table>";
}
if($filter=="reg")
{
  $pcache=array();
  print "<p>The selected filter is &quot;registrations&quot;</p>\n<table border=1>";
  print "<tr><th>date</th><td>time</td><th>Player</th><td>Status</td></tr>";
  $statusarray=array(0,0,"declined","declined","undecided","undecided","accepted","accepted");
  for($i1=0;$i1<$c2;$i1++)
  {
    $i2=$i1;
    if($reverse==1) $i2=$c2-$i1-1;
    $data3=explode(" ",$data2[$i2]);
    if(count($data3)!=7) continue;
    $aid=(int)$data3[0];
    if($aid!=201 and $aid!=202) continue;
    $pid=(int)$data3[3];
    if($pcache[$pid]=="") $pcache[$pid]=getplayername($pid);
    $st=(int)$data3[4];
    $t1=$statusarray[$st];
    if($aid==201) $t1="registered";
    print "<tr><td>$data3[1]</td><td>$data3[2]</td><td>";
    print $pcache[$pid];
    print "</td><td>$t1</td></tr>\n";
  }
  print "</table>\n";

}

if($filter=="wrong") print "<p>There was an error with the selection of filters</p>";


?>

<?php
include "footer1.php";
?>

</body>
</html>