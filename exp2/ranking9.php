<?php
print '<!DOCTYPE html><html>';
ini_set('include_path', '/home/www/bbc/');
include "head.php";
$regulartaskcount = 1;
include "exp2/regulartasks.php";
print "<body>";
include "header1.php";
include "exp5/nav1.php";
?>
<h1> Ranking of BBC - Detailed Current Season</h1>
<p>The following table is sorted by 
<?php
$sort1 = (array_key_exists("sort", $_GET) && isset($_GET['sort']) && is_numeric($_GET['sort']) && $_GET['sort'] > 0) ? $_GET['sort'] : 0;
$sort2 = 3;
if ((int) $sort1 < 3 and (int) $sort1 > 0)
  $sort2 = (int) $sort1;
$sort4 = array("","Current Season Points","Current Season Games","Current Season Score")[$sort2];
print "<b>$sort4</b>";
print ". You can choose another ranking:<br>\n";
if ($sort2 != 1)
  print " <a href=\"exp2/ranking8.php?sort=1\">Sort by Points</a> ";
if ($sort2 != 3)
  print " <a href=\"exp2/ranking8.php?sort=3\">Sort by Score</a> ";
$sort3 = array("","saisonpoints","saisongames","saisonscore")[$sort2];
// showall
$sa               = (array_key_exists("sa", $_GET) && isset($_GET['sa']) && is_numeric($_GET['sa']) && $_GET['sa'] == 1) ? true : false;
// @XXX: pagination code #1 - LIMIT in Queries - 1 Seite = 50 Zeilen
$page             = 1;
$site_rows        = 50; // 50 Zeilen pro Seite
$page             = (array_key_exists("page", $_GET) && isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
$url              = "http" . (!empty($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . preg_replace("/[?]+.*/", "", $_SERVER['REQUEST_URI']);
$start_row        = $page * $site_rows - 50; // Seite 1 = Seite 0 ;)
$keinspiel        = "0,0,0,0,0,0,0,0,0,0";
$spanfeld         = array(
  "",
  "",
  " rowspan=2",
  " rowspan=3"
);
/*
 * AND
 s1placecount != 0 AND
 s2placecount != 0 AND
 s3placecount != 0
 */
// show all?
$limit            = ($sa) ? "" : "LIMIT {$start_row}, {$site_rows}";
$request          = "
	SELECT SQL_CALC_FOUND_ROWS playerid, name, saisonpoints, s1placecount,
	s2placecount,s3placecount, saisonscore, saisongames
	FROM table2
	WHERE
	playerid > 1024
	AND
	(
		s1placecount != '$keinspiel' OR
		s2placecount != '$keinspiel' OR
		s3placecount != '$keinspiel'
	)
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
  if (!$sa) {
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
<p>Do you want to know how points and score are calculated? <a href="exp4/formula1.php">Then click here.</a></p>
<h2>Table of Players - Detailed Current Season Ranking </h2>
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
<?php
for ($i = 1; $i < 11; $i++)
  print "<th>$i</th>";
?>
</tr>
<?php
$i = 0;
while ($row = mysql_fetch_object($result)) {
  $pname   = $row->name;
  $pid     = $row->playerid;
  $spoints = $row->saisonpoints;
  $s1pc    = $row->s1placecount;
  $s2pc    = $row->s2placecount;
  $s3pc    = $row->s3placecount;
  $sgames  = $row->saisongames;
  $sscore  = $row->saisonscore;
  $span    = 3;
  if ($s3pc == $keinspiel)
    $span--;
  if ($s2pc == $keinspiel)
    $span--;
  if ($s1pc == $keinspiel)
    $span--;
  if ($span == 0)
    continue;
  $i++;
  $s            = $spanfeld[$span];
  $ss1          = floor($sscore / 1000);
  $ss2          = $sscore - $ss1 * 1000;
  $ss3          = sprintf("%d.%03d", $ss1, $ss2);
  $endofthisrow = "<td$s>$sgames</td><td$s>$spoints</td><td$s>$ss3</td></tr>\n";
  print "<tr><td$s>" . ($i + (($page - 1) * 50)) . "</td><th$s><a href=\"exp5/players1.php?id=$pid\">$pname</a></th>";
  if ($s1pc != $keinspiel) {
    print "<td>1</td>";
    $pcarray = explode(",", $s1pc);
    for ($i2 = 0; $i2 < 10; $i2++)
      print "<td>$pcarray[$i2]</td>";
    print $endofthisrow;
  } //$s1pc != $keinspiel
  if ($s2pc != $keinspiel) {
    if ($s1pc != $keinspiel)
      print "<tr>";
    print "<td>2</td>";
    $pcarray = explode(",", $s2pc);
    for ($i2 = 0; $i2 < 10; $i2++)
      print "<td>$pcarray[$i2]</td>";
    if ($s1pc == $keinspiel)
      print $endofthisrow;
  } //$s2pc != $keinspiel
  if ($s3pc != $keinspiel) {
    if ($s1pc != $keinspiel or $s2pc != $keinspiel)
      print "<tr>";
    print "<td>3</td>";
    $pcarray = explode(",", $s3pc);
    for ($i2 = 0; $i2 < 10; $i2++)
      print "<td>$pcarray[$i2]</td>";
    if ($s1pc == $keinspiel and $s2pc == $keinspiel)
      print $endofthisrow;
  } //$s3pc != $keinspiel
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