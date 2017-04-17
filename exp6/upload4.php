<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$auth1=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc']==2 or $_SESSION['upc']==3)
  {
    $auth1 = 1;
    $user3=$_SESSION['user3'];
   
  }
} //$_COOKIE['PHPSESSID'] != ""
?>


<?php
print '<!DOCTYPE html>
<html>';
require_once($_SERVER['DOCUMENT_ROOT'].'/defines.php');
ini_set('include_path', ROOT_DIR);
require "lib/pthlog.php";
include "head.php";
$regulartaskcount=1;
include "exp2/regulartasks.php";
print "<body>";
include "header1.php";
include "exp5/nav1.php";
?>
 
<h1>Upload Logfile Analysis</h1>
<?php
print "<p>";
//DEBUGINFO HERE
print "</p>";
$error=0;
$errorinfo1="none";

/*if($userpass=="nelly" or $userpass=="supernoob" or $userpass=="creeper" or $userpass=="nahajasaki" or "Jastura"
or $userpass=="Rezos" or $userpass=="l0stman" or $userpass=="MasterG84" or $userpass=="SchlumpfineX"
or $userpass=="akia" or $userpass=="joe4135" or $userpass=="ElmoEGO" or $userpass=="Gary_Ch" or $userpass=="sp0ck" 
or $userpass=="Morfi" or $userpass=="Joe_East") // hardcoded admins :( (TODO)*/

if($auth1==1)
{
		// @check if loglink is valid
		$url = $_POST['loglink'];
		if($url == "" || strpos($url, "log-file-analysis/?ID=") === false) $error = 900;
        
		$gametemp=(int)$_POST['game'];
		$step=floor($gametemp/100000);
		$gameno=$gametemp-$step*100000;
		if($step<1 or $step>4) $error=410;
		if($gameno==0) $gameno=(int)$_POST["gs$step"];
		if($gameno<=0 or $gameno>40000) $error=410;
		

		$fname2="exp6/lf3/g$gameno" . "s$step";
		$fname3=$fname2 . ".html";

		//block game number
		if($error == 0){
			include_once "exp6/func2.php";
			$blockedarray=blocker();
			if(isblocked($blockedarray,$step,$gameno,$user3)==1) $error=418;
			if($error==0) blocker(array($step,$gameno,$user3));
		}
		
}	
else $error=411;
//if($error==0 and !move_uploaded_file($_FILES['uf1']['tmp_name'],$fname6)) $error=412;
if($error==0)
{
		// @XXX: parse log link
		$log = pthlog::process_log($url);
		file_put_contents($fname3, $log["html"]);
		// @TODO: complete results are in array - html code is saved with base64 encoded images - no more seperate pic files needed
}
if($error==0) print "<p>It looks like there was no error with the Logfile Analysis Link</p>";
 
else include("exp2/error.php");

$cont1=0;
if($error==0)
{
		$request = "SELECT MAX(gameno) FROM table1 WHERE step='$step' ";
		$result = mysql_query($request);
		$row = mysql_fetch_array($result);
		if($gameno == $row[0] +1) $cont1=1;
		if(!file_exists($fname3)) $error=417;
		if($gameno == $row[0]) $cont1 = 0;
}
//die("gameno=$gameno");
//die("cont1=$cont1");
if($error==0 and $cont1==0) print "<p><a href=\"exp5/gameslist3.php?step=$step&amp;g=$gameno\">You can check here how it looks</a></p>";

