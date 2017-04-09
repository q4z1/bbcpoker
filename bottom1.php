<br><hr><br>
<p>
<a href="exp4/shoutbox1.php">Please give Feedback here! (or chat :) ) </a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<?php
//print "check";
require_once $_SERVER['DOCUMENT_ROOT'].'/defines.php';
chdir(ROOT_DIR);
if(file_exists("exp2/visitorcount.txt"))
{
	$file = fopen("exp2/visitorcount.txt","r");
	$row = fgets($file);
	fclose($file);
	print "site views (clicks, all sites): ";
	print $row;
	
}
//else print "file not found";
	?>
</p>
	

