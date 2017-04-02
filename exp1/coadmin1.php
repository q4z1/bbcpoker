<?php
print '<!DOCTYPE html>
<html>';
ini_set('include_path', '/home/www/bbc/');
include "head.php";
$regulartaskcount=1;
include "exp2/regulartasks.php";
print "<body>";
include "header1.php"; 
include "exp5/nav1.php";
?>



<!-- Checklist -->                
<h1>Co-Admins tasks</h1>
<h2>Create BBC game - Report in Pth Forum</h2>
<p style="text-align:left;margin-left:1cm">
<strong>Only if an admin ask you</strong> and of course, if you are agree to do the job :)<br><br>
	1. open the game around 19:20 with BBC settings (cf : <a href="http://bbcpoker.bplaced.net/exp1/StoreSettings.php" target=_blank >How to store them</a>).<br>
	2. invite registered players with the Step1 list.<br> 
	If players missing at 19:30, invite substitutes then players of your choice <br>
	(restriction: players with very bad stats or/and behavior).<br>
	3. if player missing at the very beginning of the game, please asked all players to leave the game and RESTART <br> 
	Only BBC games with 10 players are valid.<br>
	4. stay until the end of the game, post <a href="http://bbcpoker.bplaced.net/exp1/LogfileAnalysis.php" target=_blank >the url of the logfile</a> in the lobby or/and BBC chatbox and report in pokerTh forum.<br>
	5. if you have been disconnected, your logfile analysis is incomplete, then you can ask logfile of finalist players.
<br><br>
<strong>Optionnel</strong><br>
When the game's result is input by the admin in BBC database, you could get the BBcode for "forum standart presentation" with this link :<br>
http://bbcpoker.bplaced.net/exp6/getcode1.php?step=1&amp;g=yourgamenumber<br>
e.g. : http://bbcpoker.bplaced.net/exp6/getcode1.php?step=1&amp;g=520<br><br>
<strong>For future step </strong>(that would be : upload logfile and input in BBCdatabase)<br>
save the logfile in your computer with option : full web page (.html & files folder) but before click on "expand all"
</p>

<?php
include "footer1.php";
?>

</body>
</html>