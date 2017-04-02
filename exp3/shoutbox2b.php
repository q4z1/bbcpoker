<?php
$amode = 0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 or $_SESSION['upc'] == 2)
    $amode = 2;
} //$_COOKIE['PHPSESSID'] != ""
if ($_GET['admin'] == 1 and $amode == 2)
  $amode = 1;
// keine Ausgabe vor dem Ende!
ini_set('include_path', '/home/www/bbc/');
//include "head.php";
$regulartaskcount = 2;
include "exp2/regulartasks.php";
// search function:
$search = false;
if (array_key_exists("search", $_GET) && isset($_GET['search']) && is_numeric($_GET['search'])) {
  $id      = $_GET['search'];
  $request = "SELECT * FROM shoutbox WHERE id <= {$id} ORDER BY id DESC LIMIT 0, 4"; // die letzten 4 Einträge
  $result  = mysql_query($request);
  $search  = true;
} //array_key_exists("search", $_GET) && isset($_GET['search']) && is_numeric($_GET['search'])
// Die Ausgabe erst am Ende - da ggf. als json encoded auszugeben
$out  = "";
// json ausgabe?
$json = (array_key_exists("type", $_GET) && isset($_GET['type']) && $_GET['type'] == "json") ? true : false;
if (!$search) {
  // showall
  $sa        = (array_key_exists("sa", $_GET) && isset($_GET['sa']) && is_numeric($_GET['sa']) && $_GET['sa'] == 1) ? true : false;
  // @XXX: pagination code #1 - LIMIT in Queries - 1 Seite = 50 Zeilen
  $page      = 1;
  $site_rows = 50; // 50 Zeilen pro Seite
  $page      = (array_key_exists("page", $_GET) && isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
  $url       = "http" . (!empty($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . preg_replace("/[?]+.*/", "", $_SERVER['REQUEST_URI']);
  $start_row = $page * $site_rows - 50; // Seite 1 = Seite 0 ;)
  $limit     = ($sa || $amode == 1) ? "" : "LIMIT {$start_row}, {$site_rows}";
  $request   = "SELECT SQL_CALC_FOUND_ROWS * FROM shoutbox ORDER BY id DESC {$limit}";
  if ($amode == 1 and $_GET['sa'] == 1)
    $request = "SELECT * FROM shoutbox ORDER BY id DESC";
  $result           = mysql_query($request);
  $max_page         = 1;
  $pagination_links = "";
  if (!is_null($result)) {
    $num_rows = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS num;"));
    $max_page = ceil($num_rows->num / $site_rows);
    $pagination_links .= "<div id=\"pagination\" style=\"font-weight: normal;\">Page:&nbsp;&nbsp;";
    if ($page > 1) {
      $pagination_links .= "<a href=\"{$url}?page=1\">«</a>&nbsp;";
      $pagination_links .= "<a href=\"{$url}?page=" . ($page - 1) . "\"></a>&nbsp;";
    } //$page > 1
    else {
      $pagination_links .= "«&nbsp;";
    }
    $pagination_links .= "&nbsp;{$page}/{$max_page}&nbsp;&nbsp;";
    if ($page < $max_page) {
      $pagination_links .= "<a href=\"{$url}?page=" . ($page + 1) . "\"></a>&nbsp;";
      $pagination_links .= "<a href=\"{$url}?page={$max_page}\">»</a>";
    } //$page < $max_page
    else {
      $pagination_links .= "»";
    }
    $pagination_links .= "</div>";
  } //!is_null($result)
  if ($sa || $json) {
    $pagination_links = "";
  } //$sa
  $out .= $pagination_links;
  // @XXX: end pagination code here 
} //!$search
while ($row = mysql_fetch_object($result)) {
  $user      = $row->name;
  $id        = $row->id;
  $msg       = $row->message;
  $timestamp = $row->datetime;
  $setting   = $row->setting;
  //print "<p> ($setting , $amode ) </p>";
  if ($amode != 1 and $setting == 1) {
    $out .= "<p><small>#$id | $timestamp | </small>[Hidden Admin Message]</p>";
    continue;
  } //$amode != 1 and $setting == 1
  $user = str_replace("&", "", $user);
  $user = str_replace("<", "", $user);
  $user = str_replace(">", "", $user);
  $user = str_replace("\n", "", $user);
  $msg  = str_replace("&", "&amp;", $msg);
  $msg  = str_replace("<", "&lt;", $msg);
  $msg  = str_replace(">", "&gt;", $msg);
  $msg  = str_replace("\n", "<br>", $msg);
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
      continue;
  } //$setting == 4
  $userprint = "<b>$user</b> wrote:";
  if ($setting == 3)
    $userprint = "<span style=\"color:#ee1155\"><b>$user</b></span> wrote:";
  if ($setting == 1)
    $userprint = "<span style=\"color:#ee1155\"><b>$user</b></span> wrote (only for admins):";
  $out .= "<p><small>#$id | $timestamp | </small> $userprint<br>";
  if ($setting == 1)
    $out .= "<span style=\"background-color:#99ffcc\">$msg</span></p>\n";
  else
    $out .= "$msg </p>\n";
} //$row = mysql_fetch_object($result)
if (!$search && !$json) {
  if ($GET_['sa'] != 1 and $amode == 1)
    $out .= '<p style="text-align:center"><a href="exp4/shoutbox2.php?admin=1&sa=1">Show all Messages</a></p>';
  if ($GET_['sa'] != 1 and $amode != 1)
    $out .= '<p style="text-align:center"><a href="exp4/shoutbox2.php?sa=1">Show all Messages</a></p>';
} //!$search && !$json
if ($json) {
  // ausgabe im json-format
  $response = array(
    "status" => "ok",
    "page" => $page,
    "max_page" => $max_page,
    "html" => utf8_encode($out)
  );
  //die($out);
  die(json_encode($response));
} //$json
else {
  // standard ausgabe
  die($out);
}
function hextodec($char) {
  $i1 = (int) $char;
  if ($i1 < 10 and $i1 > 0)
    return $i1;
  if ($char == "a")
    return 10;
  if ($char == "b")
    return 11;
  if ($char == "c")
    return 12;
  if ($char == "d")
    return 13;
  if ($char == "e")
    return 14;
  if ($char == "f")
    return 15;
  if ($char == "0")
    return 0;
}
?>