<?php
declare(strict_types=1);

namespace I4code\Improse\Tests;

use I4code\Improse\Image;

function generateWorkImage()
{
    $workDir = __DIR__ . '/assets/tmp';
    $testImage = __DIR__ . '/assets/data/image.jpg';
    $inputFilename = Image::generateNewFilename($workDir);
    copy($testImage, $inputFilename);
    return $inputFilename;
}
