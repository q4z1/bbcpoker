<?php

function noobcrypt($text,$k)
{
  $ret="";
  for($i1=0;$i1<strlen($text);$i1++)
  {
    $ret .= chr(ord($text[$i1])+$k);
  }

  return $ret;
}

header("Content-type: image/png");
$string = " ".$_GET['text'].noobcrypt($_GET['c'],-1). " ";
$font  = 2;
$width  = imagefontwidth($font) * strlen($string);
$height = imagefontheight($font);
$image = imagecreatetruecolor ($width,$height);
$white = imagecolorallocate ($image,255,255,255);
$black = imagecolorallocate ($image,0,0,0);
imagefill($image,0,0,$white);
imagestring ($image,$font,0,0,$string,$black);
imagepng ($image);
imagedestroy($image);
?>