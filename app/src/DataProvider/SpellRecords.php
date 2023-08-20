<?php

namespace App\DataProvider;

use IteratorAggregate;
use League\Csv\Reader;
use League\Csv\MapIterator;

class SpellRecords implements IteratorAggregate
{
    private ?MapIterator $spellData = null;

    public function __construct(private string $dataPath)
    {
    }

    public function getIterator(): MapIterator
    {
        return $this->getRecords();
    }

    public function getRecords(): MapIterator
    {
        if (empty($this->spellData)) {
            $this->spellData = Reader::createFromPath($this->dataPath, 'r')
                ->setHeaderOffset(0)
                ->getRecords();
        }

        return $this->spellData;
    }
}
