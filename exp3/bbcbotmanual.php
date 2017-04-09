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


<h1>BBCBOT Manual</h1>
<h3>Writing a private Message</h3>
<p>This has nothing to do with bbcbot, but it can be useful if you know this. 
If you are in the lobby, write &quot;/msg playername text&quot; to send "text" to the player "playername" as a private message</p>

<h2>List of commands for bbcbot</h2>
<table>
<tr><th>Command</th><th>Explanation</th><th></th></tr>

<tr>
<td>/msg bbcbot create &lt;gamename&gt; &lt;game title&gt;</td>
<td>Create a game, if you have permission to do so.<br>
[gamename] can be &quot;step1&quot;,&quot;step2&quot;,&quot;step3&quot;,&quot;step4&quot;, or &quot;husc&quot;
</td>
<td></td></tr>
<tr>
<td>/msg bbcbot uptime</td>
<td>Displays the time since bbcbot is running in seconds</td>
<td></td></tr>
<tr>
<td>/msg bbcbot update</td>
<td>Tell bbcbot to update from bbc website</td>
<td></td></tr>
<tr>
<td>/msg bbcbot help</td>
<td>displays short help</td>
</tr>
<tr>
<td>/msg bbcbot tickets &lt;player&gt;</td>
<td>displays tickets of the player. Case-sensitive</td>
</tr>
<tr>
<td>/msg bbcbot rating &lt;player&gt;</td>
<td>displays the rating of the player. Case-sensitive</td>
</tr>
<tr>
<td>/msg bbcbot games &lt;player&gt;</td>
<td>displays the number of played games of the player. Case-sensitive</td>
</tr>
<tr>
<td>/msg bbcbot time</td><td>Display time for BBC time zone</td>
</tr>
<tr>
<td>/msg bbcbot suggest s1</td><td>Suggests Players for a bbc step1 game among the idle players, who have played bbc before</td>
</tr>
<tr>
<td>/msg bbcbot suggest sX</td>
<td>Suggests Players for a bbc step1 game among the idle players, 
who have a ticket for step X (X can be 2,3,4 here).</td>
</tr>
<tr>
  <td colspan="2"><hr /></td>
</tr>
<tr>
  <td colspan="2"><h4>Monthly Cup Commands (allowed to table-admins only):</h4></td>
</tr>
<tr>
<td>/msg bbcbot create mcup <i>&lt;description&gt;</i></td>
<td>Create a monthly cup 1st round table.
</td>
</tr>
<tr>
<td>/msg bbcbot create mcupfinal <i>&lt;description&gt;</i></td>
<td>Create a monthly cup final table.
</td>
</tr>
</table>
<hr />
<p>We plan to do more commands in the future</p>

</table>

<?php
include "footer1.php";
?>

</body>
</html>