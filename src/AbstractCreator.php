<?php

declare(strict_types=1);

namespace Balpom\Feeder;

use Balpom\Files\Writer;
use Balpom\Entity\Structures\AbstractStructure;

abstract class AbstractCreator implements CreatorInterface
{
    protected AbstractStructure $structure;

    public function __construct(AbstractStructure $structure)
    {
        $this->structure = $structure;
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
