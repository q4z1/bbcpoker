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

?>

<?php

$print1=<<<E
<h1>HUSC main page</h1>
<p>HUSC is the <b>H</b>eads <b>U</b>p <b>S</b>urvival <b>C</b>up. It is part of the BBC community, but a seperate cup</p>
<p>To know more about HUSC and how it works exactly, you might want to <a href="husc/rules1.php">visit our Rules Page</a></p>
<p>Here is an Overview about Past and future HUSC seasons:</p>
E;

function seasonrelevance($s1) // returns a number, hihger means more relevant
{
  if(!file_exists("husc/s$s1/status.txt")) return 1000-$s1;
  $t1=file_get_contents("husc/s$s1/status.txt");
  if(substr($t1,0,5)=="phase")
  {
    $phase=(int)substr($t1,5);
    if($phase==0) return 2500-$s1;
    if($phase==1) return 3500-$s1;
    if($phase==2) return 9500-$s1;
    if($phase==3) return 8500-$s1;
    if($phase==4) return 10500-$s1;
    if($phase==5) return 11500-$s1;
    if($phase==6) return 12500-$s1;  
  }
  if(substr($t1,0,5)=="round")
  {
    $round=(int)substr($t1,4);
    return 1000*$round-$s1+100000;
  }
  if(substr($t1,0,8)=="finished") return 4500+$s1;
  return 1000-$s1;
}

function insertionsort($index,$data=0) // returns a new index, sorted by data[$i], highest with index 0
{
  if($data==0)
  {
    $data=$index;
    for($i1=0;$i1<count($index);$i1++)
    {
      $data[$index[$i1]]=seasonrelevance($index[$i1]);
    }
  }
  if(count($index) != count($data)) return "ERRRRRROR";
  $c=count($index);
  for($i1=0;$i1<$c;$i1++)
  {
    // pick element
    $val1=$index[$i1];
    $val2=$data[$i1];
    for($i2=$i1-1;$i2>=0;$i2--)
    {
      if($data[$i2]>=$val2)
      {
        $index[$i2+1]=$val1;
        $data[$i2+1]=$val2;
        break;
      }
      $data[$i2+1]=$data[$i2];
      $index[$i2+1]=$index[$i2];
    }
    if($i2==-1)
    {
      $data[0]=$val2;
      $index[0]=$val1;
    }
  }
  return $index;
}


$season=1;
$noseason=0;
$index=array();
$data=array();

while($noseason<8)
{
  $t1=seasonrelevance($season);
  if($t1<1001) 
  {
    $noseason++;
    $season++;
    continue;
  }
  $noseason=0;
  $error=0;
  $index[]=$season;
  $data[]=$t1;
  $season++;
 
}

$sort=insertionsort($index,$data);
$file=fopen("husc/relevantseason.txt","w");
fwrite($file,"$sort[0]");
fclose($file);

include "husc/huscnav1.php";
print $print1;

$c=count($sort);

for($i1=0;$i1<$c;$i1++)
{
  $season=$sort[$i1];
  $t1=seasonrelevance($season);
  print "<h3>Season $season</h3>\n";
  if($t1<3502 and $t1 >1035) print "<p>This HUSC season is in preperation.</p>\n";
  if($t1>5501 and $t1 <9509) print "<p><a href=\"husc/reg11.php?s=$season\">Register for this season</a></p>\n";
  if($t1>9507 and $t1 < 50000) print "<p>This HUSC season will soon start - make sure to 
  <a href=\"husc/editsettings.php?s=$season\">change your settings</a></p>\n";
  if($t1>50000) print "<p>This HUSC season is running - <a href=\"husc/viewgames.php?s=$season\">view results</a></p>";
  if($t1<5701 and $t1>3501) print "<p>This HUSC season is finished - <a href=\"husc/viewgames.php?s=$season\">view results</a></p>"; 
}


?>

<?php
include "footer1.php";
?>

</body>
</html>