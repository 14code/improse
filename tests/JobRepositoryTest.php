<?php
declare(strict_types=1);

namespace I4code\Improse\Tests;

use I4code\Improse\Job;
use I4code\Improse\JobFactory;
use I4code\Improse\JobRepository;
use PHPUnit\Framework\TestCase;

class JobRepositoryTest extends TestCase
{
    public function generateRepository()
    {
        $jobFactoryMock = generateJobFactoryMock();
        $jobRepository = new JobRepository($jobFactoryMock);

        $jobData = [];
        $jobRepository->add($jobData);

        $jobData = [];
        $jobRepository->add($jobData);

        $jobData = [];
        $jobRepository->add($jobData);

        return $jobRepository;
    }

    public function testAdd()
    {
        $jobRepository = $this->generateRepository();
        $count = count($jobRepository->findAll());

        $jobData = [];
        $jobRepository->add($jobData);

        $jobs = $jobRepository->findAll();
        $this->assertEquals($count + 1, count($jobs));

        foreach ($jobs as $job) {
            $this->assertInstanceOf(Job::class, $job);
        }
    }

    public function testFindAll()
    {
        $jobRepository = $this->generateRepository();

        $allJobs = $jobRepository->findAll();
        $this->assertIsArray($allJobs);
        $this->assertEquals(3, count($allJobs));

        // Ensure that instances in repository are different
        $last = null;
        foreach ($allJobs as $job) {
            if ($last instanceof Job) {
                $this->assertNotSame($last, $job);
                $this->assertNotEquals($last->getId(), $job->getId());
            }
            $last = $job;
        }

    }

    /**
     * Should return first job with state 'created'
     */
    public function testGetNext()
    {
        $jobRepository = $this->generateRepository();

        $job = $jobRepository->getNext();
        $this->assertInstanceOf(Job::class, $job);

        // Verify that job is the first in repository
        $allJobs = $jobRepository->findAll();
        $this->assertEquals('created', $job->getState());
        $this->assertSame($allJobs[1], $job);
    }
}
