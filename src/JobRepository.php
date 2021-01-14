<?php
declare(strict_types=1);

namespace I4code\Improse;

class JobRepository
{
    protected $repository = [];

    protected $factory;

    /**
     * JobRepository constructor.
     * @param $factory
     */
    public function __construct($factory)
    {
        $this->factory = $factory;
    }

    public function create(array $data)
    {
        return $this->factory->create($data);
    }

    public function add(Job $job)
    {
        array_push($this->repository, $job);
    }

    public function getNext(): Job
    {
        return end($this->repository);
    }

}