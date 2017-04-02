<?php
//set cookie
if($_POST['submit']!="")
{
	$user = $_POST['pname'];
	setcookie("user1","$user",0,'/');
  if($_POST['fp'] == "" || !array_key_exists("fp", $_POST)){
    die("enable JavaScript!");
  }
  $blocked = array(
	"ee67b66ea5044987e5bda9eb51dab3f0",
	'b56d86292e9fff95aefcf857dc85f2ac',
	'bf2c7849af7b7b4e74cfff439890716c',
	'8f3cfbb0d5587abadd9e9b03431936d3',
	"1d4b76c146368bd1e49af18e19b0d3a5",
	"e52cea203e70d2f447b4bf0407604c39",
    "e191a6152d056257f442c834aebd3adc", // not sure!!!
    "31361de2dc048a309fda5d2f7ae78f1d", // Nelly & Ladybird fake post
    "3ee0e515bb949d3f1ad785d57778f31f", // Nelly again
    // "b94d3b4516d79c852d6cef4b124918b7", // brak, Anchorman, anti-brak-union
    "76eb445e40aae869d958302ba6b9559c", // false signups
    "9cc897b5fb751d75767f571516e57e24", // Legion
    "fa97f184b0cdb3fe5b298d393ebcd967", // Anonymous
    "d39bfa71ca4a502b6d7d11b842c0d94c",
    "11f0e936a54e65a22491576019b22201",
    "7a031391001a50a5f3ff8c0aade82bc7",
    "0627adb889f17d406ed5689bce39eb1c", // Rezos
    "0aa6f14746a83d92c2e0312d32f1f3a0", // Rezos
    "0bb58d54df45a7dba8612e87cb5e1659", // Rezos
    "1647964e9c68ee3b7e2aff1ba1a95b69", // Rezos
    "180eb522fb9898a76de3341b40dc9e36", // Rezos
    "1a48b9c187148f7b297b07cd2afe09f7", // Rezos
    "1dcea9043c26663b44ec9471d4d904a6", // Rezos
    "1e0a585204d9ba8a64ed673ff25a5693", // Rezos
    "2c9515fa5045a2f1f3df699c37624889", // Rezos
	"3552bf1cfb13c132bd3b246423b0b9ae", // Rezos
    "39d08b2805ab6491da2254b7920676f4", // Rezos
    "4915ee0782a35af1d2b0963acd8be6ef", // Rezos
    "506d0cf089502118eb9036275ef425a2", // Rezos
    "6370f1156b1d4e79ff81fab545ba1493", // Rezos
    "6d3a952285ab00f9eb4fbd72683a5e57", // Rezos
    "72f64d04a57b3889975a4f55329453e5", // Rezos
    "775d60f1c98463f4d0f4ad56a8fdbb2c", // Rezos
    "82d14c8a824a51555a05f9e42bf01272", // Rezos
    "95c46d0f95eaf0a174b732fd36aed301", // Rezos
    "ad1d422fafd4f48533cc34d8dfbb5bb2", // Rezos
    "d4a101a990c6f230958d789243c46843", // Rezos
    "e71c5c7fc0683a2ba0fe9b462e86ffdb", // Rezos
    "e950363819f117cfedf6153c9086c2c6", // Rezos
	"6370f1156b1d4e79ff81fab545ba1493", // Rezos
  );
  if(in_array($_POST['fp'], $blocked)){
    die('IP blocked.');
  }
}

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
<script type="text/javascript" src="/exp4/jquery-1.11.1.min.js"></script>
<script src="/exp5/jquery.md5.js" type="text/javascript"></script>
<h1>Poll Tool / Public Voting Tool</h1>
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
<?php
$p=(int)$_GET['p'];
$r=(int)$_GET['r'];

// r=0 - normal question mode
// r=1 - submit answers
// r=2 - show results
// r=3 - show raw data


$fname1="exp6/poll/questions$p.txt";
$fname2="exp6/poll/answers$p.txt";
$fname3="exp6/poll/fp$p.txt";

