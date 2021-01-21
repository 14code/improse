<?php
namespace I4code\Improse;

use DateTime;

function getBaseDir(): string
{
    return realpath(__DIR__ . '/..');
}

function generateTimestamp()
{
    return (new DateTime())->format(DateTime::ISO8601);
}
