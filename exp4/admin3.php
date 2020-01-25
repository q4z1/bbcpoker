no<?php
$adminmode=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 || $_SESSION['upc'] == 2)
    $adminmode = 1;
} //$_COOKIE['PHPSESSID'] != ""
?>
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
if($adminmode==0)
  print "<p><a href=\"/login.php\">login</a></p>";
if($adminmode==1)
{
    print <<<E
    <h2>BBC admins</h2>
E;
    $request="SELECT name,class,id FROM admins 
    WHERE 
    class=1 OR class=2 OR class=3
    ORDER BY class ASC";
    
    $result=mysql_query($request);
    print "<h4>Table of accounts</h4><table border=1><tr><th>name</th><th>admin</th><th>access to admin chat</th><th></th></tr>\n";
    while($row=mysql_fetch_object($result))
    {
      $id=$row->id;
      $regtime=$row->regtime;
      print "<tr><td>".$row->name."</td>"
      if($class==1)
      {
        print "<td>superadmin</td>";
        print "<td>yes</td>";
      }
      if($class==2)
      {
        print "<td>admin</td>";
        print "<td>yes</td>";
      }
      if($class==3)
      {
        print "<td>admin</td>";
        print "<td>no</td>";
      }
      print "</tr>\n";
    }
    print "</table>";
}

?>

<?php
include "footer1.php";
?>

</body>
</html>
