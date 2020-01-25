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

<h1>Upload Logfile Analysis</h1>
<p>Dear Admin, only use the following stuff if you know what you are doing and have the permissions to do so.
If you have questions, problems, etc. ask!<br>
It would be nice, if you keep the original file on your local Computer</p>
<form action="/exp7/upload4.php" enctype="multipart/form-data" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="200000" />
<p>
HTML file: <input type="file" name="uf1"><br>
hand_cash.png: <input type="file" name="uf2"><br>
pot_size.png:<input type="file" name="uf3">
</p>
<table border=1>
<tr><td colspan=8>This is the Logfile for:</td></tr>
<tr><td colspan=3>Step 1</td><td colspan=3>Step 2</td><td colspan=2>Step 3</td></tr>
<?php
$gn=array();
$sn=array(1,1,1,2,2,2,3,3);
for($step=1;$step<4;$step++)
{
	$request = "SELECT MAX(gameno) FROM table1 WHERE step='$step' ";
	$result = mysql_query($request);
	$row = mysql_fetch_array($result);
	$gn[3*$step-2] = (int)$row[0];
	$gn[3*$step-1] = $row[0]+1;
	$gn[3*$step-3]="<input type=\"text\" name=\"gs$step\" maxlength=4 size=2>";
//	$filename = "logfiles/BBC" . $gameno . "Step$step.html";
//	if(file_exists($filename)) break;
}
$gn[7]=$gn[8];
require_once $_SERVER['DOCUMENT_ROOT'].'/defines.php';
chdir(ROOT_DIR);
function isalready($step,$gameno)
{
	if($gameno!==(int)$gameno) return "(input number)";
	$filename1="logfiles/BBC$gameno" . "Step$step.html";
	$filename2="exp7/lf3/g$gameno" . "s$step.html";
	if(file_exists($filename1)) return "(no need)";
	if(file_exists($filename2)) return "(reupload)";
	return "(new)";
}


print "<tr>";
for($i1=0;$i1<8;$i1++)
{	
	print "<td>Game $gn[$i1]</td>";
}
print "</tr>\n<tr>";
for($i1=0;$i1<8;$i1++) print "<td>" . isalready($sn[$i1],$gn[$i1]) . "</td>";
print "</tr>\n<tr>";
//$sendva=array(10000,0,0,20000,0,0,30000,0);	
//for($i1=0;$i1<8;$i1++)
for($i1=0;$i1<8;$i1++) 
{	
	$i2=10000*$sn[$i1] + (int)$gn[$i1];
	print "<td><input type=\"radio\" name=\"game\" value=\"$i2\"></td>";
}
print "</tr>";
?>
</table>
<p>Password: <input type="password" name="pass">
<input type="submit" value="Upload Files"></p>
</form>

<?php
include "footer1.php";
?>

</body>
</html>
