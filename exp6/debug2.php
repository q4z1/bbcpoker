<?php
//die("not active");
print '<!DOCTYPE html>
<html>';
ini_set('include_path', '/home/www/bbc/');
$regulartaskcount=1;
include "exp2/regulartasks.php";

print "<pre>\n";

include_once "exp5/func1.php";
include_once "exp6/func3.php";
include_once "exp6/func2.php";
include_once "exp2/hourly1.php";

$file=fopen("exp2/systemtodo.txt","a");
fwrite($file,"\nbbcbotmakepermission\n");
fclose($file);
//calcrating2("2014-01-01 00:00:00","2014-02-01 00:00:00",1,0,0,0,0);
/*calcrating2(0,"2014-02-01 00:00:00",1,0,0,0,0);
calcrating2(0,"2014-03-01 00:00:00",1,0,0,0,0);
calcrating2(0,"2014-07-01 00:00:00",1,1,0,0,0);
*/


//calcrating2(0,0,1,1,0,0,0);

print "done";



print "</pre>\n";

?>
