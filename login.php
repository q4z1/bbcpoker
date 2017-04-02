<?php
ini_set('allow_url_fopen',1);
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 12);
ini_set('session.use_only_cookies','1 ');
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 13);
//ini_set('session.save_path', '/home/yoursite/sessions');
session_start();

ini_set('include_path', '/home/www/bbc/');
$regulartaskcount=1;
include "exp2/regulartasks.php";

$goodlogin=0;
$wrongpassword=0;
$loginattempt=(int)$_GET['loginattempt'];
$logoutattempt=(int)$_GET['logoutattempt'];
if(isset($_SESSION['upc']) && 0<(int)$_SESSION['upc'])
{
  $upc=(int)$_SESSION['upc'];
  $user3=$_SESSION['user3'];
}
else $upc=0;
if($upc==1 or $upc==2 or $upc==3 or $upc==4 or $upc==7 or $upc==8) $goodlogin=1;


if($loginattempt>=1)
{
  $hash1=md5($_POST['thepassword']);
  $nick=$_POST['nick'];
  $nick2=mysql_real_escape_string($nick);
  $request="SELECT salt,hash,name,class FROM admins WHERE name='$nick2' LIMIT 1";
  $result=mysql_query($request);
  while($row=mysql_fetch_object($result))
  {
	$salt=$row->salt;
	$hash3=$row->hash;
	$_SESSION['upc']=(int)$row->class;
	$_SESSION['user3']=$row->name;
	$upc=(int)$_SESSION['upc'];
	$user3=$_SESSION['user3'];
  }
  $hash5=md5($salt . $hash1);
  if($hash3 === $hash5 and $upc!=9) $goodlogin=1;
  else 
  {  
    $wrongpassword=1; 
    $goodlogin=0;
    session_destroy();
  }
}

if($logoutattempt>=1)
{
  $goodlogin=0;
  session_destroy();
}

?>

<?php
print '<!DOCTYPE html>
<html>';
include "head.php";
print "<body>";
include "header1.php"; 
include "exp5/nav1.php";
?>

<?php

//if($successfullogin==1) print "<h2>password is correct</h2>";
if($wrongpassword==1) print "<h2>Wrong password (or user name)</h2>";
if($logoutattempt>=1) print "<h2>You should be logged out now</h2>";

if($goodlogin==0)
print '<h1>Login</h1>
<form action="login.php?loginattempt=1" method="post">
<p>
Username:<input type="text" name="nick" value=""> <br>
Password:<input id="password" type="password" name="thepassword"><br>
<input type="submit" name="login" value="Login"></p>
</form>
<p>Note: you need to have a bbc account to login here. if you have no BBC account yet, you can
<a href="exp4/createaccount.php">click here to create a bbc account</a></p>
<p>If you fail to login then send a pm or mail to supernoob.<br>
Do not tell supernoob your password in that case.
</p>
';

if($goodlogin==1)
{
  $li="";
  if($upc==1)
  $li=<<<E
<li><a href="exp6/upload1.php">Upload and Input a BBC Game</a></li>
<li><a href="exp2/test1.php">Input without logfile</a></li>
<li><a href="exp4/shoutbox1.php?admin=1">Talk to people</a></li>	
<li><a href="exp5/reg8.php">Check registrations</a></li>
<li><a href="exp4/input22.php">Edit Tickets</a></li>
<li><a href="exp2/controlpanel.php">Check Database (!)</a></li>
<li><a href="exp5/awards/awinput1.php">Add Awards </a></li>
<li><a href="exp4/admin2.php">Manage BBC Accounts</a></li>
<li><a href="exp3/bbcbotmanual.php">View bbcbot commands</a></li>
<li><a href="exp6/dates1.php">Create dates</></li>
<li><a href="exp6/dates3.php?d=0">View dates</></li>
<li><a href="exp6/dates3.php?d=1">Delete dates</></li>
<li><a href="exp4/shoutbox3.php">View deleted Messages</li>
E;
  if($upc==2)
  $li=<<<E
<li><a href="exp6/upload1.php">Upload and Input a BBC Game</a></li>
<li><a href="exp2/test1.php">Input without logfile</a></li>
<li><a href="exp4/shoutbox1.php?admin=1">Talk to people</a></li>
<li><a href="exp6/dates1.php">Create dates</></li>
<li><a href="exp6/dates3.php?d=0">View dates</></li>
<li><a href="exp6/dates3.php?d=1">Delete dates</></li>
<li><a href="exp3/bbcbotmanual.php">View bbcbot commands</a></li>
<li><a href="exp4/shoutbox3.php">View deleted Messages</li>
E;
  if($upc==3)
  $li=<<<E
<li><a href="exp6/upload1.php">Upload and Input a BBC Game</a></li>
<li><a href="exp2/test1.php">Input without logfile</a></li>
<li><a href="exp3/bbcbotmanual.php">View bbcbot commands</a></li>
E;
  print <<<E
	<h1>Login</h1>
	<h2>Welcome $user3 !</h2>
	<p>You can go to one of the following pages:</p>
	<ul>
	$li
	<li><a href="/husc/reg11.php?s=2">Register for HUSC</a></li>
	<li><b><a href="login.php?logoutattempt=1">Logout</a></b></li>
	</ul>
	</p>

E;
  $request="SELECT name FROM admins WHERE class>=1 AND class<=3 ORDER BY class ASC";
  $result=mysql_query($request);
  print "<p>People who can Login here as admin:</p>\n<ul>\n";
  while($row=mysql_fetch_object($result))
  {
    print "<li>".$row->name."</li>\n";
  }
  print "</ul></p>";
  
}



?>
<?php
include "footer1.php";
?>
</body>
</html>
