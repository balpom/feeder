<?php

declare(strict_types=1);

namespace Balpom\Feeder;

interface CreatorInterface
{
    /*
     * Create feed string.
     * It may be in XML, YML, JSON and some another format.
     */
    public function create(): string;
}
