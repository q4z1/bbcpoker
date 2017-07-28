<br><hr><br>
<p>

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
}?>
&nbsp;&nbsp;
<?php
$t=date("Y-m-d H").":".date("i:s");
print "Server Time: $t ";
?>
<br><a href="login.php">login</a>
</p>

<div style="text-align:center;">
	<a href="http://live.pokerth.net" target="_blank" style="font-size:25px; font-weight:bold;">&gt;&gt; SPECTATE BBC &lt;&lt;</a>
</div>



