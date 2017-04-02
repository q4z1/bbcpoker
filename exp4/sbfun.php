<?php
/* this is for functions concerning the shoutbox */


function spamscoreuser($user)
{
  $ret=0;
  $d1=file_get_contents("secret/badnames.txt");
  $d2=explode("\n",$d1);
  for($i1=0;$i1<count($d2);$i1++)
  {
    $d3=explode(" ",$d2[$i1],2);
    $weight=(int)$d3[0];
    $word=$d3[1];
    if($word=="") continue;
    $c1=substr_count($user,$word);
    if($c1==0) continue;
    $ret+=$weight;
  }
  return $ret+3;
}

function spamscoreip($ip)
{
  /* TODO
  $ret=0;
  $d1=file_get_contents("secret/badnames.txt");
  $d2=explode("\n",$d1);
  for($i1=0;$i1<count($d2);$i1++)
  {
    $d3=explode(" ",$d2[$i1],2);
    $weight=(int)$d3[0];
    $word=$d3[1];
    $c1=substr_count($user,$word);
    if($c1==0) continue;
    $ret+=$weight;
  }*/
  return 0;
}

function spamscoremsg($msg )
{
  $ret=0;
  $d1=file_get_contents("secret/badwords.txt");
  $d2=explode("\n",$d1);
  for($i1=0;$i1<count($d2);$i1++)
  {
    $d3=explode(" ",$d2[$i1],2);
    $weight=(int)$d3[0];
    $word=$d3[1];
    if($word=="") continue;
    $c1=substr_count($msg,$word);
    if($c1==0) continue;
    $ret+=$weight;
  }
  return $ret+20;
}



function spamscore($msg,$user,$ip)
{
  $len=strlen($msg);
  $ssu=spamscoreuser($user);
  $ssm=spamscoremsg($msg);
  $ssi=spamscoreip($ip);
  if($ssu<0) $ssu=0;
  if($ssm<0) $ssm=0;
  if($ssi<0) $ssi=0;
  $ssu2=100.0/(float)($ssu+100);
  $ssm2=100.0/(float)($ssm+100);
  $ssi2=100.0/(float)($ssi+100);
  $ssl2=min(1.0,(float)$len/400.0);
  $r1=$ssu2*0.35+$ssm2*0.45+$ssmi2*0.1;
  $r1+=0.1*$ssl2;
  $r2=(int)($r1*10000);
  if($r2>=9999) return 9999;
  if($r2<=0) return 0;
  return $r2;
}


function msg_substitute($msg)
{
  $msg  = str_replace("&", "&amp;", $msg);
  $msg  = str_replace("<", "&lt;", $msg);
  $msg  = str_replace(">", "&gt;", $msg);
  //$msg  = str_replace("\n", "<br>", $msg);
  $msg  = preg_replace("/\\n/", "<br>",$msg,16);
  $i2   = 0;
  //$url=array();
  for ($i1 = 0; $i1 < 6; $i1++) {
    $i3 = strpos($msg, "http://", $i2);
    $i4 = strpos($msg, "https://", $i2);
    if ($i3 === false)
      $i3 = $i4;
    if ($i4 === false)
      $i4 = $i3;
    if ($i3 === false)
      break;
    if ($i4 < $i3)
      $i3 = $i4;
    $i2 = strpos($msg, " ", $i3);
    $i5 = strpos($msg, "<br>", $i3);
    if ($i2 and $i5 and $i2 > $i5)
      $i2 = $i5;
    if ($i2 === false)
      $i2 = $i5;
    //print "<p>::$i2::$i3::$i4::$msg</p>\n";
    if ($i2 === false)
      $i2 = strlen($msg);
    $url = substr($msg, $i3, $i2 - $i3);
    $msg = substr($msg, 0, $i3) . "<a href=\"$url\" target=\"_blank\">$url</a>" . substr($msg, $i2, strlen($msg) - 1);
    $i2  = $i2 + 30;
  } //$i1 = 0; $i1 < 6; $i1++
  return $msg;
}



