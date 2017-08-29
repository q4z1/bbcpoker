<?php
$auth1=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc']==2 or $_SESSION['upc']==3)
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
print <<<E
<h1>Upload Logfile Analysis</h1>
<p>Dear Admin, only use the following stuff if you know what you are doing and have the permissions to do so.
If you have questions, problems, etc. ask!<br>
It would be nice, if you keep the original file on your local Computer</p>
<form action="exp6/upload4.php" enctype="multipart/form-data" method="post">

<p>
Log-Link Url: <input type="text" name="loglink" value=""><br />
<span style="font-size: smaller">e.g.:<i>http://www.pokerth.net/log-file-analysis/?ID=3608b6a0782a9bc7cd9d391157ff20d43d62a934&UniqueGameID=1</i></span><br />
<span style="font-size: smaller">(with UniqueGameID if it is not the 1st game!)</span>
</p>
<table border=1>
<tr><td colspan=8>This is the Logfile for:</td></tr>
<tr><td colspan=2>Step 1</td><td colspan=2>Step 2</td><td colspan=2>Step 3</td><td colspan=2>Step 4 </td></tr>

E;
// blockedgamenumbers
include_once "exp6/func2.php";
$blockedarray=blocker();



$gn=array();
// $sn=array(1,1,1,2,2,2,3,3,4,4); // length 10
$sn=array(1,1,2,2,3,3,4,4); // length 8
for($step=1;$step<5;$step++)
{
	$request = "SELECT MAX(gameno) FROM table1 WHERE step='$step' ";
	$result = mysql_query($request);
	$row = mysql_fetch_array($result);
	/*$gn[3*$step-2] = (int)$row[0];
	$gn[3*$step-1] = $row[0]+1;
	$gn[3*$step-3]="<input type=\"text\" name=\"gs$step\" maxlength=4 size=2>";
	$gn[3*$step-2] = (int)$row[0]; */
	$gn[2*$step-1] = $row[0]+1;
	$gn[2*$step-2]="<input type=\"text\" name=\"gs$step\" maxlength=4 size=2>";
//	$filename = "logfiles/BBC" . $gameno . "Step$step.html";
//	if(file_exists($filename)) break;
}
// $gn[7]=$gn[8];
// $gn[8]=$gn[9];
// $gn[9]=$gn[11];

require_once $_SERVER['DOCUMENT_ROOT'].'/defines.php';
chdir(ROOT_DIR);
function isalready($step,$gameno,$blockedarray)
{
	if(isblocked($blockedarray,$step,$gameno)==1) return "blocked";
	if($gameno!==(int)$gameno) return "(input number)";
	$filename1="logfiles/BBC$gameno" . "Step$step.html";
	$filename2="exp6/lf3/g$gameno" . "s$step.html";
	if(file_exists($filename1)) return "(no need)";
	if(file_exists($filename2)) return "(reupload)"; // TODO
	return "(new)";
}


print "<tr>";
for($i1=0;$i1<8;$i1++)
{	
	print "<td>Game $gn[$i1]</td>";
}
print "</tr>\n<tr>";
for($i1=0;$i1<8;$i1++) print "<td>" . isalready($sn[$i1],(int)$gn[$i1],$blockedarray) . "</td>";
print "</tr>\n<tr>";
//$sendva=array(10000,0,0,20000,0,0,30000,0);	
//for($i1=0;$i1<8;$i1++)
for($i1=0;$i1<8;$i1++) 
{	
	$i2=100000*$sn[$i1] + (int)$gn[$i1]; // beware of bug in 4 years
	//if(isblocked($blockedarray,$sn[$i1],(int)$gn[$i1])==0)
		print "<td><input type=\"radio\" name=\"game\" value=\"$i2\"></td>";
	//else print "<td>[blocked game]</td>";
	
}
print <<<E
</tr>
</table>
E;


for($i1=0;$i1<6;$i1++)
{
	if($blockedarray[$i1]==0) continue;
	
	print "<p><b>WARNING:</b>". $blockedarray[$i1][3] . " is active here, therefore step ".$blockedarray[$i1][0] ;
	print " game " . $blockedarray[$i1][1] ." is blocked until " . $blockedarray[$i1][2] . ".</p>";
}


print <<<E
<!--<p>Password: <input type="password" name="pass">-->
<input type="submit" value="Upload Files"></p>
</form>
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
