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

<div style="margin-left:auto;margin-right:auto;width:70%">

<p>Hi BBC community, here is my plan:</p>
<p>

in the next 3 weeks (so maybe until 9th of march, not exactly), you (that means the bbc community) can collect ideas of what to change or improve in bbc, ( website, rules, etc.). after that there is a chance that i find time to program changes and/or decide with other admins.<br>
<p> <u>note:</u>
i dont promise to do anything. especially with programming, i always is an effort behind it, and if i am to lazy to do big programming projcts, you have to accept that. also this discussion is thought to produce good suggestions what to do, but nothing mandatory for us.</p>
<p>basically you can make any idea you want. please discuss each others opinions and ideas in a friendly manner.
but i would like to draw your attention to a bunch of questions, because we are interested in your opinion, especially about the rating i thought of and created</p>
<p>
Questions you can discuss:
</p>
<h3>1. RATING</h3>
<p>let me explain first: it is called rating and not ranking for a reason. each player gets a number, that evaluates their current strength based on BBC results. what you played 100 games ago shouldnt play a huge role. (in contrary, you can see our current ranking system as something that represents your glory, (e.g. you never loose points you won once). 
how do i try to achieve this? you dont need to understand the formula i posted there, but try to understand the principle:</p>
<ul>
<li>if your opponents have more points than you and you play good, you win many points</li>
<li>if your opponents have less points than you and you play good, you win a few points</li>
<li>if your opponents have more points than you and you play bad, you loose few points</li>
<li>if your opponents have less points than you and you play bad, you loose many points</li>
</ul>
<p>ofcourse different places are awarded differently (1-10)</p>
<p>OK here is the link check it out: <a href="http://bbcpoker.bplaced.net/exp6/rating2.php">RATING</a></p>
<p>you can see roughly 400 games and how people would win/loose points in every single game. finally, if you enter two correct player names, it shows you how their rating changed over time (pics for ppl who hate numbers)! that way you can see much more.
Note: there are parameters, and you can change them to produce a different rating. i put standard parameters there, but have fun playing around with other parameters.
if you have questions or wonder about strange results, ASK! if i am avaliable, i try to answer. 
(and have in mind: there is no perfect system)</p>

<p><b>1.1.</b> what parameters should we use for the rating?<br>
 please write them down if you find them to be good.</p>
<p><b>1.2. </b>what role should the rating score of player have in the bbc statistics, compared to current/all-time ranking?</p>
<p><b>1.3. </b>if we introduce it, should there be a "testing phase" before making it more official?</p>
<p><b>1.4. </b>if we introduce it, should the calculation of the rating start from then or start from the beginning of bbc?</p>
<p><b>1.5. </b>if we introduce it, should we also record the history of the rating of each single player? (so that you have a nice graph of your rating changes over time</p>
<p><b>1.6. </b>is the main strategy ok? do you want to change details?</p>
<h3>2. other stuff:</h3>
<p><b>2.1. </b>are the blind settings ok?</p>
<p><b>2.2. </b>are the dates and times for bbc games ok?</p>
<p><b>2.3. </b>should there be fixed dates for step3?</p>
<p><b>2.4. </b>should there be changes to the website in general?</p>
<p><b>2.5. </b>are you ok with the way of registration?</p>
<p><b>2.6. </b>do you want to suggest new bbc admins?</p>
<br><br>
<p> ok so far. maybe i will add more during time. i am looking forward to your opinion</p>
</div>
</div>
<?php
include "footer1.php";
?>

</body>
</html>
