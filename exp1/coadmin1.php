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



<!-- Checklist -->                
<h1>Co-Admins tasks</h1>
<h2>Create BBC game - Report in Pth Forum</h2>
<p style="text-align:left;margin-left:1cm">
<strong>If you are experienced with BBC Admin procedure and if you have permission for bbcbot and you are able to open a bbc table with correct settings</strong> ... and of course, if you agree to do the job :)<br><br>
	1. open the game around 19:20 (=10 minutes before game-start) with BBC settings (cf : <a href="http://bbc.pokerth.net/exp1/StoreSettings.php" target=_blank >How to store them</a> / or use bbcbot).<br>
	2. invite registered players with the Step1/2/3/4 list.<br> 
	If players are missing at 19:30 (=game-start time), invite substitutes - players of your choice. If Step >1 make sure that substitutes do have a ticket (use bbcbot to suggest players for step) <br>
	(restriction: players with very bad stats or/and behavior).<br>
	3. if a player is missing (disconnect) at the very beginning of the game, please ask all players to leave the game and RESTART <br> 
	Only BBC games with 10 players are valid.<br>
	4. stay until the end of the game, post <a href="http://bbc.pokerth.net/exp1/LogfileAnalysis.php" target=_blank >the url of the logfile</a> in the lobby or/and BBC chatbox and report in PokerTH forum.<br>
	5. if you have been disconnected, your logfile analysis is incomplete, then you have to ask for log-link from finalist players.
<br><br>
<strong>Optional:</strong><br>
When the game's result is input by the admin in BBC database, you could get the BBcode for "forum standard presentation" with this link:<br>
http://bbc.pokerth.net/exp6/getcode1.php?s=1&amp;g=yourgamenumber<br>
e.g. : http://bbc.pokerth.net/exp6/getcode1.php?s=1&amp;g=520<br>
... for higher steps adjust the s= parameter.<br><br>

<strong>Next steps - if you want to become an admin - would be : upload log-link and input in BBCdatabase<br>
In any case, please keep the log(file) for a minimum of 7 days in your pokerth client - if needed for any kind of correction.
</p>

<?php
include "footer1.php";
?>

</body>
</html>