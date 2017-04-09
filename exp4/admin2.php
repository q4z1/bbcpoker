<?php
$adminmode=0;
if ($_COOKIE['PHPSESSID'] != "") {
  session_start();
  if ($_SESSION['upc'] == 1 )
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
  print "<p><a href=\"login.php\">login</a></p>";
if($adminmode==1)
{
  print <<<E
  <h2>BBC Account & Admin Control</h2>
  <p>Hello, this should only be visible to superadmins</p>
  <p><b>Meaning of the Numbers for the BBC account/admin class:</b>
  <ul>
  <li><b>1</b>:&nbsp;&nbsp;Superadmin, access everywhere</li>
  <li><b>2</b>:&nbsp;&nbsp;Normal Admin, shoutbox, input/upload</li>
  <li><b>3</b>:&nbsp;&nbsp;Normal Admin, NO shoutbox, input/upload</li>
  <li><b>7</b>:&nbsp;&nbsp;Normal user, no admin</li>
  <li><b>8</b>:&nbsp;&nbsp;BBC account registration not confirmed</li>
  <li><b>9</b>:&nbsp;&nbsp;deleted/deactivated account</li>
  </ul>
  Other numbers (0,4,5,6,10,...) should not be in use (but might in the future)</p>  
  
E;
  $edit=(int)$_GET['edit'];
  if($edit!=0)
  {
    if($_POST['submit']!="")
    {
      $newclass=(int)$_POST['adminclass'];
      if(!($newclass<1 or $newclass>9 or $newclass==5 or $newclass==6 or $newclass==4))
      {
        $request="Update admins
        SET
        class=$newclass
        WHERE id=$edit";
        $result=mysql_query($request);
        if($result!== false) print "<h3>It looks like you succeeded with your request</h3>";
      }
    }
    print <<<E
    <form action="exp4/admin2.php?edit=$edit" method="post">
E;
    
    $request="SELECT name,class FROM admins WHERE id=$edit";
    $c=0;
    $result=mysql_query($request);
    while($row=mysql_fetch_object($result))
    {
      $c++;
      if($c>=2) continue;
      $name=$row->name;
      $class=$row->class;
      print "\n<p><b>$name</b>\n<ul>\n";
      
      for($i1=1;$i1<10;$i1++)
	  {
		if($i1==4 or $i1==5 or $i1==6) continue;
		$checked="";
		if($i1==$class)$checked="checked";
		print "<li><input type=\"radio\" name=\"adminclass\" value=$i1 $checked>$i1</li>";
		
      }
      print "\n</ul>\n";
    }
    if($c==0) print "<h3>ERROR: account not found</h3>";
    print <<<E
    <input type="submit" name="submit" value="submit changes!">
    </form>
    <p><a href="exp4/admin2.php"> Leave the edit mode </a></b>
E;
  }
  if($edit==0)
  {
    $request="SELECT name,class,id,regtime FROM admins 
    WHERE 
    class=1 OR class=2 OR class=3 or class=7 or class=8 or class=9 
    ORDER BY class ASC";
    
    $result=mysql_query($request);
    print "<h4>Table of accounts</h4><table border=1><tr><th>name</th><th>class</th><th>Registration Time</th><th></th></tr>\n";
    while($row=mysql_fetch_object($result))
    {
      $id=$row->id;
      $regtime=$row->regtime;
      if(strtotime($regtime)<time()-60*86400) $regtime="long ago";
      print "<tr><td>".$row->name."</td><td>" . $row->class . "</td>";
      print "<td>$regtime</td>";
      print "<td><a href=\"/exp4/admin2.php?edit=$id\">edit</a></td></tr>\n";
    }
    print "</table>";
  }
  

}

?>

<?php
include "footer1.php";
?>

</body>
</html>