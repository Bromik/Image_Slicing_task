<?php
define('ROOT', dirname(__FILE__));
$cutFile = ROOT . "/thumb_l_28550.png";
$outFile1 = ROOT . "/photo-cropped1.png";
$outFile2 = ROOT . "/photo-cropped2.png";
$outFile3 = ROOT . "/photo-cropped3.png";
$outFile4 = ROOT . "/photo-cropped4.png";
$outFile5 = ROOT . "/photo-cropped5.png";


/**
 * @param $cutFile
 * @param $outFile
 * @param $w
 * @param $h
 * @param $x
 * @param $y
 * @return Imagick
 */
function createImage($cutFile, $outFile, $w, $h, $x, $y)
{
    $image = new Imagick($cutFile);
    $size = $image->getImageGeometry();
    $wt = filesize($cutFile);
    if ($size['width'] <= 900 && $size['height'] <= 700 && $wt <= 1024000) {
        //resize img
        //$image->adaptiveResizeImage(900,700);
        //croping img by coordinates
        $image->cropImage($w, $h, $x, $y);
        //save img
        $image->writeImage($outFile);
        $im = new Imagick($outFile);
        //set format
        $im->setImageFormat("png");
        //prev img
        $im->thumbnailImage(145, null);
        $shadow = $im->clone();
        //make shadow
        $shadow->setImageBackgroundColor(new ImagickPixel('black'));
        $shadow->shadowImage(50, 7, 3, 3);
        //compose and save img with shadow
        $shadow->compositeImage($im, Imagick::COMPOSITE_OVER, 0, 0);
        $shadow->writeImage($outFile);
        return $shadow;
    } else {
        echo "Размер картниики не должен привышать в ширину 900 px,700 px в высоту и весить больше 1Mb <br> ";
        echo "Размер картинки: ширина {$size['width']} px, высота {$size['height']} px";
        exit();
    }

}


createImage($cutFile, $outFile1, 150, 170, 20, 190);
createImage($cutFile, $outFile2, 150, 300, 178, 127);
createImage($cutFile, $outFile3, 150, 530, 336, 23);
createImage($cutFile, $outFile4, 150, 300, 495, 127);
createImage($cutFile, $outFile5, 150, 170, 653, 190);

$src1 = new Imagick(ROOT . '/white.png');
$src2 = new Imagick($outFile1);
$src3 = new Imagick($outFile2);
$src4 = new Imagick($outFile3);
$src5 = new Imagick($outFile4);
$src6 = new Imagick($outFile5);
//compose all img in one
$src1->compositeImage($src2, Imagick::COMPOSITE_OVER, 20, 190);
$src1->compositeImage($src3, Imagick::COMPOSITE_OVER, 178, 127);
$src1->compositeImage($src4, Imagick::COMPOSITE_OVER, 336, 23);
$src1->compositeImage($src5, Imagick::COMPOSITE_OVER, 495, 127);
$src1->compositeImage($src6, Imagick::COMPOSITE_OVER, 653, 190);
$src1->writeImage(ROOT . '/result.png');

$dest = imagecreatefrompng(ROOT . '/result.png');
header('Content-Type: image/jpg');
imagejpeg($dest);