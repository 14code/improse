<?php
declare(strict_types=1);

namespace I4code\Improse\Tests;

use I4code\Improse\Image;
use I4code\Improse\Job;
use I4code\Improse\JobFactory;
use PHPUnit\Framework\TestCase;

function getWorkDir(): string
{
    return __DIR__ . '/assets/tmp';
}

function getTestImage()
{
    return __DIR__ . '/assets/data/image.jpg';
}

function generateImageFile(): string
{
    $workDir = getWorkDir();
    $testImage = getTestImage();
    $filename = Image::generateNewFilename($workDir);
    copy($testImage, $filename);
    return $filename;
}

function generateImageObject(): Image
{
    $testImage = getTestImage();
    $image = new Image($testImage);
    return $image;
}

function generateJobFactoryMock()
{
    $testCase = new class extends TestCase {
        private $jobIncrement = 0;

        public function createJobFactoryMock()
        {
            $ref = $this;
            $jobFactoryMock = $this->createMock(JobFactory::class);
            $jobFactoryMock->method('create')->willReturnCallback(
                function () use ($ref) {
                    $mock = $ref->createMock(Job::class);
                    $mock->method('getId')->willReturn(uniqid());
                    if (1 > $ref->jobIncrement) {
                        $mock->method('getState')->willReturn('processed');
                    } else {
                        $mock->method('getState')->willReturn('created');
                    }
                    $ref->jobIncrement++;
                    return $mock;
                }
            );
            return $jobFactoryMock;

        }
    };
    return $testCase->createJobFactoryMock();
}
