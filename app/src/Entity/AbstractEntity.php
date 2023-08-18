<?php

namespace App\Entity;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;

abstract class AbstractEntity implements IteratorAggregate, JsonSerializable
{
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->toArray());
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    abstract public function toArray(): array;
}
