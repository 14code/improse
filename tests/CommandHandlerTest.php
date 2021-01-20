<?php
declare(strict_types=1);

namespace I4code\Improse\Tests;

use I4code\Improse\CommandHandler;
use PHPUnit\Framework\TestCase;

class CommandHandlerTest extends TestCase
{
    public function testConstructor()
    {
        $workDir = __DIR__ . '/assets/tmp';
        $commandHandler = $this->getMockForAbstractClass(CommandHandler::class, [$workDir]);
        $this->assertInstanceOf(CommandHandler::class, $commandHandler);
    }

    public function testHasCommand()
    {
        $workDir = __DIR__ . '/assets/tmp';
        $commandHandler = $this->getMockForAbstractClass(CommandHandler::class, [$workDir]);
        // Command copy does not exist
        $this->assertFalse($commandHandler->hasCommand('copy'));
    }

    public function testRunCommand()
    {
        $workDir = __DIR__ . '/assets/tmp';
        $inputFile = generateWorkImage();

        $commandHandler = $this->getMockForAbstractClass(CommandHandler::class, [$workDir]);
        $outputFile = $commandHandler->runCommand($inputFile, 'copy', []);
        // Command copy does not exist => null returned
        $this->assertNull($outputFile);

        $commandHandler = new class ($workDir) extends CommandHandler {
            public function copy(string $inputFile, array $arguments = []) {
                $outputFile = $this->getWorkDir() . '/copiedto.jpg';
                copy($inputFile, $outputFile);
                return $outputFile;
            }
        };
        $outputFile = $commandHandler->runCommand($inputFile, 'copy', []);
        $this->assertNotEmpty($outputFile);
        $this->assertFileExists($outputFile);
    }
}
