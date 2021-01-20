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

    public function add(array $data)
    {
        $job = $this->factory->create($data);
        array_push($this->repository, $job);
    }

    public function findAll(): array
    {
        return $this->repository;
    }

    public function getNext(): Job
    {
        return end($this->repository);
    }

}