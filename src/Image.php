<?php
declare(strict_types=1);

namespace I4code\Improse;

class Image
{
    protected $url;
    protected $localFile;
    protected $sizeLimit;

    /**
     * Image constructor.
     */
    public function __construct(string $url)
    {
        $this->sizeLimit = 10000000; // 10M

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
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    public function verifyDownloadUrl(string $url)
    {
        if (false === filter_var($url, FILTER_VALIDATE_URL)) {
            if ($this->sizeLimit < filesize($url)) {
                throw new \RuntimeException("File $url size exceeds limit of "
                    . $this->sizeLimit);
            }
        } else {
            $headers = get_headers($this->getUrl(), 1);
            if (!isset($headers['Content-Type'])
                || ('image/jpeg' != $headers['Content-Type'])) {
                throw new \RuntimeException("Content-Type wrong or not set in URL $url");
            }
            if (!isset($headers['Content-Length'])
                || ($this->sizeLimit < $headers['Content-Length'])) {
                throw new \RuntimeException("Content-Length set in URL $url"
                    . " or exceeds limit of " . $this->sizeLimit
                    . " (vs. {$headers['Content-Length']})");
            }
        }
    }

    public function download($dir)
    {
        if (!file_exists($dir) || !is_dir($dir)) {
            throw new \RuntimeException("Folder $dir does not exist");
        }

        $file = "$dir/" . uniqid() . '.jpg';
        while (file_exists($file)) {
            $file = "$dir/" . uniqid() . '.jpg';
        }

        $this->verifyDownloadUrl($this->getUrl());

        $fileContents = file_get_contents($this->getUrl());
        file_put_contents($file, $fileContents);

        if (file_exists($file)) {
            $this->localFile = $file;
        }
    }

    public function isDownloaded()
    {
        $file = $this->getLocalFile();
        if (!empty($file) && file_exists($file)) {
            return true;
        }
        return false;
    }

    public function isValid()
    {
        return false;
    }
}