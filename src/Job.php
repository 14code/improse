<?php
declare(strict_types=1);

namespace I4code\Improse;


class Job
{
    protected $id;
    protected $state;
    protected $created;
    protected $lastchanged;

    protected $image;
    protected $tasks = [];

    protected $commandHandlers = [];

    /**
     * Job constructor.
     * @param $image
     * @param $tasks
     */
    public function __construct(string $id, Image $image, array $tasks)
    {
        $this->id = $id;
        $this->image = $image;
        $this->tasks = $tasks;

        $this->setCreated(generateTimestamp());
        $this->setState('created');
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * @param string $created
     */
    protected function setCreated(string $created): Job
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    protected function setState(string $state): Job
    {
        $this->state = $state;
        return $this->update();
    }

    /**
     * @return string
     */
    public function getLastchanged(): string
    {
        return $this->lastchanged;
    }

    /**
     * @param string $lastchanged
     * @return Job
     */
    protected function setLastchanged(string $lastchanged)
    {
        $this->lastchanged = $lastchanged;
        return $this;
    }

    public function update()
    {
        return $this->setLastchanged(generateTimestamp());
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
        $this->setState('processing');
        $image = $this->getImage();
        $commandHandler = $this->getCommandHandler();
        $outputFile = $image->getLocalFile();
        foreach ($this->getTasks() as $taskData) {
            $task = new Task($commandHandler);
            $task->setCommand($taskData['command']);
            $task->setArguments($taskData['arguments']);
            $outputFile = $task->run($image->getLocalFile());
        }
        $this->setState('processed');
        return $outputFile;
    }

}