function message2html($msg,$user,$timestamp,$setting,$id,$amode)
{
  //print "<p> ($setting , $amode ) </p>";
  if ($amode != 1 and $setting == 1) {
    return  "<p><small>#$id | $timestamp | </small>[Hidden Admin Message]</p>";
  } //$amode != 1 and $setting == 1
  $user = str_replace("&", "", $user);
  $user = str_replace("<", "", $user);
  $user = str_replace(">", "", $user);
  $user = str_replace("\n", "", $user);
  $user = str_replace("fuck", "luck", $user);
  $msg = msg_substitute($msg);

  if ($setting == 4) {
    $hash    = md5("$timestamp" + "useifhwuief" + "$user");
    $int     = 0;
    $int     = $int * 16 + hextodec(substr($hash, 1, 1));
    $int     = $int * 16 + hextodec(substr($hash, 2, 1));
    $int     = $int * 16 + hextodec(substr($hash, 3, 1));
    $int     = $int * 16 + hextodec(substr($hash, 4, 1));
    $int     = $int * 16 + hextodec(substr($hash, 5, 1));
    $int     = $int * 16 + hextodec(substr($hash, 6, 1));
    $seconds = 500 + $int % 11000;
    $now     = time();
    $then    = strtotime($timestamp);
    if ($now - $then > $seconds)
      return;
  } //$setting == 4
  $userprint = "<b>$user</b> wrote:";
  $out="";
  if ($setting == 3)
    $userprint = "<span style=\"color:#ee1155\"><b>$user</b></span> wrote:";
  if ($setting == 1)
    $userprint = "<span style=\"color:#ee1155\"><b>$user</b></span> wrote (only for admins):";
  if (($setting >= 1 and $setting <=4 and $amode!=1 )or ($amode==1 and $setting!=2))
    $out .= "<p><small>#$id | $timestamp | </small> $userprint<br>";
  if ($setting == 2 and $amode==1)
  {
    $ip="0.0.0.0"; // TODO
    $ss1=spamscore($msg,$user,$ip);
    $deletelink="/exp4/shoutbox4.php?action=1&msgid=$id";
    $delete2="<a href=\"$deletelink\" target=\"_blank\">delete</a>";
    $out .= "<p><small>#$id | $timestamp | score=$ss1 | $delete2 |</small> $userprint<br>";
  }
  if ($setting == -1 and $amode==3)
  {
    $ip="0.0.0.0";
    $ss1=spamscore($msg,$user,$ip);
    $restorelink="/exp4/shoutbox4.php?action=3&msgid=$id";
    $restore2="<a href=\"$restorelink\" target=\"_blank\">restore</a>";
    $userprint = "<b>$user</b> wrote [DELETED]:";
    $out .= "<p><small>#$id | $timestamp | score=$ss1 | $restore2 |</small> $userprint<br>";
    $out .= "$msg </p>\n";
    return $out;
  }
  if ($setting == 1)
    $out .= "<span style=\"background-color:#99ffcc\">$msg</span></p>\n";
  if ($setting ==2 or $setting==3 or $setting==4)
    $out .= "$msg </p>\n";
  return $out;
}

function deletionguidelines()
{

  return <<<E

<h3> Guidelines for deletion of messages</h3>
<p>You can delete Posts for one of the following reasons:</p>
<ul>
<li>Insults</li>
<li>Leaking of personal information</li>
<li>Trolling, spam, advertisement</li>
<li>Not really relevant for BBC (depending on the total amount of these types of messages)</li>
<li>Multiple redundant posts from the same person (i.e. if they ask the same question over and over again)</li>
</ul>

<p>Please dont delete a message if:</p>
<ul>
<li>The person is asking a question about BBC or pokerTH in good faith</li>
<li>Comments that criticize BBC (if they are polite)</li>
</ul>

<p>Remember, the primary purpose of the shoutbox is that users can find relevant information quickly, can ask questions about BBC,
and that admins can help each other with their job and discuss administrative policies.</p>

<p>To view and restore deleted messages, <a href="exp4/shoutbox3.php">go here</a>.
E;

}

?>
