<?php
declare(strict_types=1);

namespace I4code\Improse\Tests;

use I4code\Improse\Image;

function getWorkDir(): string
{
    return __DIR__ . '/assets/tmp';
}

function getTestImage()
{
    return __DIR__ . '/assets/data/image.jpg';
}

function generateImageFile(): string
{
    $workDir = getWorkDir();
    $testImage = getTestImage();
    $filename = Image::generateNewFilename($workDir);
    copy($testImage, $filename);
    return $filename;
}

function generateImageObject(): Image
{
    $testImage = getTestImage();
    $image = new Image($testImage);
    return $image;
}

