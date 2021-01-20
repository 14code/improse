<?php
declare(strict_types=1);

namespace I4code\Improse;

use I4code\Improse\Image;

class DuplicateCommandHandler extends CommandHandler
{
    public function duplicate(string $inputFile, array $arguments = []): ?string
    {
        if (file_exists($inputFile)) {
            $outputFile = Image::generateNewFilename($this->getWorkdir());
            if (copy($inputFile, $outputFile)) {
                return $outputFile;
            }
        }
    }
}