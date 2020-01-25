<!DOCTYPE html>
<html>
<?php 
//die("this script is currently disabled");
require_once($_SERVER['DOCUMENT_ROOT'].'/defines.php');
ini_set('include_path', ROOT_DIR);
include "head.php";
$regulartaskcount=1;
include "exp2/regulartasks.php";
print "<body>";
include "header1.php";

session_start();

?>

<h1>Manipulate Tickets</h1>
<p>this page is only for admins</p>

<form action="/exp4/input23.php" method="post">
<p>Player: <input type="text" name="player"></p>

Action: 
<ul>
<li><input type="radio" name="action" value=21>Add one ticket to step 2</li>
<li><input type="radio" name="action" value=22>Add one ticket to step 3</li>
<li><input type="radio" name="action" value=29>Add one ticket to step 4</li>
<li><input type="radio" name="action" value=23>Take away one ticket to step 2</li>
<li><input type="radio" name="action" value=24>Take away one ticket to step 3</li>
<li><input type="radio" name="action" value=30>Take away one ticket to step 4</li>
</ul>
<p>Please add a SHORT reason: <input type="text" name="reason" size=37 maxlength=160></p>
<p>
<input type="submit" value="Submit">
</p>
</form>
<?php
include "footer1.php";
?>

</body>
</html>