<?php

declare(strict_types=1);

namespace App\Services;

abstract class AbstractSolver {
    
    private const TEST_FILENAME = 'testinput';
    private const REAL_FILENAME = 'input';
    
    private readonly string $directory;
    private string $filename;

    public function __construct()
    {
        $reflection = new \ReflectionClass($this);
        $this->directory = dirname($reflection->getFileName());
        $this->filename = self::REAL_FILENAME;
    }

    public function getInput(): string
    {
        return file_get_contents($this->directory."/".$this->filename);
    }

    public function getLines(): array
    {
        return explode(PHP_EOL, $this->getInput());
    }

    public function useTestinput(): bool
    {
        if(is_readable($this->directory.'/'.self::TEST_FILENAME)) {
            $this->filename = self::TEST_FILENAME;
            return true;
        }
        
        return false;
    }

    public function isTesting(): bool
    {
        return $this->filename == self::TEST_FILENAME;
    }
    
    public function useRealinput(): void
    {
        $this->filename = self::REAL_FILENAME;
    }

    abstract public function firstStar(): mixed;
    abstract public function secondStar(): mixed;
}
