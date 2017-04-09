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
<h1> Ranking of BBC - Detailed All-Time</h1>
<p>The following table is sorted by 
<?php
$sort1 = (array_key_exists("sort", $_GET) && isset($_GET['sort']) && is_numeric($_GET['sort']) && $_GET['sort'] > 0) ? $_GET['sort'] : 0;
$sort2 = 3;
if ((int) $sort1 < 3 and (int) $sort1 > 0)
  $sort2 = (int) $sort1;
$sort4 = array("","All-Time Points","All-Time Games","All-Time Score")[$sort2];
print "<b>$sort4</b>";
print ". You can choose another ranking:<br>\n";
if ($sort2 != 1)
  print " <a href=\"exp2/ranking8.php?sort=1\">Sort by Points</a> ";
if ($sort2 != 3)
  print " <a href=\"exp2/ranking8.php?sort=3\">Sort by Score</a> ";
$sort3 = array("","alltimepoints","alltimegames","alltimescore")[$sort2];
// showall
$sa               = (array_key_exists("sa", $_GET) && isset($_GET['sa']) && is_numeric($_GET['sa']) && $_GET['sa'] == 1) ? true : false;
// @XXX: pagination code #1 - LIMIT in Queries - 1 Seite = 50 Zeilen
$page             = 1;
$site_rows        = 50; // 50 Zeilen pro Seite
$page             = (array_key_exists("page", $_GET) && isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
$url              = "http" . (!empty($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . preg_replace("/[?]+.*/", "", $_SERVER['REQUEST_URI']);
$start_row        = $page * $site_rows - 50; // Seite 1 = Seite 0 ;)
$limit            = ($sa) ? "" : "LIMIT {$start_row}, {$site_rows}";
$request          = "
	SELECT SQL_CALC_FOUND_ROWS playerid, name, alltimepoints, a1placecount,
				a2placecount,a3placecount, a4placecount, alltimescore, alltimegames
	FROM table2	WHERE playerid > 1024
	ORDER BY
	$sort3 DESC,
	alltimescore DESC,
	alltimegames ASC,
	saisonscore DESC,
	playerid DESC
	{$limit};
";
$result           = mysql_query($request);
$i                = 0;
$keinspiel        = "0,0,0,0,0,0,0,0,0,0";
$spanfeld         = array(
  "",
  "",
  " rowspan=2",
  " rowspan=3",
  " rowspan=4"
);
$result           = mysql_query($request);
$max_page         = 1;
$pagination_links = "";
if (!is_null($result)) {
  $num_rows = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS num;"));
  $max_page = ceil($num_rows->num / $site_rows);
  $pagination_links .= "<tr><th colspan=\"16\" style=\"font-weight: normal;\">Page:&nbsp;&nbsp;";
  if ($page > 1) {
    $pagination_links .= "<a href=\"{$url}?page=1&sort={$sort1}\">«</a>&nbsp;";
    $pagination_links .= "<a href=\"{$url}?page=" . ($page - 1) . "&sort={$sort1}\"></a>&nbsp;";
  } //$page > 1
  else {
    $pagination_links .= "«&nbsp;";
  }
  $pagination_links .= "&nbsp;{$page}/{$max_page}&nbsp;&nbsp;";
  if ($page < $max_page) {
    $pagination_links .= "<a href=\"{$url}?page=" . ($page + 1) . "&sort={$sort1}\"></a>&nbsp;";
    $pagination_links .= "<a href=\"{$url}?page={$max_page}&sort={$sort1}\">»</a>";
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
<p>Do you want to know how points and score are calculated? <a href="exp4/formula1.php">Then click here.</a></p>
<h2>Table of Players - Detailed All-Time Ranking </h2>
<p><a href="<?= $url ?>?sa=1&sort=<?= $sort1 ?>">Show all</a> entries on one site
		or divided into <a href="<?= $url ?>?sort=<?= $sort1 ?>">pages</a>.</p>
<table border=1>
<?= $pagination_links ?>	
<tr><th rowspan=2>Pos.</th>
<th rowspan=2>Playername</th>
<th rowspan=2>Step</th>
<th colspan="10"><b>Positions</b></th>
<th rowspan=2><a href="<?= $url ?>?sort=2<?= $sa2 ?>">Games</a></th>
<th rowspan=2><a href="<?= $url ?>?sort=1<?= $sa2 ?>">Points</a></th>
<th rowspan=2><a href="<?= $url ?>?sort=3<?= $sa2 ?>">Score</a></th>
</tr>
<tr>
<th> 1</th>
<th> 2</th>
<th> 3</th>
<th> 4</th>
<th> 5</th>
<th> 6</th>
<th> 7</th>
<th> 8</th>
<th> 9</th>
<th>10</th>
</tr>
<?php
while ($row = mysql_fetch_object($result)) {
  $i++;
  $pname   = $row->name;
  $pid     = $row->playerid;
  $apoints = $row->alltimepoints;
  $a1pc    = $row->a1placecount;
  $a2pc    = $row->a2placecount;
  $a3pc    = $row->a3placecount;
  $a4pc    = $row->a4placecount;
  $agames  = $row->alltimegames;
  $ascore  = $row->alltimescore;
  $span    = 4;
  if ($a4pc == $keinspiel)
    $span--;
  if ($a3pc == $keinspiel)
    $span--;
  if ($a2pc == $keinspiel)
    $span--;
  if ($a1pc == $keinspiel)
    $span--;
  if ($span == 0)
    continue;
  $s            = $spanfeld[$span];
  $as1          = floor($ascore / 1000);
  $as2          = $ascore - $as1 * 1000;
  $as3          = sprintf("%d.%03d", $as1, $as2);
  $endofthisrow = "<td$s>$agames</td><td$s>$apoints</td><td$s>$as3</td></tr>\n";
  print "<tr><td$s>" . ($i + (($page - 1) * 50)) . "</td><th$s><a href=\"exp5/players1.php?id=$pid\">$pname</a></th>";
  //print "<tr><td$s>$i</td>";
  //print "<th$s>$pname</th>";
  if ($a1pc != $keinspiel) {
    print "<td>1</td>";
    $pcarray = explode(",", $a1pc);
    for ($i2 = 0; $i2 < 10; $i2++)
      print "<td>$pcarray[$i2]</td>";
    print $endofthisrow;
  } //$a1pc != $keinspiel
  if ($a2pc != $keinspiel) {
    if ($a1pc != $keinspiel)
      print "<tr>";
    print "<td>2</td>";
    $pcarray = explode(",", $a2pc);
    for ($i2 = 0; $i2 < 10; $i2++)
      print "<td>$pcarray[$i2]</td>";
    if ($a1pc == $keinspiel)
      print $endofthisrow;
  } //$a2pc != $keinspiel
  if ($a3pc != $keinspiel) {
    if ($a1pc != $keinspiel or $a2pc != $keinspiel)
      print "<tr>";
    print "<td>3</td>";
    $pcarray = explode(",", $a3pc);
    for ($i2 = 0; $i2 < 10; $i2++)
      print "<td>$pcarray[$i2]</td>";
    if ($a1pc == $keinspiel and $a2pc == $keinspiel)
      print $endofthisrow;
  }
  if ($a4pc != $keinspiel) {
    if ($a1pc != $keinspiel or $a2pc != $keinspiel or $a3pc != $keinspiel)
      print "<tr>";
    print "<td>4</td>";
    $pcarray = explode(",", $a4pc);
    for ($i2 = 0; $i2 < 10; $i2++)
      print "<td>$pcarray[$i2]</td>";
    if ($a1pc == $keinspiel and $a2pc == $keinspiel and $a3pc == $keinspiel)
      print $endofthisrow;
  }
  
  //$a3pc != $keinspiel
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

