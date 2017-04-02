<?php
header('Content-Type: text/plain');
ini_set('include_path', '/home/www/bbc/');
$regulartaskcount=1;
include "exp2/regulartasks.php";


//$text = date("Y-m-d_H:i:s",$timestamp);
//$file2 = fopen("exp2/backup$text","w");//create new output file
$request = "SELECT * FROM table2";//ask for everything in db
$result = mysql_query($request);
while($row = mysql_fetch_array($result))
{
	$text = "";
	for($i=0;$i<17;$i++) $text = $text . "#*#$row[$i]";
	$text = $text . "\n";
	print $text;
	//fwrite($file2,$text);
}
//fwrite($file2,"####****####\n");
print "####****####\n";
$request = "SELECT * FROM table1";//ask for everything in db
$result = mysql_query($request);
while($row=mysql_fetch_object($result))
{
	$id = $row->id;
	$step = $row->step;
	$gameno = $row->gameno;
	$datetime = $row->datetime;
	$season = $row->season;
	$p1 = $row->p1;
	$p2 = $row->p2;
	$p3 = $row->p3;
	$p4 = $row->p4;
	$p5 = $row->p5;
	$p6 = $row->p6;
	$p7 = $row->p7;
	$p8 = $row->p8;
	$p9 = $row->p9;
	$p10 = $row->p10;
	$text = "$id#*#$step#*#$gameno#*#$season#*#$datetime#*#$p1#*#";
	$text = $text . "$p2#*#$p3#*#$p4#*#$p5#*#$p6#*#$p7#*#$p8#*#$p9#*#$p10\n";
	//fwrite($file2,$text);
	print $text;
}
//fclose($file2);

?>