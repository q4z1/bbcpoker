<?php
// set cookie
if ($_POST['send'] != "" and $_POST['user'] != "") {
  $user = $_POST['user'];
  setcookie("user1", "$user", time()+30 * 86400, '/');
} //$_POST['send'] != "" and $_POST['user'] != ""
$amode = 0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc'] == 2)
    $amode = 1;
} //$_COOKIE['PHPSESSID'] != ""
print '<!DOCTYPE html>
<html>';
ini_set('include_path', '/home/www/bbc/');
include "head.php";
$regulartaskcount = 1;
include "exp2/regulartasks.php";
include "exp4/sbfun.php";
print "<body style=\"position: relative;\">";
include "header1.php";
include "exp5/nav1.php";
// @XXX: debug div:
// echo '<div id="debug" style="display:none"></div>';
?>
<!-- @xxx: paging etc. - JavaScript Code (depends on jquery!) -->

<h1>Message Manager - for admins</h1>
<?php

if($amode !=1) {
  print "<p>Error: not logged in as admin</p>";
}

if ($amode == 1) {
  $action=(int)$_GET['action'];
  $msgid=(int)$_GET['msgid'];
  if(0==$msgid) $action=0;
  if(3==$action) {
    // restore step 1
    print "<p>Do you really want to restore message #$msgid and make it visible for the shoutbox again?</p>\n";
    print "<b><a href=\"/exp4/shoutbox4.php?action=4&msgid=$msgid\">YES</a></b>";
    print "&nbsp;<a href=\"/index.php\">NO</a>";
    // TODO: display more information about the message
  }
  if(1==$action) {
    // delete step 1
    print "<p>Do you really want to delete message #$msgid ? (deleted messages can be restored)</p> \n";
    print "<b><a href=\"/exp4/shoutbox4.php?action=2&msgid=$msgid\">YES</a></b>";
    print "&nbsp;<a href=\"/index.php\">NO</a>";
  }
  if(2==$action) {
    $request="UPDATE shoutbox Set setting=-1 WHERE id=$msgid";
    $result=mysql_query($request);
    $errorx=0;
    if($result===false) $errorx=1;
    if($errorx==1)
     print "<p>ERROR: your request failed</p>";
    if($errorx==0)
      print "<p>It looks like you succeeded with your request</p>";
  }
  if(4==$action) {
    $request="UPDATE shoutbox Set setting=2 WHERE id=$msgid";
    $result=mysql_query($request);
    $errorx=0;
    if($result===false) $errorx=1;
    if($errorx==1)
     print "<p>ERROR: your request failed</p>";
    if($errorx==0)
      print "<p>It looks like you succeeded with your request</p>";
  }
  // TODO: delete link in shoutbox2 for admins

  // TODO: echo stuff in sbfun

}
?>

<?php
if(1==$amode)
{
  print deletionguidelines();
}
?>
<?php
include "footer1.php";
?>
</body>
</html>
