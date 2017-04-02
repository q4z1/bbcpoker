<?php
$auth1=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc']==2 or $_SESSION['upc']==3)
    $auth1 = 1;
} //$_COOKIE['PHPSESSID'] != ""

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


<?php
if($auth1==0) 
{
  include "footer1.php";
  print "</body></html>";
  die();
}
?>

<div style="margin-left:auto;margin-right:auto;width:70%">

<p>Hi, This is a Concept for a possible BBC Heads up tournament</p>

<p>DISCLAIMER: i dont promise to do anything, the following is just an idea. most likely i will find time in march to do the programming, but again, i wont promise to do it. 
I am open for suggestions</p>

<p><b>1. General stuff</b><br>
I want to keep the organizing effort very low. the HUC from scotty and others always needed a person that was online during games.
Usually a heads-up-cup is harder to organize than one with 10 players per table.
My Idea would be to build a webpage where players can enter the result of the games by themselves.
Maybe every player should get a code when he registrates for the cup, and one can only enter the results with their code.
Or there should be an BBC account for every player who wants to participate</p>

<p><b>2. general Format</b><br>
My Idea is a special kind of tournament mode: its a certain type of a knock-out-system:
Every Player gets 3 lives (it could also be 2,4,5, or many more lives).
When you loose a game, you loose a live. When you have no lives, you are dead, that means you no longer take part in the tournament
The last player that has lives left is the winner.</p>

<p><b>3. Date and Time / Scheduling</b><br>
Here is my suggestion (i hope its not too complicated)
We Play the Tournament not on a single day, but over a period of some weeks.
There are 3 rounds per week and in each round there will be a certain number of time slots avaliable. 
Every Player must select more than half of these slots and he can set priorities among those.<br>
Example:<br>

First round: select 4 of the following 7 time slots: 
<ul>
<li>Monday 18:50</li>
<li>Monday 21:00</li>
<li>Monday 23:00</li>
<li>Tuesday 18:50</li>
<li>Tuesday 21:00</li>
<li>Wednesday 18:50</li>
<li>Wednesday 21:00 (maybe 22:30, to avoid step2 conflicts)</li>
</ul>
Second round: select 4 of the following 7 time slots:
<ul>
<li>Thursday 18:50</li>
<li>Thursday 21:00</li>
<li>Friday 18:50</li>
<li>Friday 21:00</li>
<li>Friday 23:00</li>
<li>Saturday 18:50</li>
<li>Saturday 21:00</li>
</ul>
Third round: select 2 of the following 3 time slots:
<ul>
<li>Sunday 17:00</li>
<li>Sunday 18:50</li>
<li>Sunday 21:00 (maybe 22:30, to avoid step2 conflicts)</li>
</ul>



<!--games are always played at 19.00 or 18:50 (and should be finished before bbc starts at 19:30 ;) )
First round is from Monday to Wednesday, second from Thursday to Saturday, third one on sunday. (or you could select saturday as a "must play day" instead of sunday)
before the tournament every player chooses two of the days Monday,Tuesday,Wednesday and two of the days Thursday, Friday, Saturday.
Then two players have always at least one common day. Thats when the game takes place.
There could also be more time slots, e.g. multiple per day. 
However, you still would need to select more than half of the time slots so it is guaranteed that both players have a common time -->
</p>

<p><b>4. Format of a single game</b><br>
Blinds can be discussed, i would recommend not to start with a huge stack. Also it would be good if the game is finished after a certain amount of times, i.e. 20 minutes<br>
Example: Blind Raise every 2 Minutes. Stack: 1000, Small Blinds: 15,20,25,30,40,50,60,80,100,120,150,200,250, etc.</p>

<p><b>5. Details about the format</b><br>
It is not necessary that 2^k people start. I would just recommend an even ammount of players, but odd would work too.
During the tournament it can happen that we have an odd number of players. Then one player is lucky and gets a free slot per round.
Of course the free slot will not always go to the same player ;)
I have already made a simple (deterministic!) pairing algorithm, along with a ranking method (to determine who is second, third, etc. even if they loose at the same time)</p>

<p><b>6. Organization Points</b><br>
Maybe we could introduce a little point system as an incentives, that players enter their results properly and appear when their game is scheduled</p>

<p><b>7. Name of the cup:</b><br>
I dont like BBCHUC, because that would be Best Brains Cup Heads Up Cup - a Cup too much for me.
Based on the mode with lives i thought of something like "Heads Up Survival Cup" - HUSC . 
What do you think? I am open for other suggestions.</p>

<p><b>8. When will we play it?</b><br>
It is possible that the programming will occur in march or april. I think we should/could even start with a small tournament (~12 players) to test the system.</p>

<p><b>9. Public Logs</b><br>
I think for transparency reasons it would be good to have files that are public, in which every entry of result by a player is logged. (similar idea like ticketlog). 
And it should be logged if he changes his scheduling preferences.</p>

<p><b>8. How long will the cup last?</b><br>
The Length depends of course on the number of players and lives you start with.
I already made a python test for simulation with random results. if somebody is interested, i could publish the script or the simulation results
</p>


</div>
<?php
include "footer1.php";
?>

</body>
</html>