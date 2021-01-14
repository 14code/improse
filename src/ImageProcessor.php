<?php
declare(strict_types=1);

namespace I4code\Improse;

class ImageProcessor
{
    protected $jobRepository;

    /**
     * ImageProcessor constructor.
     */
    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    /**
     * @return JobRepository
     */
    public function getJobRepository(): JobRepository
    {
        return $this->jobRepository;
    }

    // repository only stuff?
    public function createJob(array $data): Job
    {
        $jobRepository = $this->getJobRepository();
        $job = $jobRepository->create($data);
        $jobRepository->add($job);
        return $job;
    }

    public function process()
    {
        $jobRepository = $this->getJobRepository();
        $job = $jobRepository->getNext();
        $job->process();
    }

    public function getJobById(string $id): Job
    {}

    public function getJobsByImageName(string $imageName): array
    {}
}