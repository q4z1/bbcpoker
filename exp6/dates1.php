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

$exampledate=date("Y-m-d",time()+7*86400);


print <<<E
<h1>Control Dates of BBC games</h1>
<p>Dear Admin, only use the following stuff if you know what you are doing.
Consider talking to other admins to avoid chaos.
Together, you can make better decisions.
You can create dates for BBC Step 1,2,3 here if you want.
Please note that the specified time is for the BBC Time Zone
(Central Europe, Paris, Berlin, CET/CEST).
</p>
<p>
Before you create dates, you should take a look at the 
<a href="/exp6/dates3.php?d=0">games that are already planned</a>
</p>

<form action="/exp6/dates2.php" enctype="multipart/form-data" method="post">
<p>
<input type="radio" name="step" value="1">Step 1<br>
<input type="radio" name="step" value="2">Step 2<br>
<input type="radio" name="step" value="3">Step 3<br>
Game start, Date:  <input type="Text" name="date" value="$exampledate" maxlength=10 size=10>
Time: <input type="Text" name="time" value="23:59:00" maxlength=8 size=8>
</p>
<p>

<input type="submit" value="Create BBC Date!">
</form>

E;
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
