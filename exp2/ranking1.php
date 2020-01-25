<?php
print '<!DOCTYPE html><html>';
require_once($_SERVER['DOCUMENT_ROOT'].'/defines.php');
ini_set('include_path', ROOT_DIR);
include "head.php";
$regulartaskcount = 1;
include "exp2/regulartasks.php";
print "<body>";
include "header1.php";
include "exp5/nav1.php";
?>
<h1> Ranking of BBC </h1>
<p>The following table is sorted by 
<?php
$sort1 = (array_key_exists("sort", $_GET) && isset($_GET['sort']) && is_numeric($_GET['sort']) && $_GET['sort'] > 0) ? $_GET['sort'] : 0;
$sort2 = 3;
if ((int) $sort1 <= 6 and (int) $sort1 > 0)
  $sort2 = (int) $sort1;
$sort4 = array("","Season Points","Season Games","Season Score","All-Time Points","All-Time Games","All-Time Score")[$sort2];
print "<b>$sort4</b>";
print ". You can choose another ranking:<br>\n";
if ($sort2 != 1)
  print " <a href=\"/exp2/ranking1.php?sort=1\">Sort by Season Points</a> ";
if ($sort2 != 3)
  print " <a href=\"/exp2/ranking1.php?sort=3\">Sort by Season Score</a> ";
if ($sort2 != 4)
  print " <a href=\"/exp2/ranking1.php?sort=4\">Sort by All-Time Points</a> ";
if ($sort2 != 6)
  print " <a href=\"/exp2/ranking1.php?sort=6\">Sort by All-Time Score</a> ";
