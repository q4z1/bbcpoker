<!DOCTYPE html>
<html>
<?php 
//die("this script is currently disabled");
ini_set('include_path', '/home/www/bbc/');
include "head.php";
$regulartaskcount=1;
include "exp2/regulartasks.php";
print "<body>";
include "header1.php"; ?>
<h1>Manipulate Tickets</h1>
<p>this page is only for admins</p>

<form action="exp4/input23.php" method="post">
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
<p>Password:<input type="password" name="pass"> 
<input type="submit" value="Submit">
</p>
</form>
<?php
include "footer1.php";
?>

</body>
</html>