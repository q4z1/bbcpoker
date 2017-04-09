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
include "exp5/nav1.php";
?>
<h1>Changelog of Tickets</h1>
<?php




$actiontext = array(3=>"wins a ticket to Step2 in ", 4=>"wins a ticket (but had already 10) in ",
5 => "loses a ticket for playing " , 6=>"keeps ticket because of Place 3 in ",
8=>"loses ticket, was reserved but missing in ",9=>"wins a ticket to Step3 in ",
14=>"wins a ticket to Step4 in ",
21=>"gets a ticket to Step2 by admin. Reason:",22=>"gets a ticket to Step3 by admin. Reason: ",
23=>"loses a ticket to Step2 by admin. Reason:",24=>"loses a ticket to Step3 by admin. Reason: ",
25=>"gets new tickets to Step2 by admin. Reason: ",26=>"gets new tickets to Step3 by admin. Reason:",
27=>"gets new ammount of tickets to Step2 by automated input. ",28=>"gets new ammount of tickets to Step3 by automated input. ",
29=>"gets new tickets to Step4 by admin. Reason: ",30=>"loses a ticket to Step3 by admin. Reason:");
$shorttext = array(3=>"[Ts2+1]",4=>"[Ts2+0]",5=>"[Ts2-1]",6=>"[Ts2+0]",7=>"[Ts2-1]",
8=>"[Ts2-1]",9=>"[Ts3+1]",10=>"[Ts3+0]",11=>"[Ts3-1]",12=>"[Ts3-1]",13=>"[Ts3+0]",
14=>"[Ts4+1]",15=>"[Ts4+0]",16=>"[Ts4-1]",17=>"[Ts4-1]",
21=>"[Ts2+1]",22=>"[Ts3+1]",23=>"[Ts2-1]",24=>"[Ts3-1]",
29=>"[Ts4+1]",30=>"[Ts4-1]");


$actiontext[7] = $actiontext[5];
$actiontext[11] = $actiontext[5];
$actiontext[12] = $actiontext[8];
$actiontext[10] = $actiontext[4];
$actiontext[13] = $actiontext[6];
$actiontext[15] = $actiontext[4];
$actiontext[16] = $actiontext[5];
$actiontext[17] = $actiontext[8];

$trans = array();
require_once $_SERVER['DOCUMENT_ROOT'].'/defines.php';
chdir(ROOT_DIR);


$page=(int)$_GET['page'];
if($page<=0) $page=1;
$showall=(int)$_GET['showall'];


if(file_exists("exp4/ticketlog1.txt"))
{
	$text = file_get_contents("exp4/ticketlog1.txt");
	
	$rows = explode("\n",$text);
	$i1 = count($rows);
	
	$firstpage=80;
	$pages=20;
	$pagesize=($i1-120)/($pages-1);
	
	$start=$i1-1;
	$end=$i1-$firstpage-1;
	if($page>1)
	{
		$start=$i1-$firstpage-($page-2)*$pagesize+7; //the 8 is tolerance, it leads to overlapping of pages
		$end = $i1-$firstpage-($page-1)*$pagesize-7; //the 8 is tolerance. dont worry about the lower bound 0
	}
	if($showall==1)
	{	
		$start=$i1-1;
		$end=0;
	}
	$pps="<p>Pages:\n";
	for($i2=1;$i2<=$pages;$i2++)
	{
		if($page==$i2 and $showall!=1) $pps.="$i2\n";
		else $pps.="<a href=\"exp4/tickets1.php?page=$i2\">$i2</a>\n";
	}
	if($showall==1) $pps.="show all";
	else $pps.="<a href=\"exp4/tickets1.php?showall=1\">show all</a></p>";
	
	print <<<E
$pps
<table>
<colgroup>
<col>
<col>
<col>
<col>
<col>
<col>
</colgroup>
<tr>
<td>Date/Time</td>
<td></td>
<th>Player</th>
<td>Action</td>
<td>Game</td>
<td>Result</td>
</tr>
E;

	
	
	
	for($i2=$start;$i2>=0 and $i2>=$end;$i2--) 
	{
		$infos = explode("#",$rows[$i2]);
		$timetext=date("Y-m-d H:i:s", (int)$infos[0]);
		$pid = $infos[2];
		if($pid<1025) continue;
		if($trans[$pid]=="")
		{
			$request2 = "SELECT name FROM table2 WHERE playerid='$pid'";
			$result2 = mysql_query($request2);
			while($row2 = mysql_fetch_object($result2)) $trans[$pid]=$row2->name;
		}
		$gtext="-";
		$ai = $infos[1];
		if($ai>2 and $ai<5) $gtext = "BBC $infos[4] Step1. ";
		if($ai>4 and $ai<11) $gtext = "BBC $infos[4] Step2. ";
		if($ai>10 and $ai<16) $gtext = "BBC $infos[4] Step3. ";
		if($ai>15 and $ai<19) $gtext = "BBC $infos[4] Step4. ";
		if(($ai>20 and $ai<27) or $ai==29 or $ai==30) $gtext = $infos[4];
		$newval=$infos[3];
		$ticketword = "tickets";
		if($newval ==1)$ticketword = "ticket";
		$rtext = "<small>She/He has now </small><b>$newval</b><small> $ticketword to </small>Step2.";
		if(($ai>8 and $ai<14)or $ai==22 or $ai==24 or $ai==26 or $ai==28)
			$rtext = "<small>He/She has now </small><b>$newval</b><small> $ticketword to</small> Step3.";
		if(($ai>13 and $ai<19) or $ai==29 or $ai==30)
			$rtext = "<small>He/She has now </small><b>$newval</b><small> $ticketword to</small> Step4.";
		$stext= $shorttext[$ai];
		if($ai==25 or $ai == 27) $stext = "[Ts2=$newval]";
		if($ai==26 or $ai == 28) $stext = "[Ts3=$newval]";
		
		print "<tr><td><small>$timetext</small></td><td>" . $stext;
		print "</td><td>" . $trans[$pid]. "</td>";
		if($ai==27 or $ai==28) print "<td colspan=2><small>" . $actiontext[$ai] . "</small>";
		else print "<td><small>" . $actiontext[$ai] ."</small></td><td>" . $gtext ;
		print "</td><td>" . $rtext;
		print "</td></tr>\n";
	}
	
	print "</table>";
	print $pps;
	
}
else
{
	print "<p>Sorry, we did not found any entries here :( </p>";
}
?>
<?php
include "footer1.php";
?>

</body>
</html>
