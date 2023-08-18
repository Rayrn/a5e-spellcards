<?php

namespace App\Entity;

class Publishing extends AbstractEntity
{
    public function __construct(
        public string $publisherName,
        public string $source,
        public ?int $page,
        public bool $isCore,
    )
    {
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}
