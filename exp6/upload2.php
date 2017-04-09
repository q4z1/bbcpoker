<?php
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

		for($i1=1;$i1<4;$i1++) // go through all the 3 files that are being sent
		{      
			$finfo=$_FILES["uf$i1"];
			if($finfo['error']!=0){$error=400+$i1;$errorinfo1=$finfo['error'];}
			if($finfo['size']<10000 or $finfo['size']>500000) {$error=433+$i1; $errorinfo1=$finfo['size'];}
      //if($finfo['size']<5000 or $finfo['size']>500000) {$error=433+$i1; $errorinfo1=$finfo['size'];}
		}
		$gametemp=(int)$_POST['game'];
		$step=floor($gametemp/100000);
		$gameno=$gametemp-$step*100000;
		if($step<1 or $step>4) $error=410;
		if($gameno==0) $gameno=(int)$_POST["gs$step"];
		if($gameno<=0 or $gameno>40000) $error=410;
		$fname1=basename($_FILES['uf1']['name']);
		$temp=explode(".",$fname1);
		$ftype=$temp[count($temp)-1];
		if($ftype!="html" and $ftype!="htm") {$error=407;$errorinfo1=$ftype;}
		$hcash_name=basename($_FILES['uf2']['name']);
		if($error==0 and $hcash_name!="hand_cash.png" and $hcash_name!="hand_cash.php" and $hcash_name!="hand_cash.php.png"){$error=408;$errorinfo1=$hcash_name;}
		$psize_name=basename($_FILES['uf3']['name']);
		if($error==0 and $psize_name!="pot_size.png" and $psize_name!="pot_size.php" and $psize_name!="pot_size.php.png" ){$error=409;$errorinfo1=$psize_name;}
		$fname2="exp6/lf3/g$gameno" . "s$step";
		$fname3=$fname2 . ".html";
		$new_hcash_path=$fname2 . "_hand_cash.png";
		$new_psize_path=$fname2 . "_pot_size.png";
		$fname6="exp6/lf3/temp1.html";
		//block game number
		include_once "exp6/func2.php";
		$blockedarray=blocker();
		if(isblocked($blockedarray,$step,$gameno,$user3)==1) $error=418;
		if($error==0) blocker(array($step,$gameno,$user3));
		
}	
else $error=411;
if($error==0 and !move_uploaded_file($_FILES['uf1']['tmp_name'],$fname6)) $error=412;
if($error==0)//checkk if expanded+upload png
{      
		$fcontent1=file_get_contents($fname6);
		$i1=strpos($fcontent1,"expand all");
		$i2=strpos($fcontent1,"expand all",$i1+10);
		if($i1===false or ($i2!==false and $i2>$i1)) $error=413;
		$i1=strpos($fcontent1,"collapse all");
		$i2=strpos($fcontent1,"collapse all",$i1+10);
		if($i1===false or $i2===false) $error=413;
		if(!move_uploaded_file($_FILES['uf2']['tmp_name'],$new_hcash_path)) $error=414;
		if(!move_uploaded_file($_FILES['uf3']['tmp_name'],$new_psize_path)) $error=415;
}
if($error==0)//clean logfile
{
		//START CREEPER PART
		$file1 = fopen($fname6,"r");
		$newcontents="";
		$deleteline=false;
		for($i1=0;$i1<30000;$i1++)
		{
				$line = fgets($file1);
				if($line===false)break;
				if (strpos($line,"kunenalatest")!==false)//delete wrong lines in <head>
				{
						$newcontents=$newcontents . $line . "\n</head>\n<body>\n";
						$deleteline=true;
				}
				if (strpos($line, "social_bookmarks")!==false) //delete wrong lines in <body>
				{
						$line="";
						$deleteline=false;
				}
				if (strpos($line, "This log file analysis is still valid")!==false //delete special lines in <body> (advert)
						or strpos($line, "advert")!==false or strpos($line, "show_ads.js")!==false)
				{
						$line="";
				}
			   
				if(strpos($line,"at least one"))//delete all lines for right pane
				{
						$newcontents = $newcontents . $line . "
</tbody></table>
</div></div></div></div></div>
</body></html>";
						$deleteline=true;
				}
				if(!$deleteline)
				{
						$newcontents=$newcontents . $line;
				}
		}//END creeper part
 
		$alreadydone=true;
		$cleanar1=explode("<script",$newcontents);
		if(count($cleanar1)>1)$alreadydone=false;
		$newcontents2="";
		for($i1=0;$i1<count($cleanar1) and !$alreadydone;$i1++)
		{
				$cleanar2=explode("</script>",$cleanar1[$i1],2);
				$newcontents2 = $newcontents2 . $cleanar2[count($cleanar2)-1];
		}
		$cleanar1=explode("<form",$newcontents2);
		//if(count($cleanar1)>1)$alreadydone=false;
		$newcontents2="";
		for($i1=0;$i1<count($cleanar1) and !$alreadydone;$i1++)
		{
				$cleanar2=explode("</form>",$cleanar1[$i1],2);
				$newcontents2 = $newcontents2 . $cleanar2[count($cleanar2)-1];
		}
	   
	   
		//replace link names to .png and others in html code
		$stfa=array($hcash_name,$psize_name,"master-2668b9c13a2f3b4535397489f969f1ea.css","format.css");
		//for($i1=2;$i1<10;$i1++) $stfa[]="line_00$i1.png";
		//$stfa[]="line_010.png";
		//$i2=count($stfa);
		$fname7=array(basename($new_hcash_path), basename($new_psize_path));            for($i1=0;$i1<4 and $error==0;$i1++)
		{
				$i3=strpos($newcontents2,$stfa[$i1]);
				if($i3===false) {$error=420;break;}
				$i4=$i3-200;
				if($i4<0)$i4=0;
				$i6=0;
				for(;$i4<$i3;){$i6=$i4;$i4=strpos($newcontents2,'"',$i4+1);}
				if($i6==0 or $i6>$i3 or $i4<$i3) {$error=421;break;}
				//$i4=strrpos($newcontents2,'"',$i3+1);
				//$i5=strpos($newcontents2,'"',$i3+1);
				if($i6===false or $i4===false) $error=422;
				if($i1==0 or $i1==1) $newcontents2=substr_replace($newcontents2,"$fname7[$i1]",$i6+1,$i4-$i6-1);
				if($i1>1) $newcontents2=substr_replace($newcontents2,"falaf/$stfa[$i1]",$i6+1,$i4-$i6-1);
		}
		$strs=explode(".", $hcash_name);
		$ftype=$strs[count($strs)-1];
		$lipos=array();
		$linames=array("line.$ftype");
		for($i1=2;$i1<10;$i1++) $linames[]="line_00$i1.$ftype";
		$linames[9]="line_010.$ftype";
		$ofs=0;
		print "<pre>";
		$secondtrychrome=0;
		for($i1=0;$i1<10;$i1++)
		{
				//var_dump($lipos);
				for($i2=$i1;$i2<10;$i2++)
				$lipos=array(0,0,0,0,0,0,0,0,0,0);
				for($i2=0;$i2<10;$i2++)
				{
						$lipos[$i2]=strpos($newcontents2,$linames[$i2],$ofs);
						if($lipos[$i2]===false) continue;
						if($lipos[0]>$lipos[$i2] or $lipos[0]===false)
						{
								$temp=$lipos[$i2];
								$lipos[$i2]=$lipos[0];
								$lipos[0]=$temp;
						}
				}
				$i3=$lipos[0];
				$i4=$i3-100;
				if($i4<0)$i4=0;
				$i6=0;
				for(;$i4<$i3;){$i6=$i4;$i4=strpos($newcontents2,'"',$i4+1);}
			   
				//print "($i1,$i3,$i4,$i6)";
				if($i6==0 or $i6>$i3 or $i4<$i3) 
				{	
					if($secondtrychrome==1){$error=4160+$i1;break;}
					$secondtrychrome=1;
					$linames=array("line.php");
					for($i2=1;$i2<10;$i2++) $linames[$i2]="line($i2).php";
					$i1--;
					continue;
				}
			   
				$i7=$i1+1;
				$newcontents2=substr_replace($newcontents2,"falaf/square"."$i7.png",$i6+1,$i4-$i6-1);
				$ofs=$i6+1;
		}
		print "</pre>";
		fclose($file1);
		if(!$alreadydone)
		{
				$file2=fopen($fname3,"w");
				fwrite($file2,$newcontents2);
				fclose($file2);
		}
		else $error=423;
}
if(file_exists($fname6)) unlink($fname6);
if($error==0) print "<p>It looks like there was no error with the upload of Logfile Analysis</p>";
 
