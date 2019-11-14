<?php
// for jpg 
function resize_imagejpg($file, $w, $h) {
   list($width, $height) = getimagesize($file);
   $src = imagecreatefromjpeg($file);
   $dst = imagecreatetruecolor($w, $h);
   imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
   return $dst;
}

 // for png
function resize_imagepng($file, $w, $h) {
   list($width, $height) = getimagesize($file);
   $src = imagecreatefrompng($file);
   $dst = imagecreatetruecolor($w, $h);
   imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
   return $dst;
}

// for gif
function resize_imagegif($file, $w, $h) {
   list($width, $height) = getimagesize($file);
   $src = imagecreatefromgif($file);
   $dst = imagecreatetruecolor($w, $h);
   imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
   return $dst;
}
?>
