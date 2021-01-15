<?php
declare(strict_types=1);

namespace I4code\Improse\Tests;

use I4code\Improse\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    protected $tmpDir;

    public function setUp(): void
    {
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
        $image = new Image($url);
        $image->verifyDownloadUrl($url);

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
        $url = 'https://placebut.net/';
        $image = new Image($url);
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($url, $image->getUrl());

        // test with local file
        $url = $this->generateMockfile();
        $image = new Image($url);
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals($url, $image->getUrl());
    }

    public function testIsDownloaded()
    {
        $url = 'https://placebut.net/';
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

    /**
     * Image::isValid() should return true when file is valid image
     * and false in other cases
     */
    public function testIsValid()
    {
        $url = 'https://upload.wikimedia.org/wikipedia/commons/2/28/JPG_Test.jpg';
        // file above to large
        $url = 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/28/JPG_Test.jpg/815px-JPG_Test.jpg';
        $image = new Image($url);
        $image->download($this->tmpDir);
        $this->assertFalse($image->isValid());
    }

}
