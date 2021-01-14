<?php
namespace I4code\Improse\Tests;

use PHPUnit\Framework\TestCase;
use function I4code\Improse\getBaseDir;

class FrontControllerTest extends TestCase
{
    public function testSyntax()
    {
        include getBaseDir() . '/public/index.php';
        $this->assertTrue(true);
    }
}
