<?php
session_start();

// angka random
$a = rand(1, 9);
$b = rand(1, 9);

// simpan hasil
$_SESSION['captcha'] = $a * $b;

// ukuran
$width = 160;
$height = 60;

// canvas
$img = imagecreatetruecolor($width, $height);

// gradient background
for ($i = 0; $i < $height; $i++) {
    $color = imagecolorallocate($img, 230 - $i, 230 - $i, 250);
    imageline($img, 0, $i, $width, $i, $color);
}

// warna teks
$textColor = imagecolorallocate($img, 75, 0, 130);

// noise titik
for ($i = 0; $i < 80; $i++) {
    $noise = imagecolorallocate($img, rand(150,200), rand(150,200), rand(200,255));
    imagesetpixel($img, rand(0,$width), rand(0,$height), $noise);
}

// noise garis
for ($i = 0; $i < 4; $i++) {
    $line = imagecolorallocate($img, rand(120,180), rand(120,180), rand(200,255));
    imageline($img, rand(0,$width), rand(0,$height), rand(0,$width), rand(0,$height), $line);
}

// teks (FIX di sini)
$text = "$a x $b";

// posisi tengah
$x = ($width / 2) - 25;
$y = ($height / 2) - 8;

// tampilkan teks
imagestring($img, 5, $x, $y, $text, $textColor);

// border
$border = imagecolorallocate($img, 200, 200, 230);
imagerectangle($img, 0, 0, $width-1, $height-1, $border);

// header
header("Content-Type: image/png");

// output
imagepng($img);
imagedestroy($img);
exit;