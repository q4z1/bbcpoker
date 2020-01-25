<?php
print '<!DOCTYPE html>
<html>';
require_once($_SERVER['DOCUMENT_ROOT'].'/defines.php');
ini_set('include_path', ROOT_DIR);
include "head.php";
//$regulartaskcount=1;
//include "exp2/regulartasks.php";
print "<body>";
include "header1.php"; 
include "exp5/nav1.php";

?>

<h1>MD5 calculator</h1>

<?php 
if($_POST['inputpass']!="")
{
	$hash=md5($_POST['inputpass']);
	print "<p>Your md5-Value of the password is<br>
$hash</p><br><br>";
	
}
?>
<form action="/exp6/logintest1.php" method="post">
<input type="password" name="inputpass">
<input type="submit" value="Get md5 value">
</form>
<?php
include "footer1.php";
?>

</body>
</html>
