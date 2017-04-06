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

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="/exp5/jquery.md5.js" type="text/javascript"></script>
<script type="text/javascript">
$(window).load(function(){$('#fp').val(fp());});

function fp()
{
    var sFP = "";
    sFP+="Resolution:"+window.screen.availWidth+"x"+window.screen.availHeight+"\n";
    sFP+="ColorDepth:"+screen.colorDepth+"\n";
    sFP+="UserAgent:"+navigator.userAgent+"\n";    
    sFP+="Timezone:"+(new Date()).getTimezoneOffset()+"\n";
    sFP+="Language:"+(navigator.language || navigator.userLanguage)+"\n";
    document.cookie="sFP";
    if (typeof navigator.cookieEnabled != "undefined" 
        && navigator.cookieEnabled == true
        && document.cookie.indexOf("sFP") != -1)
    sFP+="Cookies:true\n";
    else
    sFP+="Cookies:false\n";
    sFP+="Plugins:"+jQuery.map(navigator.plugins, function(oElement) 
		{ 
			return "\n"+oElement.name+"-"+oElement.version; 
		});
    return $.md5(sFP);
}
</script>

<h1> Registration for BBC Games </h1>
<!--<p><font color="red">Please note that if you are not invited by an official BBC admin (<a href="exp4/admin1.php">you can see the list of bbc admins here</a>)
the game will probably not count. So make sure that you only join the right games</font></p>-->
<!--
<p><font color="red">ATTENTION! in order to use the new server, you maybe need a new account!
If you already have a forum account on pokerth.net, log in and then go <a href="http://pokerth.net/component/pthranking/?view=activategame">here</a> to activate gaming for your acccount.
If you do not have a forum account on pokerth.net, you can create a gaming account <a href="http://pokerth.net/component/pthranking/?view=registration">HERE</a>.
Take care to use the same nick as your BBC nick during registration.
</font> </p>
-->
<form action="exp5/reg2.php" method="post">
<input type="hidden" name="fp" id="fp" />
<table>
<tr><td>Enter your pokerth Nickname: </td><td>
<?php
include "exp5/func1.php";
$name = $_COOKIE['user1'];
print '<input type="text" name="pname" value="' . $name . '">';
?>
</td></tr>
<tr><td>I am new to BBC: </td>
<td><input type="radio" name="new" value=1>Yes
<input type="radio" name="new" value=0 checked>No</td></tr>

<tr><td><br></td><td></td></tr>
<tr><td colspan=2>I want to play:</td></tr>

<?php
for($i1=1;$i1<5;$i1++)
{
	print "<tr><td>";
	$rarr=getrdates($i1);
	$c1=count($rarr);
	for($i2=0;$i2<$c1;$i2++)
	{
		$datetext2 = date("D, d M Y H:i T",$rarr[$i2]);
		if($i1==4 and $rarr[$i2]==strtotime("2030-04-04 00:00:03"))
		{
			$t3=date("D, d M Y H:i T", strtotime(step4date()));
			$datetext2="unknown (maybe $t3)";
		}
		
		$t1="s$i1" . "t" . $rarr[$i2];
		print <<<E
		<tr><td>
		<input type="checkbox" name="$t1" value=1>Step $i1</td>
		<td><b>$datetext2</b></td></tr>
E;
		
	}
	
	
}
/*
$i1=3;
print "<tr><td>
<input type=\"checkbox\" name=\"s$i1". "d\" value=1>Step $i1</td>";
$retarray=calcnextdate($i1);
print "<td><b>$retarray[1]</b>";
print "<input type=\"hidden\" name=\"plan$i1\" value=\"$retarray[0]\"></td></tr>";
*/

?>


</table>
<input type="submit" name="submit" value="submit registration!">
</form>
<p>Registration will open 36 hours(for step 1) or 7 days(for step 2) before the game. Registration will close 10 minutes before game.</p>
<p><font color="red">Please be in lobby about 5 minutes before the game. If NO player is missing and the table is full the game might start before :30 - if a player is missing the game will start exactly at :30 - admins may decide to wait a few minutes for a missing player though - but that is not guaranteed!</font></p>
<p>Step 1, 2, 3 and 4 start with 10 players, including an admin (or co-admin). </p>
<p>Please: if admins or co-admins ask you to quit the game because of not having enough players on the table (players disconnected at the start or wrong start) do it ... the game won't be reported and so no tickets for the winners. </p>

<?php
include "footer1.php";
?>

</body>
</html>
