<?php
declare(strict_types=1);

namespace I4code\Improse;

class Image
{
    protected static $sizeLimit;

    protected $url;
    protected $localFile;

    private $error;

    /**
     * Image constructor.
     */
    public function __construct(string $url)
    {
        static::$sizeLimit = 10000000; // 10M

        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            if (!file_exists($url) || !is_file($url)) {
                throw new \RuntimeException("URL $url is invalid or local file does not exist");
            }
        }
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getLocalFile(): ?string
    {
        return $this->localFile;
    }

    /**
     * @return string
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        $error = $this->getError();
        return !empty($error);
    }

    /**
     * @param string $error
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    public static function verifyDownloadUrl(string $url)
    {
        if (false === filter_var($url, FILTER_VALIDATE_URL)) {
            if (!file_exists($url) || !is_file($url)) {
                throw new \RuntimeException("File $url does not exist");
            }
            if (static::$sizeLimit < filesize($url)) {
                throw new \RuntimeException("File $url size exceeds limit of "
                    . static::$sizeLimit);
            }
            if (!static::validateJpg($url)) {
                throw new \RuntimeException("Local file $url is no valid JPG image");
            }
        } else {
            $headers = get_headers($url, 1);
            if (!isset($headers['Content-Type'])
                || ('image/jpeg' != $headers['Content-Type'])) {
                throw new \RuntimeException("Content-Type wrong or not set in URL $url");
            }
            if (!isset($headers['Content-Length'])
                || (static::$sizeLimit < $headers['Content-Length'])) {
                throw new \RuntimeException("Content-Length set in URL $url"
                    . " or exceeds limit of " . static::$sizeLimit);
            }
        }
    }

    public static function verifyDir(string $dir)
    {
        if (!file_exists($dir) || !is_dir($dir)) {
            throw new \RuntimeException("Folder $dir does not exist");
        }
    }

    public static function generateNewFilename(string $dir)
    {
        static::verifyDir($dir);

        $filename = "$dir/" . uniqid() . '.jpg';
        while (file_exists($filename)) {
            $filename = "$dir/" . uniqid() . '.jpg';
        }

        return $filename;
    }

    public function download(string $dir)
    {
        static::verifyDir($dir);
        static::verifyDownloadUrl($this->getUrl());

        $file = static::generateNewFilename($dir);

        $fileContents = file_get_contents($this->getUrl());
        if (false !== $fileContents) {
            if (file_put_contents($file, $fileContents)) {
                if (file_exists($file)) {
                    $this->localFile = $file;
                    return true;
                }
            }
        }

        $this->setError('File download failed');
        return false;
    }

    public function isDownloaded()
    {
        $file = $this->getLocalFile();

        if ($this->hasError() || empty($file) || !file_exists($file)) {
            return false;
        }

        return true;
    }

    public static function validateJpg(string $file)
    {
        $valid = true;

        if (file_exists($file) && is_file($file)) {

            // Check using getimagesize() - not working on imcomplete files!
            if (function_exists('getimagesize')) {
                try {
                    $size = getimagesize($file);
                    if (!is_array($size) || !isset($size['mime'])
                            || ('image/jpeg' != $size['mime'])) {
                        $valid = false;
                    }
                } catch (\Throwable $t) {}
            }

            // check for the existence of the EOI segment header at the end of the file
            $fileHandle = fopen($file, 'r');
            if (0 !== fseek($fileHandle, -2, SEEK_END)
                    || "\xFF\xD9" !== fread($fileHandle, 2)) {
                $valid = false;
            }
            fclose($fileHandle);

        }

        return $valid;
    }

    public function isValid()
    {
        $valid = $this->isDownloaded();
        if ($valid) {
            $valid = static::validateJpg($this->getLocalFile());
        }
        return $valid;
    }
}