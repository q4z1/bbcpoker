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
<h1>Get the BBCode for forum report</h1>
<?php
$step=(int)$_GET['s'];
$gameno=(int) $_GET['g'];
if($step<1 or $step>4)$step=1;
$request="SELECT * FROM table1 WHERE step=$step and gameno=$gameno";
$result = mysql_query($request);
$c=0;
$pnames=array();
while($row = mysql_fetch_object($result))
{
	$c++;
	$pids = array($row->p1,$row->p2,$row->p3,$row->p4,$row->p5,$row->p6,$row->p7,$row->p8,$row->p9,$row->p10);
	$time = strtotime($row->datetime);
	$ttext=date("l, F jS Y - H:i T",$time);
	for($i1=0;$i1<10;$i1++)
	{
		$request2 = "SELECT name FROM table2 WHERE playerid=$pids[$i1] AND playerid>1024";
		$result2 = mysql_query($request2);
		while($row2 = mysql_fetch_object($result2))
		{
			$pnames[$i1]=$row2->name;
		}
	}
}
if($c==1)
{
	print <<<EOF
<h2>Step $step and Game Number $gameno</h2>
<p>Just copy the following code into the forum</p>
<textarea readonly rows=25 cols=80>
[center]
[attachment=1727]logo.jpg[/attachment]
[size=4][b] [color=#bb0000]BBC $gameno Step $step [/color][/b][/size]
$ttext
[br]
[list=1]
[list=1]
[*][url=http://bbc.pokerth.net/exp5/players1.php?id=$pids[0]][b][size=3][color=#444444]$pnames[0][/color][/size][/b][/url]
[*][url=http://bbc.pokerth.net/exp5/players1.php?id=$pids[1]][b][size=3][color=#444444]$pnames[1][/color][/size][/b][/url]
[*][url=http://bbc.pokerth.net/exp5/players1.php?id=$pids[2]][b][size=3][color=#444444]$pnames[2][/color][/size][/b][/url]
[*][url=http://bbc.pokerth.net/exp5/players1.php?id=$pids[3]][b][size=3][color=#444444]$pnames[3][/color][/size][/b][/url]
[*][url=http://bbc.pokerth.net/exp5/players1.php?id=$pids[4]][b][size=3][color=#444444]$pnames[4][/color][/size][/b][/url]
[*][url=http://bbc.pokerth.net/exp5/players1.php?id=$pids[5]][b][size=3][color=#444444]$pnames[5][/color][/size][/b][/url]
[*][url=http://bbc.pokerth.net/exp5/players1.php?id=$pids[6]][b][size=3][color=#444444]$pnames[6][/color][/size][/b][/url]
[*][url=http://bbc.pokerth.net/exp5/players1.php?id=$pids[7]][b][size=3][color=#444444]$pnames[7][/color][/size][/b][/url]
[*][url=http://bbc.pokerth.net/exp5/players1.php?id=$pids[8]][b][size=3][color=#444444]$pnames[8][/color][/size][/b][/url]
[*][url=http://bbc.pokerth.net/exp5/players1.php?id=$pids[9]][b][size=3][color=#444444]$pnames[9][/color][/size][/b][/url]
[/list]
[br]
Congratulations [url=http://bbc.pokerth.net/exp5/players1.php?id=$pids[0]][b][size=5][color=#bb8800]$pnames[0][/color][/size][/b][/url] and [url=http://bbc.pokerth.net/exp5/players1.php?id=$pids[1]][b][size=5][color=#bb8800]$pnames[1][/color][/size][/b][/url]
Bravo [url=http://bbc.pokerth.net/exp5/players1.php?id=$pids[2]][b][size=4][color=#444444]$pnames[2][/color][/size][/b][/url][br]
[url=http://bbc.pokerth.net/exp5/gameslist3.php?step=$step&amp;g=$gameno]
BBC $gameno Step $step Log File Analysis[/url][br]
[url=http://bbc.pokerth.net/exp2/ranking1.php][b][color=#bb0000]Ranking[/color][/b][/url] & [url=http://bbc.pokerth.net/exp6/rating6.php?g=30][b][color=#bb0000]Rating[/color][/b][/url]
[br][/center]</textarea>

	
EOF;
}


else
print "<p>We couldnt find your game</p>";

?>
<h3><a href="https://www.pokerth.net">www.PokerTh.net</a></h3>



<?php
include "footer1.php";
?>

</body>
</html>


