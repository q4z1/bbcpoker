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
include "husc/huscfun1.php"; // getseason()
$season=getseason();
include "husc/s$season/warning.html";
include "husc/huscnav1.php";
?>
<div style="margin-left:auto;margin-right:auto;width:80%">
<h1>Ideas for HUSC Changes</h1>
<p>Hi folks, on this page we can collect ideas on how to improve the Schedule or Blind Settings for Season 2. 
Other improvements ideas are welome too, but might be harder to implement. Therefore, we cannot guarantee, that these suggestions will be accepted. 
You can send us your ideas with PM in pokerth Forum or email to supernoob (or Nelly) </p>
<h2>Schedules / Time Pattern</h2>
<h3>Suggestion #1 (by supernoob)</h3>
<p>
These are the schedules from season 1. There will be 3 rounds per week.
</p>
<p><b>Round A</b>: You should be able to play at least at <b>4</b> out of the following 7 dates:</p>
<ul>
<li>monday 18:50</li>
<li>monday 21:00</li>
<li>monday 23:00</li>
<li>tuesday 18:50</li>
<li>tuesday 21:00</li>
<li>wednesday 18:50</li>
<li>wednesday 21:00</li>
</ul>
<p><b>Round B</b>: You should be able to play at least at <b>4</b> out of the following 7 dates:</p>
<ul>
<li>thursday 18:50</li>
<li>thursday 21:00</li>
<li>friday 18:50</li>
<li>friday 21:00</li>
<li>friday 23:00</li>
<li>saturday 18:50</li>
<li>saturday 21:00</li>
</ul>
<p><b>Round C</b>: You should be able to play at least at <b>2</b> out of the following 3 dates:</p>
<ul>
<li>sunday 17:00</li>
<li>sunday 18:50</li>
<li>sunday 21:00</li>
</ul><h3>Suggestion #2 (by supernoob)</h3>
<p>
In contrast to #1, there will be at least 2 days per round, and 18:50 was replaced with 19:00. 3 rounds per week.
</p>
<p><b>Round A</b>: You should be able to play at least at <b>3</b> out of the following 5 dates:</p>
<ul>
<li>tuesday 19:00</li>
<li>tuesday 21:00</li>
<li>wednesday 21:00</li>
<li>thursday 19:00</li>
<li>thursday 21:00</li>
</ul>
<p><b>Round B</b>: You should be able to play at least at <b>3</b> out of the following 5 dates:</p>
<ul>
<li>friday 19:00</li>
<li>friday 21:00</li>
<li>friday 23:00</li>
<li>saturday 19:00</li>
<li>saturday 21:00</li>
</ul>
<p><b>Round C</b>: You should be able to play at least at <b>3</b> out of the following 5 dates:</p>
<ul>
<li>sunday 17:00</li>
<li>sunday 19:00</li>
<li>sunday 21:00</li>
<li>monday 19:00</li>
<li>monday 21:00</li>
</ul>
<h3>Suggestion #3 </h3>
<p>TODO</p>
<h2>Blind Settings</h2>
<p>Games can be opened by BBCBOT, so you can choose your settings as complicated as you want.</p>
<h3>Suggestion #1 (by supernoob)</h3>
<p>These are the blind settings of season 1. Games last ~11 minutes.</p>
<p>Blind Raise every 2 Minutes. Stack: 1000 $ , Small Blinds: starts with 15 and continues like BBC blind settings.
<br>
<small>( 20, 25, 30, 40, 50, 60, 80, 100, 120, 150, 200, 250, 300, 400, 500, 600, 800, 1000, 1200, 1500, 2000, 2500,
3000, 4000, 5000, 6000, 8000, 10000, 12000, 15000, afterwards blinds are doubled.)</small></p>
<h3>Suggestion #2 (by supernoob)</h3>
<p>With these settings, you will start with 30 BB and the blinds will stay the same for 12 minutes.
In the unlikely event that a game lasts longer than 24 minutes, there will be forced Bingo to end the game. </p>
<p>Blind Raise every 2 Minutes. Stack: 300 $ , Small Blinds: starts with 5 </p>
<p>Blind Raise List: 5,5,5,5,5,10,10,15,20,30,40,640</p>
<h3>Suggestion #3 (by ElmoEGO,Nelly)</h3>
<p>Like Settings from Season 1, but stack raised to 1500</p>
<p>Blind Raise every 2 Minutes. Stack: 1500 $ , Small Blinds: starts with 15 and continues like BBC blind settings.
<br>
<small>( 20, 25, 30, 40, 50, 60, 80, 100, 120, 150, 200, 250, 300, 400, 500, 600, 800, 1000, 1200, 1500, 2000, 2500,
3000, 4000, 5000, 6000, 8000, 10000, 12000, 15000, afterwards blinds are doubled.)</small></p>

</div>

<?php
include "footer1.php";
?>

</body>
</html>