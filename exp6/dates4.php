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
$errmsg="";

$del=$_POST["del"];
if(substr($del,0,1)!="s") $errmsg="wrong input.. sorry, strange problem (1)";
$step=(int)substr($del,1,1);
if($step<1 or $step>3) $errmsg="sorry, only step1-step3 are ok here";
if(substr($del,2,1)!="t") $errmsg="wrong input.. sorry, strange problem (1)";
$unixt=substr($del,3);

$timetext=date("Y-m-d H:i:s",$unixt);


$request="DELETE FROM dates WHERE step=$step AND date='$timetext'";
if($errmsg=="") $result=mysql_query($request);


print <<<E
<h1>Control Dates of BBC games</h1>
<p>Hello admin! you wanted to delete the game at
$timetext
for Step $step.
</p>

E;
if($errmsg=="") print "<p><b>Success</b>! it looks like your request worked</p>";
else print "<p><b>ERROR</b>".$errmsg."</p>";

print <<<E
<p>If you want to see a list of planned games
<a href="/exp6/dates3.php?d=0">go here</a>.</p>
E;



} // end Admin part
else
print "<p>Hello, if you are an admin, you could visit that page: <a href=\"/login.php\">(click here)</a></p>";


?>
<?php
include "footer1.php";
?>

</body>
</html>