$ris0link="<a href=\"exp6/poll/poll.php?p=$p&amp;r=0\">here is the poll</a>";
$ris2link="<a href=\"exp6/poll/poll.php?p=$p&amp;r=2\">here are the results</a>";
$ris3link="<a href=\"exp6/poll/poll.php?p=$p&amp;r=3\">here is the raw data of results</a>";
	


if(file_exists($fname1) and $r>=0 and $r<=2)
{
 
	$error=0;
	
	
	$savetext="";
	$printtext="";
	$enable=1;
	$bbconly=0;
	$scheme=file_get_contents($fname1);
	$md5 = md5($scheme);
	$rows=explode("\n", $scheme);
	$cr = count($rows);
	//$radioquestion=0;
	
	if($r==1)
	{
		$pname=$_POST['pname'];
		$pname2=mysql_real_escape_string($pname);
		$request="SELECT * FROM table2 WHERE name='$pname2' AND alltimegames > 20";
		
		
		$result=mysql_query($request);
		$c=0;
		while($row=mysql_fetch_object($result))
		{
			$c++;
		}
		if($c!=1) $error=601;
		if($md5 != $_POST['md5']) $error=602;
		if(strpos($pname,"::")!== false) $error=603;
		$t1="";
		if(file_exists($fname2))$t1=file_get_contents($fname2);
		if(strpos($t1,"\n$pname")!==false) $error=604;
		$savetext .= "$pname::";
	}
	
	if($r==2)
	{
		if(file_exists($fname2))$ft= file_get_contents($fname2);
		$votes1=array();
		$votes2=array();
		$votes3=array();
		$t1=explode("\n",$ft);
		$c1=count($t1);
		$i2=0;
		for($i1=0;$i1<$c1;$i1++)
		{
			$t2=explode("::",$t1[$i1]);
			$c2=count($t2);
			if($c2<3) continue;
			$name=$t2[0];
			for($i2=1;$i2<$c2-3;$i2++)
			{
				$t3=$t2[$i2];
				if(strpos($t3,"Q")!==0) continue;
				$t4=strpos($t3,"A");
				$t5=strpos($t3,"|");
				if($t5!==false) 
				{	
					$t6=0;
					$t6=substr($t3,0,$t5);
					if(!array_key_exists($t6,$votes1))
					{
						$votes1[$t6]=array();
					}
					$isalr=0;
					for($i3=0;$i3<count($votes1[$t6]);$i3++)
					{	if($votes1[$t6][$i3]==$t3)$isalr=1;
					}
					if($isalr==0)$votes1[$t6][]=$t3;
				}
				if(!array_key_exists($t3,$votes2)) 
				{

					$votes2[$t3]=0;
					$votes3[$t3]=array();
				}
				$votes2[$t3]++;
				$votes3[$t3][]=$name;
			}
		}
	}
	$showtext="<h2>Results</h2>\n";
	
	$qnumber=0;
	$anumber=0;
	for($i1=0;$i1<$cr;$i1++)
	{
		$t1=strpos($rows[$i1],"//");
		if($t1===0) continue;
		$t1=strpos($rows[$i1],"TEXT:");
		if($t1===0)
		{
			$t2=substr($rows[$i1],5);
			$t3="<p>$t2</p>\n";
			$printtext .= $t3;
			$showtext .= $t3;
			
		}
		$t1=strpos($rows[$i1],"QR:");
		if($t1===0)
		{
			$qnumber++;
			$t2=substr($rows[$i1],3);
//			$radioquestion=1;
			$anumber=1;
			$t3="<p>$t2</p>\n";
			$printtext .= $t3;
			$showtext .= $t3;
			continue;
		}
		$t1=strpos($rows[$i1],"A:");
		if($t1===0 and $anumber>0)
		{
			$t2=substr($rows[$i1],2);
			$t4="Q$qnumber"."A$anumber";
			$printtext .= <<<E1
			<input type="radio" name="Q$qnumber" value="$t4">$t2<br>
			
E1;
			$showtext .= "&bull; $t2 - <b>";
			$t5=(int)$votes2[$t4];
			if($t5==1) $showtext .=  "1 Vote</b> ";
			else $showtext .=  "$t5 Votes</b> ";
			if($votes2[$t4]>0)
			{	
				$showtext .= "(" . implode($votes3[$t4],", ") . " ) ";
			}
			$showtext .= "<br>";
			$t3=$_POST["Q$qnumber"];
			if($t3=="$t4") $savetext .= "$t4::";
			$anumber++;

		}
		$t1=strpos($rows[$i1],"QT:");
		if($t1===0)
		{
			$anumber=0;
			$qnumber++;
			$t2=substr($rows[$i1],3);
			$printtext .= <<<E2
			<p>$t2 <input type="text" name="Q$qnumber" size=25 maxlength="99"></p>
			
E2;
			$showtext .="<p>$t2</p>\n<ul>\n";
			$c1=count($votes1["Q$qnumber"]);
			for($i2=0;$i2<$c1;$i2++)
			{
				$t4=$votes1["Q$qnumber"][$i2];
				$t6=explode("|",$t4,2)[1];
				$showtext .= "&bull; $t6 - <b>";
				$t5=(int)$votes2[$t4];
				if($t5==1) $showtext .=  "1 Vote</b> ";
				else $showtext .=  "$t5 Votes</b> ";
				if($votes2[$t4]>0)
				{	
					$showtext .= "(" . implode($votes3[$t4],", ") . " ) ";
				}
				$showtext .= "<br>";
			}
			//$showtext .= "</ul>";
			$t3=$_POST["Q$qnumber"];
			$t3=str_replace("::", " : : ",$t3);
			if($t3=="") $t3="(no text)";
			$savetext .= "Q$qnumber|$t3::";
		}
		$t1=strpos($rows[$i1],"DISABLE");
		if($t1===0) $enable=0;
		$t1=strpos($rows[$i1],"BBCONLY");
		if($t1===0) $bbconly=1;
	}
	/*
	print "<pre>";
	var_dump($votes1);
	print "</pre>";
	*/
	if($r==1 and $enable==0) $error=605;
	$md5part=substr($md5,0,7);
	$savetext = "\n" . $savetext .  date("Y-m-d H:i:s") . "::$md5part::0";
	
	if($bbconly==0 and $error==601) $error=0;
	
	
	if($r==1 and $error==0)
	{
		$file=fopen($fname2,"a");
		fwrite($file,$savetext);
		fclose($file);
		$file=fopen($fname3,"a");
		fwrite($file,$savetext . "|fp:".$_REQUEST['fp']);
		fclose($file);
		print <<<E3
		<p>Thank you for your participation! <a href="exp6/poll/poll.php?p=$p&r=2">Click here to see the results</a></p>
E3;
	}
	if($r==1 and $error!=0)
	{
		include "exp2/error.php";
	}
	
	$submittext="<p><b>This Poll cannot be answered </b>yet</p>";
	if($enable==1)$submittext="<p><input type=\"submit\" name=\"submit\" value=\"Submit\"></p>";
	$printtext .= <<<E4
	<input type="hidden" name="md5" value="$md5">
	$submittext
	</form>
E4;

	$bbconlytext="";
	if($bbconly==1) $bbconlytext=" (your BBC nickname)";
	$printtext1= <<<E5
	<p><small>Note: In this Poll, everybody can see what you choose as answers. that way you can check if your vote is counted correctly.</small></p>
	
	<form method="post" action="exp6/poll/poll.php?p=$p&r=1">
  <input type="hidden" name="fp" id="fp" value="" />
	<p>Enter your Name $bbconlytext: <input type="text" name="pname" value="$user"></p>
	
E5;
	$printtext=$printtext1 . $printtext . "<p>$ris2link  || $ris3link</p>" ;
	$showtext .= "<p>$ris0link  || $ris3link</p>";
	if($r==0) print $printtext;
	if($r==2) print $showtext;

}
if(!file_exists($fname1))
{
	print "<p>We could not find, which poll do you mean.</p>";
}
if($r==3)
{
	
	if(file_exists($fname2)) $ft = file_get_contents($fname2);
	print "\n<h3>Raw Data</h3><pre>\n$ft\n</pre>\n <p>$ris0link || $ris2link</p>";
	
	
}

?>

<?php
include "footer1.php";
?>
</body>
</html>
