<?php
//mysql_connect("localhost","bbcpoker","baguette")or die ("Internal MYSQL-ERROR");
//mysql_select_db("bbcpoker") or die ("The Database does not exist");
print '<!DOCTYPE html><html>';
require_once($_SERVER['DOCUMENT_ROOT'].'/defines.php');
ini_set('include_path', ROOT_DIR);
include "head.php";
$regulartaskcount = 1;
include "exp2/regulartasks.php";
print "<body>";
include "header1.php";
include "exp5/nav1.php";
if ($_GET["step"] == 1 or $_GET["step"] == 2 or $_GET["step"] == 3 or $_GET["step"] == 4) {
  $step      = $_GET["step"];
  // @XXX: pagination code #1 - LIMIT in Queries - 1 Seite = 50 Zeilen
  $page      = 1;
  $site_rows = 50; // 50 Zeilen pro Seite
  $page      = (array_key_exists("page", $_GET) && isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
  $url       = "http" . (!empty($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER['SERVER_NAME'] . preg_replace("/[?]+.*/", "", $_SERVER['REQUEST_URI']);
  // showall
  $sa        = (array_key_exists("sa", $_GET) && isset($_GET['sa']) && is_numeric($_GET['sa']) && $_GET['sa'] == 1) ? true : false;
  $start_row = $page * $site_rows - 50; // Seite 1 = Seite 0 ;)
  print "<h1> List of Games in Step $step </h1><br>\n";
  $limit            = ($sa) ? "" : "LIMIT {$start_row}, {$site_rows}";
  $request          = "SELECT SQL_CALC_FOUND_ROWS * FROM table1 WHERE step='$step' ORDER BY gameno DESC {$limit};";
  $result           = mysql_query($request);
  $max_page         = 1;
  $pagination_links = "";
  if (!is_null($result)) {
    $num_rows = mysql_fetch_object(mysql_query("SELECT FOUND_ROWS() AS num;"));
    $max_page = ceil($num_rows->num / $site_rows);
    $pagination_links .= "<tr><th colspan=\"12\" style=\"font-weight: normal;\">Page:&nbsp;&nbsp;";
    if ($page > 1) {
      $pagination_links .= "<a href=\"{$url}?page=1&step={$step}\">«</a>&nbsp;";
      $pagination_links .= "<a href=\"{$url}?page=" . ($page - 1) . "&step={$step}\"></a>&nbsp;";
    } //$page > 1
    else {
      $pagination_links .= "«&nbsp;";
    }
    $pagination_links .= "&nbsp;{$page}/{$max_page}&nbsp;&nbsp;";
    if ($page < $max_page) {
      $pagination_links .= "<a href=\"{$url}?page=" . ($page + 1) . "&step={$step}\"></a>&nbsp;";
      $pagination_links .= "<a href=\"{$url}?page={$max_page}&step={$step}\">»</a>";
    } //$page < $max_page
    else {
      $pagination_links .= "»";
    }
    $pagination_links .= "</th></tr>";
  } //!is_null($result)
  else {
    $pagination_links = "<tr><th colspan=\"12\"> The Database is Empty</th></tr>";
  }
  $sa2 = "";
  if ($sa) {
    $pagination_links = "";
    $sa2              = "&sa=1";
  } //$sa
  // @XXX: end pagination code here 
  //$i=0;
  $trans = array();
?>
<p><a href="<?= $url ?>?sa=1&step=<?= $step ?>">Show all</a> entries on one site
		or divided into <a href="<?= $url ?>?step=<?= $step ?>">pages</a>.</p>
<?
  print "<table border=1>";
  print $pagination_links;
  print "<tr><th>#</th><th>S</th>";
  print "<th>Winner</th>";
  for ($i = 2; $i < 11; $i++)
    print "<th> Place $i</th>";
  print "</tr>";
  while ($row = mysql_fetch_object($result)) {
    //$i++;
    $pid    = array(
      $row->p1,
      $row->p2,
      $row->p3,
      $row->p4,
      $row->p5,
      $row->p6,
      $row->p7,
      $row->p8,
      $row->p9,
      $row->p10
    );
    $gameno = $row->gameno;
    $season = $row->season;
    print "<tr><td><a href=\"exp5/gameslist3.php?step=$step&amp;g=$gameno\">$gameno</a></td>";
    print "<td>$season</td>";
    for ($i = 0; $i < 10; $i++) {
      if ($trans[$pid[$i]] == "") {
        $request2 = "SELECT name FROM table2 WHERE playerid='$pid[$i]'";
        $result2  = mysql_query($request2);
        while ($row2 = mysql_fetch_object($result2))
          $trans[$pid[$i]] = $row2->name;
      } //$trans[$pid[$i]] == ""
      if ($trans[$pid[$i]] != "0") {
        $n = $trans[$pid[$i]];
        print "<td>$n</td>";
      } //$trans[$pid[$i]] != "0"
      else
        print "<td></td>";
    } //$i = 0; $i < 10; $i++
    print "</tr>";
  } //$row = mysql_fetch_object($result)
  print $pagination_links;
  print "</table><p><small><b>S</b> means Season</small></p>";
} //$_GET["step"] == 1 or $_GET["step"] == 2 or $_GET["step"] == 3 or $_GET["step"] == 4
?>
<?php
include "footer1.php";
?>
</body>
</html>
