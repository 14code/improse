<?php
declare(strict_types=1);

namespace I4code\Improse;

use I4code\Improse\Image;

class JobFactory
{
    public function create(array $data)
    {
        if (!isset($data['image']) or !($data['image'] instanceof Image)) {
           throw new \RuntimeException('Image object not specified');
        }
        if (!isset($data['tasks']) or !is_array($data['tasks'])) {
            throw new \RuntimeException('Tasks not specified');
        }
        $id = uniqid('job_');
        $job = new Job($id, $data['image'], $data['tasks']);
        return $job;
    }
}