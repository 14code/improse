<?php
namespace I4code\Improse\Tests;

use PHPUnit\Framework\TestCase;
use function I4code\Improse\getBaseDir;

class FunctionsTest extends TestCase
{
    public function testGetBaseDir()
    {
        $this->assertEquals(realpath(__DIR__ . '/..'), getBaseDir());
    }
}
