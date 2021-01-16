<?php
declare(strict_types=1);

namespace I4code\Improse\Tests;

use I4code\Improse\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    protected $dataDir;
    protected $tmpDir;
    protected $testHost;

    public function setUp(): void
    {
        $this->testHost = 'improse.phpunit.test';
        $this->dataDir = __DIR__ . '/assets/data';
        $this->tmpDir = __DIR__ . '/assets/tmp';
    }

    public function tearDown(): void
    {
        // Remove temporary files
        $dirContents = array_diff(scandir($this->tmpDir),
            ['.', '..', '.gitkeep']);
        foreach ($dirContents as $entry) {
            $file = $this->tmpDir . '/' . $entry;
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function generateMockfile()
    {
        $url = 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/28/JPG_Test.jpg/815px-JPG_Test.jpg';
        Image::verifyDownloadUrl($url);

        $file = $this->tmpDir . '/' . uniqid() . '.jpg';
        file_put_contents($file, file_get_contents($url));
        return $file;
    }

    public function testConstructorNoUrl()
    {
        $this->expectException(\ArgumentCountError::class);
        $image = new Image();
    }

    public function testConstructorInvalidUrl()
    {
        $this->expectException(\RuntimeException::class);
        $url = 'blabla';
        $image = new Image($url);
    }

    public function testConstructor()
    {
        $url = "https://{$this->testHost}/";
        $image = new Image($url);
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($url, $image->getUrl());

        // test with local file
        $url = $this->generateMockfile();
        $image = new Image($url);
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($url, $image->getUrl());
    }

    public function testVerifyDownloadUrl()
    {
        // file exceeds limit of 10M
        $url = 'https://upload.wikimedia.org/wikipedia/commons/2/28/JPG_Test.jpg';
        $this->expectException(\RuntimeException::class);
        Image::verifyDownloadUrl($url);
    }

    public function testIsDownloaded()
    {
        $url = "https://{$this->testHost}/";
        $image = new Image($url);
        $this->assertEmpty($image->getLocalFile());
        $this->assertFalse($image->isDownloaded());
    }

    public function testInvalidDownload()
    {
        $this->expectError();
        $url = 'https://sdfsf.sdfgg.ssffd/sdfsf';
        $image = new Image($url);
        $image->download($this->tmpDir);
    }

    public function testHasError()
    {
        $url = $this->generateMockfile();
        $image = new Image($url);
        $this->assertFalse($image->hasError());
    }

    public function testDownload()
    {
        $url = $this->generateMockfile();
        $image = new Image($url);
        $this->assertEmpty($image->getLocalFile());
        $this->assertFalse($image->isDownloaded());
        $image->download($this->tmpDir);
        $file = $image->getLocalFile();
        $this->assertNotEmpty($file);
        $expected = $this->tmpDir . '/' . basename($file);
        $this->assertEquals($expected, $file);
        $this->assertFileExists($file);
        $this->assertTrue($image->isDownloaded());
        $this->assertNotEquals($url, $file);
        $this->assertFileEquals($url, $file);
    }

    public function testValidateJpg()
    {
        $file = $this->dataDir . '/test.txt';
        $this->assertFalse(Image::validateJpg($file));

        $file = $this->dataDir . '/corrupted.jpg';
        $this->assertFalse(Image::validateJpg($file));

        $file = $this->dataDir . '/image.jpg';
        $this->assertTrue(Image::validateJpg($file));
    }

    /**
     * Image::isValid() should return true when file is valid image
     * and false in other cases
     */
    public function testIsValid()
    {
        // image should return true
        $url = $this->dataDir . '/image.jpg';
        $image = new Image($url);
        $image->download($this->tmpDir);
        $this->assertTrue($image->isValid());

        // valid image should return true
        $url = 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/28/JPG_Test.jpg/815px-JPG_Test.jpg';
        $image = new Image($url);
        $image->download($this->tmpDir);
        $this->assertTrue($image->isValid());

        $url = "https://{$this->testHost}/image.jpg";
        $image = new Image($url);
        $image->download($this->tmpDir);
        $this->assertTrue($image->isValid());
    }

}
