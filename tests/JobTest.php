<?php
declare(strict_types=1);

namespace I4code\Improse\Tests;

use I4code\Improse\CommandHandler;
use I4code\Improse\Image;
use I4code\Improse\Job;
use PHPUnit\Framework\TestCase;

class JobTest extends TestCase
{

    public function testProcess()
    {
        $image = generateImageObject();
        $image->download(getWorkDir());

        $tasks = [
            [
                'command' => 'duplicate',
                'arguments' => []
            ]
        ];
        $job = new Job($image, $tasks);

        $commandHandlerMock = $this->createMock(CommandHandler::class);
        $commandHandlerMock->method('hasCommand')->willReturn(true);
        $commandHandlerMock->method('runCommand')->willReturn(generateImageFile());
        $job->addCommandHandler($commandHandlerMock);

        $outputFile = $job->process();
        $this->assertNotEmpty($outputFile);
        $this->assertFileExists($outputFile);
    }
}
