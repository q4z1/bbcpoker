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

<h2>Logfile Analysis</h2>
<p>- when the game is over, leave the lobby and click on "5 Logs"<br><br>
	<a href="http://www.servimg.com/image_preview.php?i=83&u=17486677" target="_blank" ><img src="http://i15.servimg.com/u/f15/17/48/66/77/screen10.jpg" border="0" alt="Image hÃ©bergÃ©e par servimg.com" /></a><br><br>
	- select the good file, then click on <b>Analyse Logfile</b>,<br> 
	(remember 1 logfile by connection -> 1 or several games in a log)<br><br>
	<a href="http://www.servimg.com/image_preview.php?i=84&u=17486677" target="_blank" ><img src="http://i15.servimg.com/u/f15/17/48/66/77/screen11.jpg" border="0" alt="Image hÃ©bergÃ©e par servimg.com" /></a><br><br>
	- select the good game <br><br>
	<a href="http://www.servimg.com/image_preview.php?i=85&u=17486677" target="_blank" ><img src="http://i15.servimg.com/u/f15/17/48/66/77/screen12.jpg" border="0" alt="Image hÃ©bergÃ©e par servimg.com" /></a><br><br>
	- <b>copy-paste</b> the <b>URL</b> in PokerTh lobby<br><br>
	<a href="http://www.servimg.com/image_preview.php?i=87&u=17486677" target="_blank" ><img src="http://i15.servimg.com/u/f15/17/48/66/77/screen14.jpg" border="0" alt="Image hÃ©bergÃ©e par servimg.com" /></a><br><br>
	- Optionnel : you can <b>save as and rename it</b> on your computer<br> with option : full web page (.html & files folder)<br>
	but <b>before click on "expand all"</b><br><br>
	<a href="http://www.servimg.com/image_preview.php?i=106&u=17486677" target="_blank" ><img src="http://i56.servimg.com/u/f56/17/48/66/77/saveas10.jpg" border="0" alt="Image hÃ©bergÃ©e par servimg.com" /></a><br><br>
</p>

<?php
include "footer1.php";
?>
</body>
</html>