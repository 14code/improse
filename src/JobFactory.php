<?php
declare(strict_types=1);

namespace I4code\Improse;

class JobFactory
{
    public function create(array $data)
    {
        return new Job($data['image'], $data['tasks']);
    }
}