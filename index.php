<?php
define('ROOT', dirname(__FILE__));
//$cutFile = ROOT . "/thumb_l_28550.png";
//$cutFile = ROOT . "/IMG_0503.jpg";
$cutFile = ROOT . "/UTC-IM---baner.jpg";
$outFile1 = ROOT . "/photo-cropped1.png";
$outFile2 = ROOT . "/photo-cropped2.png";
$outFile3 = ROOT . "/photo-cropped3.png";
$outFile4 = ROOT . "/photo-cropped4.png";
$outFile5 = ROOT . "/photo-cropped5.png";
$mainImage = ROOT . '/white.png';



/**
 * @param $cutFile
 * @param $outFile
 * @param $w
 * @param $h
 * @param $x
 * @param $y
 * @return Imagick
 */



$createNewImage = new Imagick ($cutFile);
$size = $createNewImage->getImageGeometry();
$createNewImage->newImage ($size['width'], $size['height'], "white");
$createNewImage->setImageFormat ("png");
file_put_contents ("white.png", $createNewImage);

function createLittleImage($cutFile, $outFile, $columns, $hPercent, $xPercent, $yPercent,$mainImage)
{
    $image = new Imagick($cutFile);
    $size = $image->getImageGeometry();
    $onePercentW = ceil($size['width'] / 100);
    $onePercentH = ceil($size['height'] / 100);
    $w = ceil($size['width'] / $columns)."<br>";
    $h = ceil($onePercentH * $hPercent)."<br>";
    $x = ceil($onePercentW * $xPercent)."<br>";
    $y = ceil($onePercentH * $yPercent)."<br>";
    $image->cropImage($w, $h, $x, $y);
    //save img
    $image->writeImage($outFile);
    $im = new Imagick($outFile);
    //set format
    $im->setImageFormat("png");
    //prev img
    $coll=$w-$onePercentW;
    $im->thumbnailImage($coll, null);
    $shadow = $im->clone();
    //make shadow
    $shadow->setImageBackgroundColor(new ImagickPixel('black'));
    $shadow->shadowImage(50, 7, 3, 3);
    //compose and save img with shadow
    $shadow->compositeImage($im, Imagick::COMPOSITE_OVER, 0, 0);
    $shadow->writeImage($outFile);
    return $shadow;
}

createLittleImage($cutFile, $outFile1, 5, 30, 2, 35,$mainImage);
createLittleImage($cutFile, $outFile2, 5, 60, 22, 25,$mainImage);
createLittleImage($cutFile, $outFile3, 5, 90, 42, 5,$mainImage);
createLittleImage($cutFile, $outFile4, 5, 60, 62, 25,$mainImage);
createLittleImage($cutFile, $outFile5, 5, 30, 82, 35,$mainImage);


$src1 = new Imagick(ROOT . '/white.png');
$src2 = new Imagick($outFile1);
$src3 = new Imagick($outFile2);
$src4 = new Imagick($outFile3);
$src5 = new Imagick($outFile4);
$src6 = new Imagick($outFile5);
//compose all img in one
$size = $src1->getImageGeometry();
$onePercentW = ceil($size['width'] / 100);
$onePercentH = ceil($size['height'] / 100);


$src1->compositeImage($src2, Imagick::COMPOSITE_OVER, $onePercentW*2, $onePercentH*35);
$src1->compositeImage($src3, Imagick::COMPOSITE_OVER, $onePercentW*22, $onePercentH*25);
$src1->compositeImage($src4, Imagick::COMPOSITE_OVER, $onePercentW*42, $onePercentH*5);
$src1->compositeImage($src5, Imagick::COMPOSITE_OVER, $onePercentW*62, $onePercentH*25);
$src1->compositeImage($src6, Imagick::COMPOSITE_OVER, $onePercentW*82, $onePercentH*35);
$src1->writeImage(ROOT . '/result.png');
//
$dest = imagecreatefrompng(ROOT . '/result.png');
header('Content-Type: image/jpg');
imagejpeg($dest);


