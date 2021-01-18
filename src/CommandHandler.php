<?php
declare(strict_types=1);

namespace I4code\Improse;

class CommandHandler
{
    public function hasCommand(string $command): bool
    {
        if (method_exists($this, $command)) {
            return true;
        }
        return false;
    }

    public function runCommand(string $command, array $arguments): ?string
    {
        if ($this->hasCommand($command)) {
            return $this->$command($arguments);
        }
        return null;
    }
}