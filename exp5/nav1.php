<?php
//this file is include only
/*mysql_connect("localhost","bbcpoker","baguette")or die ("Internal MYSQL-ERROR");
mysql_select_db("bbcpoker") or die ("The Database does not exist");
*/

if($p=="")
{
	$url=$_SERVER['REQUEST_URI'];
	$p = explode("/",$url);
	$p = $p[count($p)-1];
	$p = explode("." ,$p)[0];
}
$current1=0;
if($p=="index")$current1=1;
if($p=="")$current1=1;
if($p=="reg1")$current1=3;
if($p=="reg2")$current1=3;
if($p=="reg3")$current1=3;
if($p=="reg5")$current1=3;
if($p=="tickets1")$current1=5;
if($p=="tickets2")$current1=5;
if($p=="report1")$current1=4;
if($p=="gameslist2")$current1=4;
if($p=="gameslist3")$current1=4;
if($p=="sstats1")$current1=6;
if($p=="rating6")$current1=6;
if($p=="ranking1")$current1=6;
if($p=="ranking8")$current1=6;
if($p=="ranking9")$current1=6;
if($p=="ranking10")$current1=6;
if($p=="formula1")$current1=6;
if($p=="index1")$current1=8;
if($p=="shoutbox1")$current1=9;
if($p=="description")$current1=2;
if($p=="bbcbotmanual")$current1=2;
if($p=="players1")$current1=6;
if($p=="admin1")$current1=2;
if($p=="coadmin1")$current1=2;
if($p=="schedule")$current1=2;
if($p=="admin-manual")$current1=2;
if($p=="event1")$current1=7;
if($p=="event2")$current1=7;
if($p=="event3")$current1=7;
if($p=="event4")$current1=7;
if($p=="event5")$current1=7;
if($p=="event6")$current1=7;
if($p=="event7")$current1=7;
if($p=="event8")$current1=7;
if($p=="results")$current1=7;

$sbadmin = '';
if(!isset($_SESSION)) session_start();
if(isset($_SESSION['user3']) && $_SESSION['user3'] == 'sp0ck'){
	$sbadmin = "?admin=1";
}

$now=(int)time();
$now= (1<<17) & $now;
if($now==0)$chorfb="Shoutbox";
else $chorfb="Messages";
$t=array();
$t[$current1]=" class=\"current\"";
print <<<EOF
<nav class="nav">
<ul>
	<li$t[1]><a href="index.php">Home</a></li>
	<li$t[2]><a href="exp4/description.php">Description</a></li>
	<li$t[3]><a href="exp5/reg3.php">Registration</a></li>
	<li$t[4]><a href="exp5/report1.php">Results</a></li>
	<li$t[5]><a href="exp4/tickets2.php">Tickets</a></li>
	<li$t[6]><a href="exp6/rating6.php?g=30">Rankings</a></li>
	<li$t[7]><a href="exp1/event8.php">Events</a></li>
	<li$t[8]><a href="husc/index1.php">HUSC</a></li>
	<li$t[9]><a href="exp4/shoutbox1.php$sbadmin">$chorfb</a></li>
</ul>
</nav>
EOF;

$current2=0;
if($p=="reg3") $current2=1;
if($p=="reg3" and $_GET['s']==2) $current2=2;
if($p=="reg3" and $_GET['s']==3) $current2=3;
if($p=="reg3" and $_GET['s']==4) $current2=4;
if($p=="reg1") $current2=5;
if($p=="reg5") $current2=6;
if($p=="report1") $current2=1;
if($p=="gameslist2") $current2=2;
if($p=="gameslist2" and $_GET['step']==2) $current2=3;
if($p=="gameslist2" and $_GET['step']==3) $current2=4;
if($p=="gameslist2" and $_GET['step']==4) $current2=5;
if($p=="gameslist3") $current2=6;
if($p=="sstats1") $current2=7;
if($p=="tickets2") $current2=1;
if($p=="tickets2" and $_GET['sort']==2) $current2=1;
if($p=="tickets2" and $_GET['sort']==3) $current2=2;
if($p=="tickets2" and $_GET['sort']==4) $current2=1;
if($p=="tickets2" and $_GET['sort']==5) $current2=3;
if($p=="tickets1" ) $current2=4;
if($p=="rating6")$current2=1;
if($p=="ranking1")$current2=2;
if($p=="ranking8")$current2=3;
if($p=="ranking9")$current2=4;
if($p=="ranking10")$current2=5;
if($p=="formula1")$current2=6;
if($p=="players1")$current2=7;
if($p=="description")$current2=1;
if($p=="schedule")$current2=2;
if($p=="admin1")$current2=3;
if($p=="coadmin1")$current2=4;
if($p=="admin-manual")$current2=5;
if($p=="bbcbotmanual")$current2=6;
if($p=="event1")$current2=9;
if($p=="event2")$current2=8;
if($p=="event3")$current2=7;
if($p=="event4")$current2=6;
if($p=="event5")$current2=5;
if($p=="event6")$current2=4;
if($p=="event7")$current2=3;
if($p=="event8")$current2=2;
if($p=="results")$current2=1;

