<?php
$auth1=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc']==2 )
    $auth1 = 1;
} //$_COOKIE['PHPSESSID'] != ""


print '<!DOCTYPE html>
<html>';
ini_set('include_path', '/home/www/bbc/');
include "head.php";
$regulartaskcount=1;
include "exp2/regulartasks.php";
print "<body>";
include "header1.php"; 
include "exp5/nav1.php";

?>


<?php
if($auth1==1)
{
$errmsg="";

$exampledate=date("Y-m-d",time()+7*86400);
$step=(int)$_POST["step"];
$date2=$_POST["date"];
$time2=$_POST["time"];
$temp1=strtotime("$date2 $time2");

/*
$t2 = time();
$limit = 3 * 60 * 60; // 3 hours
if(($temp1-$t2) < $limit){
  $errmsg="The minimum time difference for creating an additional game is 3 hours before the game starts!";
}
*/

if($step<1 or $step>3) $errormsg="Only step 1-3 are allowed";
if($temp1 == false) $errmsg="Could not read time or Date";
if($temp1<time()) $errmsg="Date/Time is in the past - you can only create dates for the future";
$timetext=date("Y-m-d H:i:s",$temp1);


$request="SELECT COUNT(*) FROM dates WHERE step=$step AND date='$timetext'";
$result=mysql_query($request);
$r1=mysql_fetch_array($result);
$r2=$r1[0];
if($r2<1) 
{
  $request = "INSERT INTO dates 
  (date, step, status) 
  VALUES 
  ('$timetext',$step,0)";
  if($errmsg=="") $result=mysql_query($request) or die("ERRRORRR: " . mysql_error());
}
else $errmsg="There was already a game for that date and step";


print <<<E
<h1>Control Dates of BBC games</h1>
<p>Hello admin! you wanted to create a game at
$timetext
for Step $step.
</p>

E;
if($errmsg=="") print "<p><b>Success</b>! it looks like your request worked</p>";
else print "<p><b>ERROR</b>".$errmsg."</p>";

print <<<E
<p>If you want to see a list of planned games
<a href="exp6/dates3.php?d=0">go here</a>.</p>
E;



} // end Admin part
else
print "<p>Hello, if you are an admin, you could visit that page: <a href=\"login.php\">(click here)</a></p>";


?>
<?php
include "footer1.php";
?>

</body>
</html>
