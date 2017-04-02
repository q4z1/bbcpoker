<?php
print '<!DOCTYPE html>
<html>';
ini_set('include_path', '/home/www/bbc/');
include "head.php";
$regulartaskcount=1;
include "exp2/regulartasks.php";
print "<body>";
include "header1.php"; 

include "husc/huscnav1.php";
include "husc/huscfun1.php";

?>

<?php
print "<h1>DEBUG</h1>";
print "<pre>";
// start debug 4 -> 3
// print setround(2,10);
print checkendofround1(2);
//print twodigit(10);
print "\n";


print "done";
print "</pre>";
?>

<?php
include "footer1.php";
?>

</body>
</html>