$t=array("","","","","","","");
$t[$current2]=" class=\"current\"";
if($current1==2)
{
	print <<<EOF
<nav class="nav">
<ul>
	<li$t[1]><a href="exp4/description.php">Description and Settings</a></li>
	<li$t[2]><a href="exp4/schedule.php">Schedule</a></li>
	<li$t[3]><a href="exp4/admin1.php">Admins</a></li>
	<li$t[4]><a href="exp1/coadmin1.php">Co-Admins</a></li>
	<li$t[5]><a href="exp4/admin-manual.php">Admin-Manual</a></li>
	<li$t[6]><a href="exp3/bbcbotmanual.php">bbcbot</a></li>
</ul>
</nav>
EOF;
}if($current1==6)
{
	print <<<EOF
<nav class="nav">
<ul>
	<li$t[1]><a href="exp6/rating6.php?g=30">Rating</a></li>
	<li$t[2]><a href="exp2/ranking1.php">Main Ranking</a></li>
	<li$t[3]><a href="exp2/ranking8.php">Detailed All-time</a></li>
	<li$t[4]><a href="exp2/ranking9.php">Detailed Current Season</a></li>
	<li$t[5]><a href="exp2/ranking10.php">ROI Ranking</a></li>
	<li$t[6]><a href="exp4/formula1.php">Explanation</a></li>
	<li$t[7]><a href="exp5/players1.php">Player</a></li>
	<li$t[8]><a href="exp5/sstats1.php">Old Seasons</a></li>
</ul>
</nav>
EOF;
}
if($current1==3)
{
	print <<<EOF
<nav class="nav">
<ul>
	<li$t[1]><a href="exp5/reg3.php">Step 1 Tables</a></li>
	<li$t[2]><a href="exp5/reg3.php?s=2">Step 2 Tables</a></li>
	<li$t[3]><a href="exp5/reg3.php?s=3">Step 3 Tables</a></li>
	<li$t[4]><a href="exp5/reg3.php?s=4">Step 4 Tables</a></li>
	<li$t[5]><a href="exp5/reg1.php">Register yourself</a></li>
	<li$t[6]><a href="exp5/reg5.php">Deregistration</a></li>
</ul>
</nav>
EOF;
}
if($current1==4)
{
	print <<<EOF
<nav class="nav">
<ul>
	<li$t[1]><a href="exp5/report1.php">Recent Games</a></li>
	<li$t[2]><a href="exp2/gameslist2.php?step=1">Step 1 Games</a></li>
	<li$t[3]><a href="exp2/gameslist2.php?step=2">Step 2 Games</a></li>
	<li$t[4]><a href="exp2/gameslist2.php?step=3">Step 3 Games</a></li>
	<li$t[5]><a href="exp2/gameslist2.php?step=4">Step 4 Games</a></li>
	<li$t[6]><a href="exp5/gameslist3.php">Details</a></li>
	
</ul>
</nav>
EOF;
}
if($current1==5)
{
	print <<<EOF
<nav class="nav">
<ul>
	<li$t[1]><a href="exp4/tickets2.php?sort=1">Sort by Names, Ts2, T3 or T4 </a></li>
	<li$t[2]><a href="exp4/tickets2.php?sort=3">Only Ts3 Players</a></li>
	<li$t[3]><a href="exp4/tickets2.php?sort=5">Only Ts4 Players</a></li>
	<li$t[4]><a href="exp4/tickets1.php">Tickets Changelog</a></li>
</ul>
</nav>
EOF;
}

$current3=0;
if($p=="reg3")$current3=1;
if($p=="reg3" and $_GET['g']>0) $current3=(int)$_GET['g'];
if($p=="reg3" and $current2==1 and $current3>5) $current3=1;
if($p=="reg3" and $current2==2 and $current3>3) $current3=1;
if($p=="reg3" and $current2==3 and $current3>2) $current3=1;



if($current1==7)
{
	print <<<EOF
<nav class="nav">
<ul>
	<li$t[1]><a href="exp1/results.php">Results</a></li>
  <li$t[2]><a href="exp1/event8.php">BBC 5000 Cup</a></li>
	<li$t[3]><a href="exp1/event7.php">Valentine's Day Cup</a></li>
	<li$t[4]><a href="exp3/event6.php">Hello World</a></li>
	<li$t[5]><a href="exp3/event5.php">3000 Games</a></li>
	<li$t[6]><a href="exp1/event4.php">New Year Cup</a></li>
	<li$t[7]><a href="exp3/event3.php">Nelly's birthday</a></li>
	<li$t[8]><a href="exp1/event2.php">THousandTH</a></li>
	<li$t[9]><a href="exp1/event1.php">sp0ck surprise</a></li>
</ul>
</nav>
EOF;
}

/*
$t=array();
$t[$current3]=" class=\"current\"";

if($current1==3 and $current2==1)
{
	print <<<EOF
<nav class="nav">
<ul>
	<li$t[1]><a href="exp5/reg3.php?s=1&g=1">Next Game</a></li>
	<li$t[2]><a href="exp5/reg3.php?s=1&g=2">Last Game</a></li>
	<li$t[3]><a href="exp5/reg3.php?s=1&g=3">Older Game</a></li>
	<li$t[4]><a href="exp5/reg3.php?s=1&g=4">Older Game</a></li>
	<li$t[5]><a href="exp5/reg3.php?s=1&g=5">Older Game</a></li>
</ul>
</nav>
EOF;
}
if($current1==3 and $current2==2)
{
	print <<<EOF
<nav class="nav">
<ul>
	<li$t[1]><a href="exp5/reg3.php?s=2&g=1">Next Game</a></li>
	<li$t[2]><a href="exp5/reg3.php?s=2&g=2">Last Game</a></li>
	<li$t[3]><a href="exp5/reg3.php?s=2&g=3">Older Game</a></li>
</ul>
</nav>
EOF;
}if($current1==3 and $current2==3)
{
	print <<<EOF
<nav class="nav">
<ul>
	<li$t[1]><a href="exp5/reg3.php?s=3&g=1">Next Game</a></li>
	<li$t[2]><a href="exp5/reg3.php?s=3&g=2">Last Game</a></li>
</ul>
</nav>
EOF;
}*/
?>
