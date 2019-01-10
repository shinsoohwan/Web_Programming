<?php
session_start();

/*
$patten = "qwertyuiopasdfghjklzxcvbnm 1234567890";
$code = "";

for ($i = 1; $i <= 4; i++)
{
  $code .= $patten[rand(0, strlen($patten) - 1)];
}
*/

$code=rand(1000,9999);
$_SESSION["code"]=$code;

///*
$im = imagecreatetruecolor(50, 24);
$bg = imagecolorallocate($im, 22, 86, 165); //background color blue
$fg = imagecolorallocate($im, 255, 255, 255);//text color white
imagefill($im, 0, 0, $bg);
imagestring($im, 5, 5, 5,  $code, $fg);

header("Cache-Control: no-cache, must-revalidate");
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
//*/

/*
header('Content-type: image/png');
$img = imagecreatetruecolor(150, 80);
imagefill($img, 0,0,0x00000);
imagestring($img, 10,50,30,$_SESSION['code'], 0xffffff);
imageline($img, 0,40,150,40, 0xff0000);
imagepng($img);
*/

?>
