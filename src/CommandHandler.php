<?php
declare(strict_types=1);

namespace I4code\Improse;

abstract class CommandHandler
{
    private $workDir;

    /**
     * CommandHandler constructor.
     * @param string $workDir
     */
    public function __construct(string $workDir)
    {
        if (!file_exists($workDir) || !is_dir($workDir)) {
            throw new \RuntimeException("Folder $workDir does not exist");
        }
        $this->workDir = $workDir;
    }

    /**
     * @return string
     */
    public function getWorkDir(): string
    {
        return $this->workDir;
    }

    public function hasCommand(string $command): bool
    {
        if (method_exists($this, $command)) {
            return true;
        }
        return false;
    }

    public function runCommand(string $inputFile, string $command, array $arguments): ?string
    {
        if ($this->hasCommand($command)) {
            return $this->$command($inputFile, $arguments);
        }
        return null;
    }
}