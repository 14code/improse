<?php
declare(strict_types=1);

namespace I4code\Improse;

class Job
{
    protected $id;
    protected $state;
    protected $image;
    protected $tasks = [];

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

    public function process()
    {
        $image = $this->getImage();
        foreach ($this->getTasks() as $task) {
           $task->run($image);
        }
    }

}