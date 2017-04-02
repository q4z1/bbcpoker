<?php
//this file is include only

if($p=="")
{
	$url=$_SERVER['REQUEST_URI'];
	$p = explode("/",$url);
	$p = $p[count($p)-1];
	$p = explode("." ,$p)[0];
}


$season=(int)$_GET['s'];
if($season<=0 or !file_exists("husc/s$season/status.txt")) $season=(int)file_get_contents("husc/relevantseason.txt");


print <<<EOF
<nav class="nav">
<ul>
	<li><a href="index.php">Home</a></li>
	<li><a href="exp4/description.php">Description</a></li>
	<li><a href="exp5/reg3.php">Registration</a></li>
	<li><a href="exp5/report1.php">Results</a></li>
	<li><a href="exp4/tickets2.php">Tickets</a></li>
	<li><a href="exp6/rating6.php?g=30">Rankings</a></li>
	<li><a href="exp1/event7.php">Events</a></li>
	<li class="current"><a href="husc/index1.php">HUSC</a></li>
	<li><a href="exp4/shoutbox1.php">Shoutbox</a></li>
</ul>
</nav>
EOF;

$t1=file_get_contents("husc/s$season/status.txt");
$phase=-1;
if(substr($t1,0,5)=="phase")
{
  $phase=(int)substr($t1,5);
}



if($phase>=0 and $phase <4)
{
  if($p=="index1") $current1=1;
  if($p=="rules1") $current1=2;
  if($p=="reg11") $current1=3;
  if($p=="reg13") $current1=4;
  if($p=="log2") $current1=5;
  $t=array();
  $t[$current1]=" class=\"current\" ";
  print <<<E
  <nav class="nav">
  <ul>
  <li$t[1]><a href="husc/index1.php?s=$season">Main</a></li>
  <li$t[2]><a href="husc/rules1.php?s=$season">Rules</a></li>
  <li$t[3]><a href="husc/reg11.php?s=$season">Register</a></li>
  <li$t[4]><a href="husc/reg13.php?s=$season">Players</a></li>
  <li$t[5]><a href="husc/log2.php?s=$season">Logs</a></li>
  </ul>
  </nav>
E;
}
else
{
  if($p=="index1") $current1=1;
  if($p=="rules1") $current1=2;
  if($p=="viewgames") $current1=3;
  if($p=="viewranking") $current1=3;
  if($p=="viewpref") $current1=3;
  if($p=="editresults") $current1=4;
  if($p=="editsettings") $current1=4;
  if($p=="log2") $current1=5;
  $t=array();
  $t[$current1]=" class=\"current\" ";
  print <<<E
  <nav class="nav">
  <ul>
  <li$t[1]><a href="husc/index1.php?s=$season">Main</a></li>
  <li$t[2]><a href="husc/rules1.php?s=$season">Rules</a></li>
  <li$t[3]><a href="husc/viewgames.php?s=$season">Public</a></li>
  <li$t[4]><a href="husc/editresults.php?s=$season">Personal</a></li>
  <li$t[5]><a href="husc/log2.php?s=$season">Logs</a></li>
  </ul>
  </nav>
E;
  if($current1==3)
  {
    if($p=="viewgames") $current2=1;
    if($p=="viewranking") $current2=2;
    if($p=="viewpref") $current2=3;
    $t=array();
    $t[$current2]=" class=\"current\" ";
    print <<<E
    <nav class="nav">
    <ul>
    <li$t[1]><a href="husc/viewgames.php?s=$season">Games</a></li>
    <li$t[2]><a href="husc/viewranking.php?s=$season">Ranking</a></li>
    <li$t[3]><a href="husc/viewpref.php?s=$season">Time Preferences</a></li>
    </ul>
    </nav>
E;
  }
  if($current1==4)
  {
    if($p=="editresults") $current2=1;
    if($p=="editsettings") $current2=2;
    $t=array();
    $t[$current2]=" class=\"current\" ";
    print <<<E
    <nav class="nav">
    <ul>
    <li$t[1]><a href="husc/editresults.php?s=$season">Enter Result</a></li>
    <li$t[2]><a href="husc/editsettings.php?s=$season">Change Time Preferences</a></li>
    </ul>
    </nav>
E;
  }
  
}
// TODO : make menu based on season	- more specified


?>