// @XXX: old code :
/*
$sort3 = array("","saisonpoints","saisongames","saisonscore","alltimepoints","alltimegames","alltimescore")[$sort2];
if($sort2>3) $request = "SELECT playerid, name, alltimescore, alltimepoints, alltimegames,saisonscore, saisonpoints,
saisongames FROM table2 WHERE playerid > 1024 ORDER BY $sort3 DESC,
alltimescore DESC, alltimegames ASC, saisonscore DESC,  playerid DESC";
else $request="SELECT playerid, name, alltimescore, alltimepoints, alltimegames,saisonscore, saisonpoints,
saisongames FROM table2 WHERE playerid > 1024 ORDER BY $sort3 DESC,
saisonscore DESC, saisongames ASC, alltimescore DESC, playerid DESC";
$result = mysql_query($request);
*/
// showall
$sa        = (array_key_exists("sa", $_GET) && isset($_GET['sa']) && is_numeric($_GET['sa']) && $_GET['sa'] == 1) ? true : false;
// @XXX: pagination code #1 - LIMIT in Queries - 1 Seite = 50 Zeilen
$page      = 1;
$site_rows = 50; // 50 Zeilen pro Seite
$page      = (array_key_exists("page", $_GET) && isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
$url       = "http" . (!empty($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . preg_replace("/[?]+.*/", "", $_SERVER['REQUEST_URI']);
$start_row = $page * $site_rows - 50; // Seite 1 = Seite 0 ;)
$sort3 = array("","saisonpoints","saisongames","saisonscore","alltimepoints","alltimegames","alltimescore")[$sort2];
$limit     = ($sa) ? "" : "LIMIT {$start_row}, {$site_rows}";
if ($sort2 > 3)
  $request = "
		SELECT SQL_CALC_FOUND_ROWS playerid, name, alltimescore, alltimepoints, alltimegames,saisonscore, saisonpoints,
		saisongames FROM table2 WHERE playerid > 1024
		ORDER BY
		$sort3 DESC,
		alltimescore DESC,
		alltimegames ASC,
		saisonscore DESC,
		playerid DESC
		{$limit};
	";
else
  $request = "
		SELECT SQL_CALC_FOUND_ROWS playerid, name, alltimescore, alltimepoints, alltimegames,saisonscore, saisonpoints,
		saisongames FROM table2 WHERE playerid > 1024
		ORDER BY
		$sort3 DESC,
		saisonscore DESC,
		saisongames ASC,
		alltimescore DESC,
		playerid DESC
		{$limit};
	";
$result           = mysql_query($request);
$max_page         = 1;
$pagination_links = "";
if (!is_null($result)) {
  $num_rows = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS num;"));
  $max_page = ceil($num_rows->num / $site_rows);
  $pagination_links .= "<tr><th colspan=\"8\" style=\"font-weight: normal;\">Page:&nbsp;&nbsp;";
  if ($page > 1) {
    $pagination_links .= "<a href=\"/{$url}?page=1&sort={$sort1}\">«</a>&nbsp;";
    $pagination_links .= "<a href=\"/{$url}?page=" . ($page - 1) . "&sort={$sort1}\">‹</a>&nbsp;";
  } //$page > 1
  else {
    $pagination_links .= "«&nbsp;";
  }
  $pagination_links .= "&nbsp;{$page}/{$max_page}&nbsp;&nbsp;";
  if ($page < $max_page) {
    $pagination_links .= "<a href=\"/{$url}?page=" . ($page + 1) . "&sort={$sort1}\">›</a>&nbsp;";
    $pagination_links .= "<a href=\"/{$url}?page={$max_page}&sort={$sort1}\">»</a>";
  } //$page < $max_page
  else {
    $pagination_links .= "»";
  }
  $pagination_links .= "</th></tr>";
} //!is_null($result)
else {
  $pagination_links = "<tr><th colspan=\"8\"> The Database is Empty</th></tr>";
}
$sa2 = "";
if ($sa) {
  $pagination_links = "";
  $sa2              = "&sa=1";
} //$sa
// @XXX: end pagination code here 
?>
</p>
<p>Do you want to know how points and score is calculated? <a href="/exp4/formula1.php">Then click here.</a></p>
<h2>Table of Players </h2>
<p><a href="/<?= $url ?>?sa=1&sort=<?= $sort1 ?>">Show all</a> entries on one site
		or divided into <a href="/<?= $url ?>?sort=<?= $sort1 ?>">pages</a>.</p>
<table border=1>
<?= $pagination_links ?>
<tr><th rowspan=2>Pos.</th>
<th rowspan=2>Playername</th>
<th colspan=3>Current Season</th>
<th colspan=3>All-Time</th></tr>
<tr> 
<th><a href="/<?= $url ?>?sort=1<?= $sa2 ?>">Points</a></th>
<th><a href="/<?= $url ?>?sort=2<?= $sa2 ?>">Games</a></th>
<th><a href="/<?= $url ?>?sort=3<?= $sa2 ?>">Score</a></th>
<th><a href="/<?= $url ?>?sort=4<?= $sa2 ?>">Points</a></th>
<th><a href="/<?= $url ?>?sort=5<?= $sa2 ?>">Games</a></th>
<th><a href="/<?= $url ?>?sort=6<?= $sa2 ?>">Score</a></th>
</tr>
<?php
$i = 0;
while ($row = mysql_fetch_object($result)) {
  $i++;
  $pid     = $row->playerid;
  $pname   = $row->name;
  $ascore  = $row->alltimescore;
  $agames  = $row->alltimegames;
  $apoints = $row->alltimepoints;
  $sscore  = $row->saisonscore;
  $sgames  = $row->saisongames;
  $spoints = $row->saisonpoints;
  $as3     = round($ascore / 100);
  $as1     = floor($as3 / 10);
  $as2     = $as3 - $as1 * 10;
  $ss3     = round($sscore / 100);
  $ss1     = floor($ss3 / 10);
  $ss2     = $ss3 - $ss1 * 10;
  print "<tr><td>" . ($i + (($page - 1) * 50)) . "</td>"; // @XXX: aktuelle Zeile muss mit pagination berücksichtigt werden
  print "<td><a href=\"/exp5/players1.php?id=$pid\">$pname</a></td>";
  print "<td>$spoints</td>";
  print "<td>$sgames</td>";
  printf("<td>%d.%d</td>", $ss1, $ss2);
  print "<td>$apoints</td>";
  print "<td>$agames</td>";
  printf("<td>%d.%d</td></tr>\n", $as1, $as2);
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