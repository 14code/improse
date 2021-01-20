<?php
declare(strict_types=1);

namespace I4code\Improse\Tests;

use I4code\Improse\DuplicateCommandHandler;
use I4code\Improse\Image;
use PHPUnit\Framework\TestCase;

class DuplicateCommandHandlerTest extends TestCase
{

    public function testDuplicate()
    {
        $workDir = __DIR__ . '/assets/tmp';
        $testImage = __DIR__ . '/assets/data/image.jpg';
        $inputFilename = Image::generateNewFilename($workDir);
        copy($testImage, $inputFilename);

        $commandHandler = new DuplicateCommandHandler($workDir);
        $outputFile = $commandHandler->duplicate($inputFilename);
        $this->assertNotEmpty($outputFile);
        $this->assertFileExists($outputFile);
    }

}
