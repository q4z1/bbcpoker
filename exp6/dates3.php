<?php
$auth1=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc']==2 )
    $auth1 = 1;
} //$_COOKIE['PHPSESSID'] != ""


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


<?php
if($auth1==1)
{

$deletemode=(int)$_GET["d"];
if($deletemode!=1) $deletemode=0;
$d=$deletemode;


print <<<E
<h1>Control Dates of BBC games</h1>
<p>Dear Admin, this is a list of all planned bbc games for step 1,2, and 3.
You can create new dates 
<a href="/exp6/dates1.php">here</a>
The time zone in the following table will be the BBC time zone
(Central Europe, Paris, Berlin, CET/CEST).
Most of the games will be auto-generated 40-45 days in advance.
Deletion of games is only possible in the next 35 days.
</p>

E;

if($d==0) print "<p>You are in normal mode. you can just see a list of planned games</p>";
if($d==0) print "<p><a href=\"/exp6/dates3.php?d=1\">Go to Delete Mode - Delete Dates!</a></p>\n";
if($d==1) print "<p>You are in \"Delete Mode\", be careful what you are doing, because you
have the power to delete dates here!.</p>
<p><a href=\"/exp6/dates3.php?d=0\">Go to normal mode</a></p>

<form method=\"post\" action=\"exp6/dates4.php\">
";


print "<table border=1>
<tr><th>Step</th><th>Start Time/Date</th>
";
if($d==1) print "<td>Delete?</td>";
print "</tr>\n";

$nowt=date("Y-m-d H:i:s");
$request="SELECT * FROM dates WHERE date>'$nowt' and step<4";
$result=mysql_query($request);
while($row=mysql_fetch_object($result))
{
  $dt=$row->date;
  $unixt=strtotime($dt);
  $step=$row->step;
  $timetext=date("D, d M Y H:i T",strtotime($dt));
  print "<tr><td>$step</td><td>$timetext</td>";
  if($d==1 and $unixt<time()+35*86400) print "<td><input type=\"radio\" name=\"del\" 
  value=\"s$step"."t$unixt\"></td>";
  if($d==1 and $unixt>=time()+35*86400) print "<td></td>";
  print "</tr>\n";
}

print "</table>";

if($d==1)
print "<br><input type=\"submit\" value=\"Delete Game\">
</form>";

// blockedgamenumbers


} // end Admin part
else
print "<p>Hello, if you are an admin, you could visit that page: <a href=\"/login.php\">(click here)</a></p>";


?>
<?php
include "footer1.php";
?>

</body>
</html>
