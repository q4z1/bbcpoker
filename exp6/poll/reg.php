<?php
//set cookie
if($_POST['submit']!="")
{
	$user = $_POST['pname'];
	setcookie("user1","$user",0,'/');
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

<h1>Valentine's Day Cup Registration</h1>

<?php
$p=(int)$_GET['p'];
$r=(int)$_GET['r'];

// r=0 - normal question mode
// r=1 - submit answers
// r=2 - show results
// r=3 - show raw data


$fname1="exp6/poll/questions$p.txt";
$fname2="exp6/poll/answers$p.txt";

//$ris0link="<a href=\"exp6/poll/reg.php?p=$p&amp;r=0\">here is the poll</a>";
//$ris2link="<a href=\"exp6/poll/reg.php?p=$p&amp;r=2\">here are the results</a>";
//$ris3link="<a href=\"exp6/poll/reg.php?p=$p&amp;r=3\">here is the raw data of results</a>";
	


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
			$printtext .= <<<E
			<input type="radio" name="Q$qnumber" value="$t4">$t2<br>
			
E;
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
			$printtext .= <<<E
			<p>$t2 <input type="text" name="Q$qnumber" size=25 maxlength="99"></p>
			
E;
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
		print <<<E
		<p>Thank you for your participation! </p>
E;
	}
	if($r==1 and $error!=0)
	{
		include "exp2/error.php";
	}
	
	$submittext="<p><b>This Poll cannot be answered </b>yet</p>";
	if($enable==1)$submittext="<p><input type=\"submit\" value=\"Submit\"></p>";
	$printtext .= <<<E
	<input type="hidden" name="md5" value="$md5">
	$submittext
	</form>
E;

	$bbconlytext="";
	if($bbconly==1) $bbconlytext=" poker-heroes nickname";
	$printtext1= <<<E
	
	
	<form method="post" action="exp6/poll/reg.php?p=$p&r=1">
	<p>Saturday, Feb 13st 21:00 CET<br><br>Enter your poker-heroes nickname $bbconlytext: <input type="text" name="pname" value="$user"></p>
	
E;

$printtext=$printtext1 . $printtext; // . "<p>$ris2link  || $ris3link</p>" ;
//	$showtext .= "<p>$ris0link  || $ris3link</p>";
if($r==0) print $printtext;
//if($r==2) print $showtext;

	

}
if(!file_exists($fname1))
{
	print "<p>We could not find, which poll do you mean.</p>";
}
//if($r==3)
//{
	
//	if(file_exists($fname2)) $ft = file_get_contents($fname2);
//	print "\n<h3>Raw Data</h3><pre>\n$ft\n</pre>\n <p>$ris0link || $ris2link</p>";
	
	
//}

?>

<?php
include "footer1.php";
?>
</body>
</html>
