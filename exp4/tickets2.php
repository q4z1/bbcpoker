<?php
print '<!DOCTYPE html>
<html>';
require_once($_SERVER['DOCUMENT_ROOT'].'/defines.php');
ini_set('include_path', ROOT_DIR);
include "head.php";
$regulartaskcount = 1;
include "exp2/regulartasks.php";
print "<body>";
include "header1.php";
include "exp5/nav1.php";
?>
<h1> Ticket List </h1>
<?php
$ssortt = "<p>The following table is sorted by <b>Tickets to Step ";
/*
1 - sort by ts2, ts3, ts4, ts2+ts3+ts4>0
2 - sort by ts3, ts2, ts4, ts2+ts3+ts4>0
3 - sort by ts3, ts2, ts4, ts3>0
4 - sort by alphabet , ts2, ts3, ts4, ts2+ts3+ts4>0
5 - sort by ts4, ts2, ts3, ts4>0
6 - sort by ts4, ts2, ts3, ts2+ts3+ts4>0

restructure?
invent some for step4 ?
*/
$sort1  = (array_key_exists("sort", $_GET) && isset($_GET['sort']) && is_numeric($_GET['sort']) && $_GET['sort'] > 0) ? $_GET['sort'] : 0;
$sort2  = 1;
if ((int) $sort1 < 7 and (int) $sort1 > 0)
  $sort2 = (int) $sort1;
$ssortt .= array("","2","3","3","","4","4")[$sort2] . "</b>";
if ($sort2 == 4)
  $ssortt = "<p> The following table is sorted alphabetically</p>";
print $ssortt;
if ($sort2 == 3)
  print "<br>Only Players with a Ticket to Step 3 are shown";
if ($sort2 == 5)
  print "<br>Only Players with a Ticket to Step 4 are shown";

// showall
$sa        = (array_key_exists("sa", $_GET) && isset($_GET['sa']) && is_numeric($_GET['sa']) && $_GET['sa'] == 1) ? true : false;
// @XXX: pagination code #1 - LIMIT in Queries - 1 Seite = 50 Zeilen
$page      = 1;
$site_rows = 50; // 50 Zeilen pro Seite
$page      = (array_key_exists("page", $_GET) && isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
$url       = "http" . (!empty($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . preg_replace("/[?]+.*/", "", $_SERVER['REQUEST_URI']);
$start_row = $page * $site_rows - 50; // Seite 1 = Seite 0 ;)
$limit     = ($sa) ? "" : "LIMIT {$start_row}, {$site_rows}";
?>
</p>
<?php
$sort3 = array("","saisonpoints","saisongames","saisonscore","alltimepoints","alltimegames","alltimescore")[$sort2];
$where = "playerid > 1024 AND ";
$order = "";
if ($sort2 == 1) {
  $where = "(ts2>0 OR ts3>0 OR ts4>0 ) ";
  $order = "
		ts2 DESC,
		ts3 DESC,
		alltimescore DESC,
		playerid DESC ";
} //$sort2 == 1
elseif ($sort2 == 2) {
  $where = "(ts2>0 OR ts3>0 OR ts4>0 ) ";
  $order = "
		ts3 DESC,
		ts2 DESC,
		alltimescore DESC,
		playerid DESC ";
} //$sort2 == 2
  elseif ($sort2 == 3) {
  $where = "ts3>0 ";
  $order = "
		ts3 DESC,
		ts2 DESC,
		alltimescore DESC,
		playerid DESC ";
} //$sort2 == 3
  elseif ($sort2 == 4) {
  $where = "(ts3>0 OR ts2>0 OR ts4>0) ";
  $order = "
		name ASC,
		ts2 DESC,
		ts3 DESC,
		alltimescore DESC,
		playerid DESC ";
} //$sort2 == 4
  elseif ($sort2 == 5) {
  $where = "ts4>0 ";
  $order = "
		ts4 DESC,
		ts3 DESC,
		ts2 DESC,
		alltimescore DESC,
		playerid DESC ";
} //$sort2 == 5
  elseif ($sort2 == 6) {
  $where = "(ts3>0 OR ts2>0 OR ts4>0) ";
  $order = "
		ts4 DESC,
		ts3 DESC,
		ts2 DESC,
		alltimescore DESC,
		playerid DESC ";
} //$sort2 == 6

$request          = "
	SELECT SQL_CALC_FOUND_ROWS name, ts2, ts3, ts4 FROM table2
	WHERE
	{$where}
	ORDER BY
	{$order}
	{$limit}";
$result           = mysql_query($request);
$max_page         = 1;
$pagination_links = "";
if (!is_null($result)) {
  $num_rows = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS num;"));
  $max_page = ceil($num_rows->num / $site_rows);
  $pagination_links .= "<tr><th colspan=\"5\" style=\"font-weight: normal;\">Page:&nbsp;&nbsp;";
  if ($page > 1) {
    $pagination_links .= "<a href=\"/{$url}?page=1&sort={$sort1}\">«</a>&nbsp;";
    $pagination_links .= "<a href=\"/{$url}?page=" . ($page - 1) . "&sort={$sort1}\">«</a>&nbsp;";
  } //$page > 1
  else {
    $pagination_links .= "«&nbsp;";
  }
  $pagination_links .= "&nbsp;{$page}/{$max_page}&nbsp;&nbsp;";
  if ($page < $max_page) {
    $pagination_links .= "<a href=\"/{$url}?page=" . ($page + 1) . "&sort={$sort1}\">»</a>&nbsp;";
    $pagination_links .= "<a href=\"/{$url}?page={$max_page}&sort={$sort1}\">»</a>";
  } //$page < $max_page
  else {
    $pagination_links .= "»";
  }
  $pagination_links .= "</th></tr>";
} //!is_null($result)
else {
  $pagination_links = "<tr><th colspan=\"5\"> The Database is Empty</th></tr>";
}
$sa2 = "";
if ($sa) {
  $pagination_links = "";
  $sa2              = "&sa=1";
} //$sa
// @XXX: end pagination code here
?>
<h2>Table of Players </h2>
<p><a href="/<?= $url ?>?sa=1">Show all</a> entries on one site
		or divided into <a href="/<?= $url ?>">pages</a>.</p>
<table border=1>
<?= $pagination_links ?>
<tr><th rowspan=2>Pos.</th>
<th rowspan=2><a href="/<?= $url ?>?sort=4<?= $sa2 ?>">Playername</a></th>
<th colspan=3>Tickets to</th>
<tr> 
<th><a href="/<?= $url ?>?sort=1<?= $sa2 ?>">Step 2</a></th>
<th><a href="/<?= $url ?>?sort=2<?= $sa2 ?>">Step 3</a></th>
<th><a href="/<?= $url ?>?sort=6<?= $sa2 ?>">Step 4</a></th>
</tr>
<?php

$i = 0;
while ($row = mysql_fetch_object($result)) {
  $i++;
  $pname = $row->name;
  $ts2   = $row->ts2;
  $ts3   = $row->ts3;
  $ts4   = $row->ts4;
  print "<tr><td>" . ($i + (($page - 1) * 50)) . "</td>";
  print "<td>$pname</td>";
  print "<td>$ts2</td>";
  print "<td>$ts3</td>";
  print "<td>$ts4</td></tr>\n";
} //$row = mysql_fetch_object($result)
//@XXX: old code
/*
if($i==0) print "<tr><th colspan=\"8\"> The Database is Empty</th></tr>";
*/
//@XXX: pagination code
echo $pagination_links;
?>
</table>
<?php
include "footer1.php";
?>
</body>
</html>