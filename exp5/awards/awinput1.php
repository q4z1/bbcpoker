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

<form method="post" enctype="multipart/form-data" action="/exp5/awards/awinput2.php">
<input type="hidden" name="MAX_FILE_SIZE" value="200000"/>
<p>Player:<input type="text" name="player" size=12></p>
<p>Select a picture for award: <input type="file" name="pic"></p>
<p>Description: <input type="text" name="descr" size=40></p>
<p>Password: <input type="password" name="pass"><input type="submit" value="Add Awards"></p>




<?php
include "footer1.php";
?>
</body>
</html>
