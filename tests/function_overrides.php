<?php
declare(strict_types=1);

namespace I4code\Improse;

function translateUrlToLocalfile($url)
{
    $testDir = __DIR__ . '/assets/data';
    $testHost = 'https://improse.phpunit.test/';
    $pattern = '/^' . preg_quote($testHost) . '/';
    if (preg_match($pattern, $url)) {
        $file = $testDir . '/' . basename($url);
        if (file_exists($file) && is_file($file)) {
            return $file;
        }
    }
    return false;
}

function file_get_contents(string $filename,bool $use_include_path = true,
                           ?resource $context = null, ?int $offset = 0, ?int $maxlen = null)
{
    if ($localFile = translateUrlToLocalfile($filename)) {
        $filename = $localFile;
    }
    // $maxlen cannot be null
    if (null === $maxlen) {
        return \file_get_contents($filename, $use_include_path, $context, $offset);
    }
    return \file_get_contents($filename, $use_include_path, $context, $offset, $maxlen);
}

/**
 * @param string $url
 * @param int|null $format
 * @param resource|null $context
 * @return array|false|string[]
 */
function get_headers(string $url, int $format = null, resource $context = null)
{
    if (($filename = translateUrlToLocalfile($url))
            && function_exists('getimagesize')) {
        $size = getimagesize($filename);
        if (isset($size['mime'])) {
            return [
                'Content-Type' => $size['mime'],
                'Content-Length' => filesize($filename)
            ];
        }
    }
    return \get_headers($url, $format, $context);
}

/**
 * Add quoting slashes to internal preg_quote()
 * @param string $str
 * @param string|null $delimeter
 * @return string
 */
function preg_quote(string $str, ?string $delimeter = null): string
{
    $str = \preg_quote($str, $delimeter);
    return str_replace('/', '\/', $str);
}

