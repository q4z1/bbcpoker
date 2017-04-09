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

<h1>Calculating explained</h1>
<h3>Calculation of Points</h3>
<p>You get Points for each BBC game you play, according to the following table:</p>
<table border=1>
<tr><td> Place</td><td colspan=4> <b>Points</b> if game is</td></tr>
<tr><td></td><td>Step 1</td><td>Step 2</td><td>Step 3</td><td>Step 4</td></tr>
<tr><th>1</th><td>10</td><td>20</td><td>30</td><td>40</td></tr>
<tr><th>2</th><td>9</td><td>18</td><td>27</td><td>36</td></tr>
<tr><th>3</th><td>8</td><td>16</td><td>24</td><td>32</td></tr>
<tr><th>4</th><td>7</td><td>14</td><td>21</td><td>28</td></tr>
<tr><th>5</th><td>6</td><td>12</td><td>18</td><td>24</td></tr>
<tr><th>6</th><td>5</td><td>10</td><td>15</td><td>20</td></tr>
<tr><th>7</th><td>4</td><td>8</td><td>12</td><td>16</td></tr>
<tr><th>8</th><td>3</td><td>6</td><td>9</td><td>12</td></tr>
<tr><th>9</th><td>2</td><td>4</td><td>6</td><td>8</td></tr>
<tr><th>10</th><td>1</td><td>2</td><td>3</td><td>4</td></tr>
</table>
<h3>Calculation of Score</h3>
<p>If P is the Number of Points you got and G is the Number of Games you played, 
the score is calculated with the following formula:</p>
<img src="exp4/formula2.gif" width=110 height=36 alt="formula">
<p>So its "Points per Game" multiplied with a coefficient. 
This coefficient is 1 in the first game, and increases by 1 if you play twice as much games
(for instance it is 2 after 2 games, 3 after 4 games, 4 after 8 games, 5 after 16 games etc.).
Why did we choose this formula? 
Because it gives Players who play more often a little bonus, 
but high scores requires good results too (because of "Points per Game").
</p>
<p>For People who understand PHP, here is the formula copied from the source code: </p>
<table><tr><td style="text-align:left">
<pre>
function calcscore($points, $games)//$games is number of played games
{	
    if($games&lt;=0 or $points&lt;=0) return 0;
    $coefficient = 1 + log((float)$games, 2);//logarithm with base 2
    $score =(float)$points* $coefficient /(float)$games;
    return (int)($score*1000);
    //score will be divided by 1000 in ranking
}
</pre>
</td></tr>
</table>

<?php
include "footer1.php";
?>

</body>
</html>