else include("exp2/error.php");
$cont1=0;
if($error==0)
{
		$request = "SELECT MAX(gameno) FROM table1 WHERE step='$step' ";
		$result = mysql_query($request);
		$row = mysql_fetch_array($result);
		if($gameno == $row[0] +1) $cont1=1;
		if(!file_exists($fname3)) $error=417;
}
if($error==0 and $cont1==0) print "<p><a href=\"exp5/gameslist3.php?step=$step&amp;g=$gameno\">You can check here how it looks</a></p>";
 
if($error==0 and $cont1==1)
{

	//TRY TO READ NAMES FROM LOGFILE
		$ft = file_get_contents($fname3,"r");
		if($ft===false) $error=417;
		$players = array("","","","","","","","","","");
		$i1=0;
		$p1 = 0;
		while($i1 <100)
		{
				$i1++;
				$p1 = strpos($ft,"<table",$p1+1);
				$p2 = strpos($ft,"class=\"data\"",$p1);
				$p3 = strpos($ft,">",$p1);
				if($p2<$p3 and $p2>$p1)
				{
						$p4 = strpos($ft,"</table>",$p1);
						break;
				}
		}
		$p2 = $p1+1;
		$i1 = 0;
		while( $i1<100)
		{
				$p6 = strpos($ft,"class=\"data player\"",$p2+1); // TODO: does this really appear in logfile?
				if($p6>$p4 or $p6<$p1) $p2 = strpos($ft,"class=\"player\"",$p2+1);
				else $p2 = $p6;
				if($p2>$p4 or $p2<$p1)  break;
				$i1++;
				$p3 = strpos($ft,">",$p2);
				$p6 = strpos($ft,"<", $p2);
				$p5 = strpos($ft,"</td>", $p2);
				if($p6 != $p5) 
				{
					$p7=strpos($ft,"<span id",$p2);
					if($p7 != $p6 or $p7>$p5) {$i1--; continue;}
					$p8=strpos($ft,">",$p7);
					$p9=strpos($ft,"</span>",$p7);
					if($p8<$p9 and $p9+6<$p5)
					{
						$players[$i1-1]=substr($ft, $p8+1,$p9-$p8-1) . substr($ft, $p9+7,$p5-$p9-7);
						continue;
					}
				}
				if($p3 > $p5) $error=424;
				$players[$i1-1] = substr($ft, $p3+1,$p5-$p3-1);
		}
		if($error==0)
		{
				//for($i1=0;$i1<10;$i1++) if($players[$i1]=="")$players[$i1]="0";
				print "<p>the following data of BBC $gameno Step $step could be read from the logfile analysis:</p>";
				print "<table border=1><tr><th>place</th><th>Name</th></tr>\n";
				for($i1=0;$i1<10;$i1++)
				{       print "<tr><td>";
						print $i1+1;
						print "</td><td>" . $players[$i1] . "</td></tr>\n";
				}      
				print "</table><br><br>\n";
				print "<form action=\"exp2/test1.php\" method=\"post\">\n";
				for($i1=0;$i1<10;$i1++) print "<input type=\"hidden\" name=\"pl[]\" value=\"$players[$i1]\">\n";
				print "<input type=\"hidden\" name=\"step\" value=\"$step\">\n";
				print "<input type=\"submit\" value=\"submit data to input page\"> ";
				print "</form>\n";
				print "<p>Note: you can change the results after this point (if the nicknames are wrong, or unusual letters (è, ä, ö, ê etc. )dont worry</p>";
		}
}
?>
 
<?php
include "footer1.php";
?>
 
</body>
</html>

