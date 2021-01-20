<?php
declare(strict_types=1);

namespace I4code\Improse\Tests;

use I4code\Improse\Job;
use I4code\Improse\JobFactory;
use I4code\Improse\JobRepository;
use PHPUnit\Framework\TestCase;

class JobRepositoryTest extends TestCase
{

    public function testAdd()
    {
        $jobFactoryMock = generateJobFactoryMock();
        $jobRepository = new JobRepository($jobFactoryMock);
        $count = count($jobRepository->findAll());

        $jobData = [];
        $jobRepository->add($jobData);

        $jobs = $jobRepository->findAll();
        $this->assertEquals($count + 1, count($jobs));

        foreach ($jobs as $job) {
            $this->assertInstanceOf(Job::class, $job);
        }
    }

    public function testGetNext()
    {
        // What is the next job:
        // - the last added?
        // - the first inserted?
        $jobFactoryMock = generateJobFactoryMock();
        $jobRepository = new JobRepository($jobFactoryMock);

        $jobData = [];
        $jobRepository->add($jobData);

        $jobData = [];
        $jobRepository->add($jobData);

        $jobData = [];
        $jobRepository->add($jobData);

        $job = $jobRepository->getNext();
        $this->assertInstanceOf(Job::class, $job);

        // Ensure that instances in repository are different
        $last = null;
        foreach ($jobRepository->findAll() as $job) {
            if ($last instanceof Job) {
                $this->assertNotSame($last, $job);
                $this->assertNotEquals($last->getId(), $job->getId());
            }
            $last = $job;
        }

    }
}
