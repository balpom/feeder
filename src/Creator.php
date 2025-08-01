<?php

declare(strict_types=1);

namespace Balpom\Feeder;

use Balpom\Files\Writer;

abstract class Creator implements CreatorInterface
{
    protected Elements $elements;

    public function __construct(Elements $elements)
    {
        $this->elements = $elements;
    }

    abstract public function create(): string;

    /*
     * Create feed string and write it in file.
     */
    public function write(string $filePath): void
    {
        $result = $this->create();
        $writer = new Writer($filePath);
        $writer->write($result);
    }

}
