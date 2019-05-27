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
<h1> Ranking of BBC - ROI</h1>
<p>
  <strong>The ROI ranking is based on the following calculation:</strong>
</p>
<br />
<div  style="background-color: #fafafa; width: 75%; text-align: center; margin: 0px auto;">
  <pre>
    ROI percentage =
    100 *
    (
      (number_of_1st_Step1 + number_of_1st_Step2 + number_of_1st_Step3 + number_of_1st_Step4 )* 45 +
      (number_of_2nd_Step1 + number_of_2nd_Step2 + number_of_2nd_Step3 + number_of_2nd_Step4 ) * 45 +
      (number_of_3rd_Step1 + number_of_3rd_Step2 + number_of_3rd_Step3 + number_of_3rd_Step4 ) * 10
      - number_of_games * 10
    ) / (number_of_games * 10);
  </pre>
</div>
<p>
  ROI calculation starts when >= 100 games have been played.
</p>
<?php
// showall
$sa               = (array_key_exists("sa", $_GET) && isset($_GET['sa']) && is_numeric($_GET['sa']) && $_GET['sa'] == 1) ? true : false;
// @XXX: pagination code #1 - LIMIT in Queries - 1 Seite = 50 Zeilen
$page             = 1;
$site_rows        = 50; // 50 Zeilen pro Seite
$page             = (array_key_exists("page", $_GET) && isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
$url              = "http" . (!empty($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . preg_replace("/[?]+.*/", "", $_SERVER['REQUEST_URI']);
$start_row        = $page * $site_rows - 50; // Seite 1 = Seite 0 ;)

$sortp = (array_key_exists("sort", $_GET) && isset($_GET['sort']) && is_numeric($_GET['sort']) && $_GET['sort'] > 0) ? $_GET['sort'] : 0;
$sorta = array("alltimeroi", "hundredroi");
$sort = $sorta[$sortp];

// show all?
$limit            = ($sa) ? "" : "LIMIT {$start_row}, {$site_rows}";
$request          = "
	SELECT SQL_CALC_FOUND_ROWS playerid, name, alltimegames, alltimeroi, hundredroi
	FROM table2
	WHERE
	playerid > 1024 AND
  alltimegames > 99
	ORDER BY
	$sort DESC 
	{$limit};
";
//die($request);
$result           = mysql_query($request);
$max_page         = 1;
$pagination_links = "";
if (!is_null($result)) {
  $num_rows = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS num;"));
  $max_page = ceil($num_rows->num / $site_rows);
  if (!$sa) {
    $pagination_links .= "<tr><th colspan=\"16\" style=\"font-weight: normal;\">Page:&nbsp;&nbsp;";
    if ($page > 1) {
      $pagination_links .= "<a href=\"{$url}?page=1&sort=$sortp\">«</a>&nbsp;";
      $pagination_links .= "<a href=\"{$url}?page=" . ($page - 1) . "&sort=$sortp\">‹</a>&nbsp;";
    } //$page > 1
    else {
      $pagination_links .= "«&nbsp;";
    }
    $pagination_links .= "&nbsp;{$page}/{$max_page}&nbsp;&nbsp;";
    if ($page < $max_page) {
      $pagination_links .= "<a href=\"{$url}?page=" . ($page + 1) . "&sort=$sortp\">›</a>&nbsp;";
      $pagination_links .= "<a href=\"{$url}?page={$max_page}&sort=$sortp\">»</a>";
    } //$page < $max_page
    else {
      $pagination_links .= "»";
    }
    $pagination_links .= "</th></tr>";
  } //!$sa
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
<!--
<p>Do you want to know how points and score are calculated? <a href="exp4/formula1.php">Then click here.</a></p>
<h2>Table of Players - Detailed Current Season Ranking </h2>
-->
<p><a href="<?= $url ?>?sa=1<?="&sort=$sortp"?>">Show all</a> entries on one site
		or divided into <a href="<?= $url ?><?="?sort=$sortp"?>">pages</a>.</p>
<table border=1>
<?= $pagination_links ?>	
<tr><th>Pos.</th>
<th>Playername</th>
<th>All-Time Games</th>
<th><a href="<?= $url ?>?sort=0<?= $sa2 ?>">ROI (All-Time)</a></th>
<th><a href="<?= $url ?>?sort=1<?= $sa2 ?>">ROI (last 100 games)</a></th>
</tr>
<?php
$i = $start_row;
while ($row = mysql_fetch_object($result)) {
	$pid = $row->playerid;
  $pname   = $row->name;
  $agames = $row->alltimegames;
  $roi100 = $row->hundredroi / 100;
  $roia = $row->alltimeroi / 100;
  $i++;
  $td = "<tr><td>$i</td><td><a href=\"exp5/players1.php?id=$pid\">$pname</a></td><td>$agames</td><td>".sprintf("%0.2f",$roia)." %</td><td>".sprintf("%0.2f",$roi100)." %</td></tr>";
  echo $td;
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