if($error==0 )
{
		// show last uploaded game
		print "<h1> Results Input</h1>";
		
		//$ttext = date("Y-m-d H:i:s",time()-7*43200);
		$request = "SELECT * FROM table1 ORDER BY inputtime DESC 
		LIMIT 1";
		$result = mysql_query($request);
		$c=0;
		while($row = mysql_fetch_object($result))
		{
		
			$c++;
			$gameno = $row->gameno;
			$gstep=$row->step;
			print "<p><b>Last Game input: BBC Game $gameno of Step $gstep</b></p>";
			$pids = array($row->p1,$row->p2,$row->p3,$row->p4,$row->p5,$row->p6,$row->p7,$row->p8,$row->p9,$row->p10);
			print "<table border=1><tr><th>Winner</th>";
			for($i1=2;$i1<11;$i1++) print "<td>Place $i1</td>";
			print "</tr>\n<tr>";
			$pnames=array("","","");
			for($i1=0;$i1<10;$i1++)
			{
				print "<td>";
				$request2 = "SELECT name FROM table2 WHERE playerid=$pids[$i1] AND playerid>1024";
				$result2 = mysql_query($request2);
				while($row2 = mysql_fetch_object($result2))
				{
					print $row2->name ;
					if($i1<3)$pnames[$i1]=$row2->name;
				}
				print "</td>";	
			}
			print "</tr></table>";
			print "<p>Season: <b>";
			print $row->season;
			print "</b>, ";
			$time = $row->datetime;
			
			if(strpos($time,"2000-01-01")===false) $dtt = "<b>" . date("D, d M Y H:i T",strtotime($time)) ."</b>";
			else $dtt = "unknown";
			print "Begin: $dtt, ";
			$time = $row->inputtime;
			if(strpos($time,"2000-01-01")===false) $dtt = "<b>" . date("D, d M Y H:i T",strtotime($time)) ."</b>";
			else $dtt = "unknown";
			print "Input: $dtt</p>";
		}
			
		//die("cont1 = $cont1");
		if($cont1 == 1){
			print '<h2>New Game Input</h2><p> (with some error detection)</p>'
				. '<form action="/exp2/test3.php" method="post"><p>Game start, Date:'; 
		}else if($cont1 == 2){
			print '<h2>Re-Upload of game '.$gameno.'</h2><p> (with some error detection)</p>'
				. '<form action="/exp2/test3.php" method="post"><p>Game start, Date:'
				. '<input type="hidden" name="reupload" value="'.$gameno.'"/>';
		}

		
		$dateval = date("Y-m-d");
		print "<input type=\"Text\" name=\"date\" value=\"$dateval\" maxlength=10 size=10>";
		$hour=(int)date("H");
		
		$timeval="23:59:59";
		if($hour==20 or $hour==21) $timeval="19:30:00";
		if($hour==23) $timeval="21:30:00";
		if($hour==24) $timeval="23:00:00";
		print "Time: <input type=\"Text\" name=\"time\" value=\"$timeval\" maxlength=8 size=8></p>";
		print "Step ";
		for($i=1;$i<5;$i++)
		{
			$ctext="";
			if($step==$i) $ctext=" checked=\"true\"";
			print "<input type=\"radio\" name=\"step\" value=\"$i\"$ctext>$i";
		}
	
	
		if($error==0)
		{
				//for($i1=0;$i1<10;$i1++) if($players[$i1]=="")$players[$i1]="0";
				print "<p>the following data of <b style='color: red'>BBC $gameno Step $step</b> could be read from the logfile analysis:</p>";
				print "<table border=1><tr><th>place</th><th>Name</th></tr>\n";
				foreach($log["result"] as $pos => $player)
				{       print "<tr><td>";
						print $pos;
						print "</td><td>" . $player["player"] . "</td></tr>\n";
				}      
				print "</table>\n";
				foreach($log["result"] as $pos => $player) print "<input type=\"hidden\" name=\"pl[]\" value=\"".$player["player"]."\">\n";
				//if($step > 1){ // @XXX maybe another time - onchange step radio above!
					print '<p>Players that where reserved but missing and should loose tickets (<span style="color: red">Only if step > 1</span>):<br>'
						. '<input type="Text" name="punish[]">'
						. '<input type="Text" name="punish[]"><input type="Text" name="punish[]"><input type="Text" name="punish[]"></p>';
				//}
				print "<input type=\"submit\" value=\"submit data to input page\"> ";
				print "</form>\n";
		}
}
?>
 
<?php
include "footer1.php";
?>
 
</body>
</html>

