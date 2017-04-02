<?php
$pc=explode(",",$_GET['d']);
$type=(int)$_GET['t'];
$error=0;
if(count($pc)!=10) $error=1;
$sum=0;
for($i1=0;$i1<10;$i1++)
{	$pc[$i1]=(int)$pc[$i1];
	if($pc[$i1]<0) $error=1;
	$sum+=$pc[$i1];
}
$deg=array();
if($sum==0) $error=1;
for($i1=0;$i1<10 and $error==0;$i1++) $deg[$i1]=360.0*$pc[$i1]/$sum;
$sum=0;
for($i1=0;$i1<10;$i1++) $sum+=$deg[$i1];
if($sum>360.1 or $sum<359.9)$error=1;
$max=1;
for($i1=0;$i1<10;$i1++) if($max<$pc[$i1])$max=$pc[$i1];
if($max<12) $gh=10;
else $gh=113.0/$max;
header("Content-type:image/png");
if($type==0)$img=imagecreate(120,120);
if($type==1)$img=imagecreate(160,120);
$background = imagecolorallocate( $img,255, 255, 255);
imagecolortransparent($img,$background);
$c=array();
/*$c[0]=imagecolorallocate($img,0,255,0);
$c[1]=imagecolorallocate($img,0,255,128);
$c[2]=imagecolorallocate($img,0,255,255);
$c[3]=imagecolorallocate($img,0,128,255);
$c[4]=imagecolorallocate($img,0,0,255);
$c[5]=imagecolorallocate($img,128,0,255);
$c[6]=imagecolorallocate($img,255,0,255);
$c[7]=imagecolorallocate($img,255,0,128);
$c[8]=imagecolorallocate($img,255,0,0);
$c[9]=imagecolorallocate($img,255,128,0);*/
/*
$c[0]=imagecolorallocate($img,226,0,26);
$c[1]=imagecolorallocate($img,232,82,69);
$c[2]=imagecolorallocate($img,238,131,112);
$c[3]=imagecolorallocate($img,245,178,172);
$c[4]=imagecolorallocate($img,249,206,200);
$c[5]=imagecolorallocate($img,207,209,210);
$c[6]=imagecolorallocate($img,177,179,180);
$c[7]=imagecolorallocate($img,135,136,138);
$c[8]=imagecolorallocate($img,88,88,90);
$c[9]=imagecolorallocate($img,0,0,0);
*/
/*
$c[0]=imagecolorallocate($img,226,0,26);
$c[1]=imagecolorallocate($img,232,82,69);
$c[2]=imagecolorallocate($img,238,131,112);
$c[3]=imagecolorallocate($img,245,178,172);
$c[4]=imagecolorallocate($img,234,191,230);
$c[5]=imagecolorallocate($img,182,182,250);
$c[6]=imagecolorallocate($img,152,154,190);
$c[7]=imagecolorallocate($img,135,136,138);
$c[8]=imagecolorallocate($img,88,88,90);
$c[9]=imagecolorallocate($img,0,0,0);
*/
/*
$c[0]=imagecolorallocate($img,0,206,26);
$c[1]=imagecolorallocate($img,82,232,69);
$c[2]=imagecolorallocate($img,131,238,112);
$c[3]=imagecolorallocate($img,178,245,172);
$c[4]=imagecolorallocate($img,206,249,200);
$c[5]=imagecolorallocate($img,209,207,210);
$c[6]=imagecolorallocate($img,179,177,180);
$c[7]=imagecolorallocate($img,136,135,138);
$c[8]=imagecolorallocate($img,88,88,90);
$c[9]=imagecolorallocate($img,0,0,0);
*/
/*
$c[0]=imagecolorallocate($img,87,171,39);
$c[1]=imagecolorallocate($img,112,183,71);
$c[2]=imagecolorallocate($img,146,200,115);
$c[3]=imagecolorallocate($img,171,213,147);
$c[4]=imagecolorallocate($img,205,230,190);
$c[5]=imagecolorallocate($img,217,179,255);
$c[6]=imagecolorallocate($img,191,129,204);
$c[7]=imagecolorallocate($img,166,79,184);
$c[8]=imagecolorallocate($img,128,3,154);
$c[9]=imagecolorallocate($img,56,1,111);
*/

$c[0]=imagecolorallocate($img,212,2,29);
$c[1]=imagecolorallocate($img,222,40,80);
$c[2]=imagecolorallocate($img,235,92,150);
$c[3]=imagecolorallocate($img,245,132,203);
$c[4]=imagecolorallocate($img,255,171,255);
$c[5]=imagecolorallocate($img,211,143,224);
$c[6]=imagecolorallocate($img,162,109,167);
$c[7]=imagecolorallocate($img,105,73,148);
$c[8]=imagecolorallocate($img,74,46,134);
$c[9]=imagecolorallocate($img,66,42,112);

/*
$c[0]=imagecolorallocate($img,87,171,39);
$c[1]=imagecolorallocate($img,103,178,76);
$c[2]=imagecolorallocate($img,146,200,115);
$c[3]=imagecolorallocate($img,171,213,147);
$c[4]=imagecolorallocate($img,194,222,184);
$c[5]=imagecolorallocate($img,187,188,190);
$c[6]=imagecolorallocate($img,161,163,165);
$c[7]=imagecolorallocate($img,123,124,126);
$c[8]=imagecolorallocate($img,81,81,84);
$c[9]=imagecolorallocate($img,64,64,67);
*/
/*
$c[0]=imagecolorallocate($img,225,0,26);
$c[1]=imagecolorallocate($img,231,82,68);
$c[2]=imagecolorallocate($img,237,130,112);
$c[3]=imagecolorallocate($img,244,177,171);
$c[4]=imagecolorallocate($img,248,205,210);
$c[5]=imagecolorallocate($img,187,188,190);
$c[6]=imagecolorallocate($img,161,163,165);
$c[7]=imagecolorallocate($img,123,124,126);
$c[8]=imagecolorallocate($img,81,81,84);
$c[9]=imagecolorallocate($img,64,64,67);
*/
/*
$c[0]=imagecolorallocate($img,255,237,0);
$c[1]=imagecolorallocate($img,238,208,0);
$c[2]=imagecolorallocate($img,216,183,15);
$c[3]=imagecolorallocate($img,177,138,43);
$c[4]=imagecolorallocate($img,158,116,57);
$c[5]=imagecolorallocate($img,140,96,70);
$c[6]=imagecolorallocate($img,121,74,83);
$c[7]=imagecolorallocate($img,102,53,96);
$c[8]=imagecolorallocate($img,91,34,104);
$c[9]=imagecolorallocate($img,69,29,90);
*/


$deg2=270;
for($i1=0;$i1<10 and $error==0 and $type==0;$i1++) {
	if($deg[$i1]>0)imagefilledarc($img,60,60,110,110,$deg2,$deg2+$deg[$i1],$c[$i1],IMG_ARC_PIE);
	$deg2+=$deg[$i1];
}
for($i1=0;$i1<10 and $error==0 and $type==1;$i1++) {
	imagefilledrectangle($img,$i1*16,120-$gh*$pc[$i1]-3,$i1*16+15,117,$c[$i1]);
}
for($i1=0;$i1<11 and $error==0 and $type==1 and $gh==10;$i1++) imageline($img,0,$i1*10+16,159,$i1*10+16,$background);
imagepng($img);
imagecolordeallocate($img,$background);
for($i1=0;$i1<10;$i1++)imagecolordeallocate($img,$c[$i1]);
?>