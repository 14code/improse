<?php
namespace I4code\Improse;

function getBaseDir(): string
{
    return realpath(__DIR__ . '/..');
}
