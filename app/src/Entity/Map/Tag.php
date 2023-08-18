<?php

namespace App\Entity\Map;

use App\Entity\AbstractEntity;

class Tag extends AbstractEntity
{
    private array $tags = [];

    public function __construct(string ...$tags)
    {
        $this->tags = $tags;
    }

    public function getActive(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return $this->tags;
    }
}
