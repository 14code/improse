<?php
declare(strict_types=1);

namespace I4code\Improse;

class Job
{
    protected $id;
    protected $state;
    protected $image;
    protected $tasks = [];

    protected $commandHandlers = [];

    /**
     * Job constructor.
     * @param $image
     * @param $tasks
     */
    public function __construct(Image $image, array $tasks)
    {
        $this->id = uniqid('job_');
        $this->state = 'created';
        $this->image = $image;
        $this->tasks = $tasks;
    }

    /**
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @return array
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }
    public function addCommandHandler(CommandHandler $commandHandler)
    {
        $this->commandHandlers[] = $commandHandler;
    }

    /**
     * @return array
     */
    public function getCommandHandlers(): array
    {
        return $this->commandHandlers;
    }

    public function getCommandHandler(): CommandHandler
    {
        foreach ($this->getCommandHandlers() as $commandHandler) {
            return $commandHandler;
        }
        throw new \RuntimeException('No CommandHandlers defined');
    }

    public function process()
    {
        $image = $this->getImage();
        $commandHandler = $this->getCommandHandler();
        $outputFile = null;
        foreach ($this->getTasks() as $taskData) {
            $task = new Task($commandHandler);
            $task->setCommand($taskData['command']);
            $task->setArguments($taskData['arguments']);
            $outputFile = $task->run($image->getLocalFile());
        }
        return $outputFile;
    }

}