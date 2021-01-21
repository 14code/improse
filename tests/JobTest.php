<?php
declare(strict_types=1);

namespace I4code\Improse\Tests;

use I4code\Improse\CommandHandler;
use I4code\Improse\Image;
use I4code\Improse\Job;
use PHPUnit\Framework\TestCase;

class JobTest extends TestCase
{

    public function testConstructor()
    {
        $id = uniqid('job_');
        $image = generateImageObject();
        $tasks = [];
        $job = new Job($id, $image, $tasks);
        $this->assertInstanceOf(Job::class, $job);
        $this->assertEquals($id, $job->getId());
        $this->assertEquals('created', $job->getState());
        $this->assertNotEmpty($job->getCreated());
    }

    public function testProcess()
    {
        $id = uniqid('job_');
        $image = generateImageObject();
        $image->download(getWorkDir());
        $tasks = [];
        $job = new Job($id, $image, $tasks);

        $commandHandlerMock = $this->createMock(CommandHandler::class);
        $commandHandlerMock->method('hasCommand')->willReturn(true);
        $commandHandlerMock->method('runCommand')->willReturn(generateImageFile());
        $job->addCommandHandler($commandHandlerMock);

        $outputFile = $job->process();
        $this->assertNotEmpty($outputFile);
        $this->assertFileExists($outputFile);
        $this->assertEquals('processed', $job->getState());
        $this->assertNotEmpty($job->getLastchanged());
    }
}
