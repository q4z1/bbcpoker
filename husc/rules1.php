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
<h1>HUSC Rules</h1>
<h3>General format</h3>
<p>Every Player starts with a certain number of lives, e.g. 3 or 4 lives (the exact number of lives can differ from season to season). If he/she looses a game, she/he also looses a live. A player with no lives is dead and therefor out of the current HUSC season.
The last remaining Player is the winner.</p>

<h3>Blind settings</h3>
<p>Blind Raise every 2 Minutes. Stack: 1500 $ , Small Blinds: starts with 15 and continues like BBC blind settings.
<br>
<small>( 20, 25, 30, 40, 50, 60, 80, 100, 120, 150, 200, 250, 300, 400, 500, 600, 800, 1000, 1200, 1500, 2000, 2500,
3000, 4000, 5000, 6000, 8000, 10000, 12000, 15000, afterwards blinds are doubled.)</small></p>
<p>(in season 1, we had a 1000 $ stack)</p>
<h3>Other stuff, Awards, Prices, Long-time planning</h3>
<p>The HUSC winner will get an award in their BBC Profile.</p>
<p>Starting in Season 2, there will be BBC tickets as prices. 
These will be specified each season individually and can depend on the number of lives and participating players.</p>
<p>Every BBC player with at least 10 BBC games is able to register. 
Nelly or supernoob can choose the participants among those registrations.
A player that violates the code of conduct or causes trouble, does not need to be accepted in future HUSC seasons.</p>
<p>The first HUSC season will be a test season or debug season. 
Every BBC-admin can participate and the 5 fastest registrations from non-admins (but BBC players) are allowed. 
There will be 3 lives and no tickets for winners</p>
<p>The second HUSC season will be open for every BBC player with at least 10 BBC games. Everyone starts with 4 lives.
Ticket prices will be distributed as following:</p>
<ul>
<li>2-12 players: <b>1 Ts3</b> for the winner</li>
<li>13-28 players: <b>2 Ts3</b> for the winner, <b>1 Ts2</b> for second</li>
<li>29 or more players: <b>3 Ts3</b> for the winner, <b>1 Ts3</b> for second, <b>1 Ts2</b> for third</li>
</ul>

<h3>Seeding</h3>
<p>Before a HUSC season starts, each participant will get a &quot;seed number&quot; (based on BBC rating) according to the following rules:
<ul>
<li>This number is unique for every player. If the number of participants is equal to X, the numbers 1 to X will be seed numbers</li>
<li>The seeding will be done according to the BBC rating at the beginning of the month in which the HUSC season starts.</li>
<li>The Player with higher rating gets the higher seed number.</li>
</ul>
<p>Example: if 20 players participate in a HUSC season, 
then the highest rated player would get the number 20, the second rated gets 19, the third 18, 
and the player with lowest rating gets seed number 1.
(and for a new HUSC season you will get a new seed number.)</p>
<h3>Ranking Algorithm</h3>
<p>There are 3 possible results for each game:
<ul>
<li>Played game, one player wins, the other looses</li>
<li>Forfeit win/loss: one player does not appear to the game</li>
<li>Forfeit loss for both players: no result feedback, both players did not play</li>
</ul>
<p>The Ranking will be calculated after each round according to the following criteria: 
(the higher the number, the better. in case of equality look at the next criteria)</p>
<ol>
<li>Lives</li>
<li>won games</li>
<li>won played games</li>
<li>number of Forfeit losses</li>
<li>the sum of all won games of the played opponents</li>
<li>the sum of all lives of the played opponents</li>
<li>the sum of all won games of all opponents</li>
<li>the sum of all lives of all opponents</li>
<li>the sum of all won played games of the played opponents</li>
<li>the sum of all Forfeit losses of the played opponents</li>
<li>the sum of all won played games of all opponents</li>
<li>the sum of all Forfeit losses of all opponents</li>
<li>Seeding number</li>
</ol>
<p>In case of &quot;the sum of ... of opponents&quot;: If you play an opponent twice, it is also counted twice.</p>
<!-- maybe better explanation ... -->
<h3>Pairing Algorithm</h3>
<p>The Pairing Algorithm describes which players play against each other</p>
<p><b>Step 1:</b> If the number of living players is even, go to step 3. Otherwise go to step 2.</p>
<p><b>Step 2:</b> We choose the player who will not have an opponent (i.e. no game) this round according to the following criteria, then go to step 3:</p>
<ol>
<li>Number of games</li>
<li>Number of played games</li>
<li>Highest Ranking</li>
</ol>
<p><b>Step 3:</b> Now the number of remaining players is even. Pick the highest ranked player.<br>
Among the remaining players, look for an opponent according to the following criteria (then go to step 4):
</p>
<ol>
<li>Fewest number of scheduled games between those players.</li>
<li>Fewest number of played games between those players.</li>
<li>Highest number of lives</li>
<li>Lowest ranking</li>
</ol>
<p><b>Step 4:</b> If there are any players remaining, go to step 3 again. If every player found an opponent, STOP. </p>
<h3>Scheduling Algorithm</h3>
<p>The Scheduling Algorithm describes the way a date is found for two Players. 
For each round, there is a number of avaliable time slots, e.g. 3,5, or 7.
Each player chooses for each time slot between 3 options:</p>
<ul>
<li><b>1</b>: That's a good time slot for me</li>
<li><b>2</b>: That time slot is ok for me</li>
<li><b>3</b>: I cannot play at that time</li>
</ul>
<p>There is the important restriction, that 3. can be chosen at most less than half of the avaliable time slots.</p>
<p>
The date for the game is chosen according to the following criteria (if you take the restriction above into account, 
these criteria are sufficient for a decision)
</p>
<ol>
<li>if Possible, select the earliest date where both players have &quot;1&quot;</li>
<li>if Possible, select the earliest date where one player has &quot;1&quot; and the other &quot;2&quot;</li>
<li>select the earliest date where both players have &quot;2&quot;</li>
</ol>

<h3>End of round / Scheduling</h3>
<p> A round ends after every game input is done and is correct, or in the next morning after the last scheduled game of that round.
(Official end of rounds: usually 6 am, start of round: usually 2 pm. The automated check for end of round will occur between 8 am and 10 am)
If one player forget to enter their result, the input from the other player is taken. 
If no player did any input, the game will be a forfeit loss for both players.
If both players claim to have won, the round will not end and Nelly or supernoob have to decide about the final results,
this can also result in a forfeit loss for both players.
Eventually the tournament will be delayed by such events.
After the end of a round, the next scheduled round (among A,B,C) will be selected, i.e. the one that starts sooner.
<br>The automated check will edit the result according to the following rules:</p>
<pre>
00 -> --
01 -> 01
0- -> --
0+ -> -+
0? -> --
11 -> stop
1- -> stop
1+ -> stop
1? -> 10
-- -> --
-+ -> -+
-? -> --
++ -> stop
+? -> +-
?? -> --
</pre>
</div>

<?php
$phase=getphase($season);
$round=getround($season);
if($phase>=1 or $round>=1 or isfinished($season)==1)
{
  print "<h2>Specific Rules for season $season (from registration page)</h2>\n";
  print sched2html($season);
  include "husc/s$season/codeofconduct.html";
}

?>

<?php
include "footer1.php";
?>

</body>
</html>