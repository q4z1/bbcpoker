<!DOCTYPE html>
<html>
<?php 

$x = (int)$_GET['x'];

$wait = (int)$_GET['w'];

if($wait<=0 or $wait>100) $wait=1;
$url = $_GET['url'];
if($url=="" and $x==0) $x=1;
if($x>0)
{
	$found=0;
	if($x>1000 and $x<4000)
	{
		$gameno=0;
		$step=0;
		if($x<2000) $step=1;
		if($x>1999 and $x<3000) $step=2;
		if($x>2999 and $x<4000) $step=3;
		$gameno=$x-1000*$step;
		$url = "http://bbcpoker.bplaced.net/logfiles/BBC$gameno" . "Step$step.html";
		$found=1;
	}
	
	$file = fopen("url.txt","r");
	
	while(1)
	{
		$row = fgets($file);
		if($row===false)break;
		if($x==(int)explode(":",$row,2)[0])
		{
			$url = explode(":",$row,2)[1];
			$found=1;
			break;
		}
	}
	if($found==0) $url="bbcpoker.bplaced.net/exp2/ranking1.php";
}
if(explode("://",$url)[0]!="http" and explode("://",$url)[0]!="https")
{
	$url = "http://" . $url;
}

?>
<head>
<title>links</title>
  <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<?php  print "<meta http-equiv=\"refresh\" content=\"$wait; URL=$url\"> ";?>
</head>



<?php

ini_set('include_path', '/home/www/bbc/');
//include "head.php";
$regulartaskcount=1;
include "exp2/regulartasks.php"; ?>
<body>
<?php
print "<p>You will be redirected to <a href=\"$url\">$url</a> in $wait Seonds</p>";
?>

</body>
</html>