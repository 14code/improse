<?php
declare(strict_types=1);

namespace I4code\Improse;

class Task
{
    protected $commandHandler;

    protected $command;
    protected $arguments;

    /**
     * Task constructor.
     * @param CommandHandler $commandHandler
     */
    public function __construct(CommandHandler $commandHandler)
    {
        $this->commandHandler = $commandHandler;
    }

    /**
     * @return array
     */
    public function getArguments(): ?array
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getCommand(): ?string
    {
        return $this->command;
    }

    /**
     * @param string mixed $command
     */
    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    /**
     * @return CommandHandler
     */
    public function getCommandHandler(): CommandHandler
    {
        return $this->commandHandler;
    }

    public function run(string $inputFilename): string
    {
        $command = $this->getCommand();
        $commandHandler = $this->getCommandHandler();
        if ($commandHandler->hasCommand($command)) {
            return $commandHandler->runCommand($inputFilename, $command, $this->getArguments());
        }
    }

}