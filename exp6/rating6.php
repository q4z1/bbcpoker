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
<?php
// showall
$sa        = (array_key_exists("sa", $_GET) && isset($_GET['sa']) && is_numeric($_GET['sa']) && $_GET['sa'] == 1) ? true : false;

$sa2 = "";
if ($sa) {
  $pagination_links = "";
  $sa2              = "&sa=1";
} //$sa



?>

<h1>Rating table (version 5)</h1>
<p><b>Options:</b><br>
<a href="exp6/rating6.php?g=100<?=$sa2 ?>" >(Show players with more than 99 games)</a><br>
<a href="exp6/rating6.php?g=30<?=$sa2 ?>">(Show players with more than 29 games)</a><br>
<a href="exp6/rating6.php?g=0<?=$sa2 ?>">(Show all players)</a><br>
<!--<a href="exp6/rating6.php?recalc=1">(recalculate rating)</a> (can be done after a game)-->
</p>
<h3>About the rating</h3>
<p>
We have two pages that help a little for understanding the rating:<br>
<a href="exp4/formula5.php">Rating Calculator</a> for a single game and for playing around<br>
<a href="exp6/rating5.php">Calculation for every bbc game</a> Warning: there is a lot of data.
</p>
<?php
if($_GET['recalc']=="1")
{
	include "func3.php";
	calcrating2("2014-10-01 00:00:00",0,0,1,0,0,0);
}

// @XXX: pagination code #1 - LIMIT in Queries - 1 Seite = 50 Zeilen
$page      = 1;
$site_rows = 50; // 50 Zeilen pro Seite
$page      = (array_key_exists("page", $_GET) && isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
$url       = "http" . (!empty($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . preg_replace("/[?]+.*/", "", $_SERVER['REQUEST_URI']);
$start_row = $page * $site_rows - 50; // Seite 1 = Seite 0 ;)
$limit     = ($sa) ? "" : "LIMIT {$start_row}, {$site_rows}";


$mingames=(int)$_GET['g'];
?>

<p><a href="<?= $url ?>?sa=1&g=<?= $mingames ?>">Show all</a> entries on one site
		or divided into <a href="<?= $url ?>?g=<?= $mingames ?>">pages</a>.</p>
<table border=1>
<tr><td>#</td><th>Player</th><th>Rating</th><th>Games</th></tr>
<?php


$request="SELECT SQL_CALC_FOUND_ROWS playerid, name, rating, alltimegames 
FROM table2 WHERE rating!=0 and alltimegames>=$mingames
ORDER BY rating DESC 
{$limit}";
$result=mysql_query($request);

$pagination_links = "";
if (!is_null($result)) {
  $num_rows = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS num;"));
  $max_page = ceil($num_rows->num / $site_rows);
  $pagination_links .= "<tr><th colspan=\"4\" style=\"font-weight: normal;\">Page:&nbsp;&nbsp;";
  if ($page > 1) {
    $pagination_links .= "<a href=\"{$url}?page=1&g={$mingames}\">&laquo;</a>&nbsp;";
    $pagination_links .= "<a href=\"{$url}?page=" . ($page - 1) . "&g={$mingames}\">&lsaquo;</a>&nbsp;";
  } //$page > 1
  else {
    $pagination_links .= "&laquo;&nbsp;";
  }
  $pagination_links .= "&nbsp;{$page}/{$max_page}&nbsp;&nbsp;";
  if ($page < $max_page) {
    $pagination_links .= "<a href=\"{$url}?page=" . ($page + 1) . "&g={$mingames}\">&rsaquo;</a>&nbsp;";
    $pagination_links .= "<a href=\"{$url}?page={$max_page}&g={$mingames}\">&raquo;</a>";
  } //$page < $max_page
  else {
    $pagination_links .= "&raquo;";
  }
  $pagination_links .= "</th></tr>";
} //!is_null($result)
else {
  $pagination_links = "<tr><th colspan=\"4\"> The Database is Empty</th></tr>";
}
$sa2 = "";
if ($sa) {
  $pagination_links = "";
  $sa2              = "&sa=1";
} //$sa


print $pagination_links;

$c=0;
while($row=mysql_fetch_object($result))
{
	$c++;
	$pid     = $row->playerid;
	$pname   = $row->name;
	$rating  = $row->rating;
	$agames  = $row->alltimegames;
	print "<tr><td>" . ($c + (($page - 1) * 50)) . "</td>";
	print "<td><a href=\"exp5/players1.php?id=$pid\">$pname</a></td>";
	print "<td>$rating</td>";
	print "<td>$agames</td>";
	print "</tr>\n";
}
print $pagination_links;
?>
</table>
<?php
include "footer1.php";
?>
</body>
</html>
