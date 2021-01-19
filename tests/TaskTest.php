<?php
declare(strict_types=1);

namespace I4code\Improse\Tests;

use I4code\Improse\Task;
use I4code\Improse\CommandHandler;
use I4code\Improse\Image;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    protected $task;

    public function setUp(): void
    {
        $commandHandlerMock = $this->createMock(CommandHandler::class);
        $this->task = new Task($commandHandlerMock);
    }

    public function testConstruct()
    {
        $this->assertInstanceOf(Task::class, $this->task);
    }

    public function testSetArguments()
    {
        $this->assertNull($this->task->getArguments());
        $arguments = ['width' => 600, 'height' => 400];
        $this->task->setArguments($arguments);
        $this->assertEquals($arguments, $this->task->getArguments());
    }

    public function testSetCommand()
    {
        $this->assertNull($this->task->getCommand());
        $command = 'resize';
        $this->task->setCommand($command);
        $this->assertEquals($command, $this->task->getCommand());
    }

    public function testGetCommandHandler()
    {
        $commandHandler = $this->task->getCommandHandler();
        $this->assertInstanceOf(CommandHandler::class, $commandHandler);
    }

    public function testRun()
    {
        $workDir = __DIR__ . '/assets/tmp';
        $testImage = __DIR__ . '/assets/data/image.jpg';
        $inputFilename = Image::generateNewFilename($workDir);
        copy($testImage, $inputFilename);

        $outputFilename = Image::generateNewFilename($workDir);
        copy($testImage, $outputFilename);
        $commandHandlerMock = $this->createMock(CommandHandler::class);
        $commandHandlerMock->method('hasCommand')->willReturn(true);
        $commandHandlerMock->method('runCommand')->willReturn(
            $outputFilename);

        $task = new Task($commandHandlerMock);
        $task->setCommand('resize');
        $task->setArguments(['width' => 600, 'height' => 400]);

        $outputFilename = $task->run($inputFilename);
        $this->assertNotEmpty($outputFilename);
        $this->assertFileExists($outputFilename);
    }
}
