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

<h2>Awards input</h2>

<?php
$error=0;
$errorinfo1="none";
if($userpass=="nelly" or $userpass=="supernoob")
{
	$finfo=$_FILES['pic'];
	if($finfo['error']!=0) $error=502;
	if($finfo['size']<1000 or $finfo['size']>200000) $error=503;
	$player=$_POST['player'];
	$descr=$_POST['descr'];
	$request=$request = "SELECT playerid FROM table2 WHERE name LIKE '$player'";
	$c=0;
	$result = mysql_query($request)
		OR die("Error: $request <br>".mysql_error());
	while($row = mysql_fetch_object($result))
	{
		$c++;
		$pid=$row->playerid;
	}
	if($c==0) {$error=504;$errorinfo1=$player;}
	if($error==0 and $pid<1024) {$error=505;$errorinfo1=$player;}
	
	$fname1=basename($_FILES['pic']['name']);
	$temp=explode(".",$fname1);
	$ftype=strtolower(trim($temp[count($temp)-1]));
	if($error=0 and $ftype!="jpg" and $ftype!="png" and $ftype!="gif" and $ftype!="jpeg") $error=506;
	$errorinfo1=$fname1;
	$filenumber=-1;
	for($i1=1;$i1<200;$i1++)
	{
		if(file_exists("exp5/awards/pics/award$i1.$ftype")) continue;
		$filenumber=$i1;
		break;
	}
	if($filenumber==-1) $error=507;
	$fname3="award$filenumber.$ftype";
	$fname2="exp5/awards/pics/$fname3";

}
else $error=501;

if($error==0 and !move_uploaded_file($_FILES['pic']['tmp_name'],$fname2)) $error=508;

if($error==0)
{
	$fcontent=file_get_contents("exp5/awards/awardsdata.txt");
	$newrow= "\n$pid##$fname3##$descr";
	$fileh=fopen("exp5/awards/awardsdata.txt","w");
	fwrite($fileh,$fcontent . $newrow);
	fclose($fileh);
	$request="UPDATE table2 SET settings=1 WHERE playerid=$pid";
	$result=mysql_query($request);
	
	
	print "<p>It looks like there was no error!</p>";
}
else include("exp2/error.php");


?>



<?php
include "footer1.php";
?>
</body>
</html>
