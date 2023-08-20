<?php

namespace App\Entity;

class SpellTagList extends AbstractEntity implements Filterable
{
    private const KNOWN_TAGS = [
        AllowedClassList::RECORD_MAP,
        Publishing::RECORD_MAP,
        Spell::RECORD_MAP,
        SpellSchool::RECORD_MAP
    ];

    private array $tags = [];

    public function __construct(array $record)
    {
        $tags = array_filter($record, fn($value) => 1 == $value);
        foreach (self::KNOWN_TAGS as $dataMap) {
            $tags = array_filter($tags, fn($key) => !in_array($key, $dataMap), ARRAY_FILTER_USE_KEY);
        }

        $this->tags = array_keys($tags);
    }

    public function toArray(): array
    {
        return $this->tags;
    }

    /** @inheritDoc */
    public function checkCriteria(string $filter, array $values): bool
    {
        return count(array_intersect($values, $this->toArray())) > 0;
    }
}
