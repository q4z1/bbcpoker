<?php
header('Content-Type: text/plain');
ini_set('include_path', '/home/www/bbc/');
$regulartaskcount=1;
include "exp2/regulartasks.php";


//$text = date("Y-m-d_H:i:s",$timestamp);
//$file2 = fopen("exp2/backup$text","w");//create new output file
$request = "SELECT * FROM table1";//ask for everything in db
$result = mysql_query($request);
$i = 0;
$text = "";
while($row = mysql_fetch_assoc($result))
{
  if($i == 0){
    $text .= "* table1 : Separator between fields = #*#: *\n\n";
    foreach($row as $key => $value){
      $text .= "#*#$key";
    }
    $text .= "#*#\n";
    //$text .= "-----\nValues:\n";
  }
  foreach($row as $key => $value){
    $text .= "#*#$value";
  }
  $text .= "#*#\n";
  $i++;
	//for($i=0;$i<17;$i++) $text = $text . "#*#$row[$i]";
	

	//fwrite($file2,$text);
}
$text .= "\n----------------------\n\n";
$request = "SELECT * FROM table2";//ask for everything in db
$result = mysql_query($request);
$i = 0;
while($row = mysql_fetch_assoc($result))
{
  if($i == 0){
    $text .= "* table2 : Separator between fields = #*#: *\n\n";
    foreach($row as $key => $value){
      $text .= "#*#$key";
    }
    $text .= "#*#\n";
    //$text .= "-----\nValues:\n";
  }
  foreach($row as $key => $value){
    $text .= "#*#$value";
  }
  $text .= "#*#\n";
  $i++;
	//for($i=0;$i<17;$i++) $text = $text . "#*#$row[$i]";
	

	//fwrite($file2,$text);
}
$text .= "\n----------------------\n\n";
print $text;
die();

?>