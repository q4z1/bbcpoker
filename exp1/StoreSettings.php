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

<h2>How to store BBC Settings</h2>
<p>Fill the gaps like those screenshots<br><br>
	<img src="exp1/images2/settings1.png" alt="settings1" title="settings1" width=550 height=386 /><br><br>
	<img src="exp1/images2/settings21.png" alt="settings21" title="settings21" width=550 height=386 /><br><br>
	Click on "edit"<br><br>
	<img src="exp1/images2/settings22.png" alt="settings22" title="settings22" width=550 height=386 /><br><br>
	Then ok, ok.
</p>

<?php
include "footer1.php";
?>
</body>
</html>