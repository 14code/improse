<?php
declare(strict_types=1);

namespace I4code\Improse;

class Image
{
    protected $url;
    protected $localFile;
    protected $size;

    /**
     * Image constructor.
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function download()
    {}

    public function isDownloaded()
    {}

    public function isValid()
    {}
}