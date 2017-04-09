<?php
$auth1=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc']==2 or $_SESSION['upc']==3)
    $auth1 = 1;
} //$_COOKIE['PHPSESSID'] != ""

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


<div style="margin-left:auto;margin-right:auto;width:70%">

<p>Hi, This is just a random collection of ideas for tournaments. Not always relevant for poker, i just wanted to write stuff down.</p>

<h2><small>[poker]</small> Ticket Based Large Flexible Tournament</h2>

<ul>
<li> Ticket/Step System like BBC</li>
<li> Step 1: 10 Players, Ts2 for #1,#2,#3,#4</li>
<li> Step 2: 10 Players, Ts3 for #1,#2 and Ts2 for #3,#4,#5,#6</li>
<li> Step 3: 10 Players, Ts4 for #1</li>
<li> Step 4: 6 Players, Ts5 for #1</li>
<li> Step 5 and higher steps: 6 Players, only winner gets ticket for next step</li>
<li> Remark: good players will probably play more step2 games than step1 games, bad players/all-inners will play mostly step 1</li>
<li> Ticket Limit: maximum 15 Tickets for each step</li>
<li> Optional: for Step 5 and higher: steps consist of multiple games with the same people</li>
<li> Optional: Limit step1 access to teach the all-inners some patience (e.g. max 5 step1 games in 3 hours)
<li> Scheduling: there is always a Step1 and Step2 game open, step3 and higher steps are dynamic (i.e. react automatically for demand)</li>
</ul>

<h2><small>[poker]</small> league system/multiple-games-match</h2>
<ul>
<li>10 Players</li>
<li>A fixed number of games (i.e. 7)</li>
<li>Games can (and probably will) start with less than 10 Players</li>
<li>Criteria for placement:
 <ol>
  <li>Number of wins</li>
  <li>Number of second places</li>
  <li>Number of third places</li>
  <li>Number of fourth places</li>
  <li>Number of fifth places</li>
  <li>Number of sixth places</li>
  <li>Number of seventh places</li>
  <li>Number of eight places</li>
  <li>Number of nineth places</li>
  <li>Number of tenth places</li>
  <li>Starting Number (assigned before start)</li>
 </ol>
</ul>

<h2><small>[not poker]</small> Team-based game, single player evaluation</h2>

<ul>
<li>Fixed number of players, who participate in every game</li>
<li>Everyone starts with 0 points</li>
<li>For each game, the teams are chosen in such a way, that their combined strength is roughly the same</li>
<li>If a game ends in a draw, it is repeatet with the same teams</li>
<li>After a game, everyone in the winning team gets one point more</li>
<li>Remark: over time, the teams will become more and more balanced</li>
</ul>

<h2><small>[not poker]</small> Team-based game 3v3, single player evaluation</h2>
<ul>
<li>6 Players will play 6 games (3v3)</li>
<li>After that, the players can be compared pairwise, by comparing the results of the two games, where they had each a game with the same teammates</li>
<li>This comparison can be used to make a final ranking</li>
<li>Concept can be generalised for more players, but it does not work for every number</li>
</ul>
<ol>
<li>a,b,c -- d,e,f</li>
<li>a,b,d -- c,e,f</li>
<li>a,b,e -- c,d,f</li>
<li>a,b,f -- c,d,e</li>
<li>a,c,d -- b,e,f</li>
<li>b,c,d -- a,e,f</li>
</ol>
<table border=1>
<tr><td></td><th>a</th><th>b</th><th>c</th><th>d</th><th>e</th><th>f</th></tr>
<tr><th>a</th><td> * </td><td>   </td><td>   </td><td>   </td><td>   </td><td>   </td></tr>
<tr><th>b</th><td>5,6</td><td> * </td><td>   </td><td>   </td><td>   </td><td>   </td></tr>
<tr><th>c</th><td>2,6</td><td>2,5</td><td> * </td><td>   </td><td>   </td><td>   </td></tr>
<tr><th>d</th><td>1,6</td><td>1,5</td><td>1,2</td><td> * </td><td>   </td><td>   </td></tr>
<tr><th>e</th><td>4,5</td><td>4,6</td><td>1,3</td><td>2,3</td><td> * </td><td>   </td></tr>
<tr><th>f</th><td>3,5</td><td>3,6</td><td>1,4</td><td>2,4</td><td>3,4</td><td> * </td></tr>
</table>
<p>TODO</p>

<h2><small>[not poker]</small> 1v1v1, single player evaluation</h2>
<ul>
<li>7 Players, everyone will play 3 games and exactly once against each opponent</li>
<li> Final Placement based on results from extracting 1v1 games</li>
</ul>
<ol>
<li>a-b-c</li>
<li>a-d-e</li>
<li>a-f-g</li>
<li>b-d-f</li>
<li>b-e-g</li>
<li>c-d-g</li>
<li>c-e-f</li>
</ol>




</div>
<?php
include "footer1.php";
?>

</body>
</html>
