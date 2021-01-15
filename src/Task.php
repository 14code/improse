<?php
declare(strict_types=1);

namespace I4code\Improse;

class Task
{
    protected $command;
    protected $arguments;

    public function generateOutpoutFilename(string $fileName): string
    {}

    public function run(string $inputFile): string
    {}
}