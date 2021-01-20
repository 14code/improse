<?php
declare(strict_types=1);

namespace I4code\Improse\Tests;

use I4code\Improse\Image;
use I4code\Improse\Job;
use I4code\Improse\JobFactory;
use PHPUnit\Framework\TestCase;

class JobFactoryTest extends TestCase
{

    public function testCreate()
    {
        $jobData = [
            'image' => $this->createMock(Image::class),
            'tasks' => []
        ];
        $jobFactory = new JobFactory();
        $job = $jobFactory->create($jobData);
        $this->assertInstanceOf(Job::class, $job);
    }

}
