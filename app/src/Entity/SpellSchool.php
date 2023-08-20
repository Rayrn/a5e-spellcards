<?php

namespace App\Entity;

class SpellSchool extends AbstractEntity implements Filterable
{
    public const RECORD_MAP = [
        'school' => 'Classical School'
    ];

    public bool $abjuration = false;
    public bool $conjuration = false;
    public bool $divination = false;
    public bool $enchantment = false;
    public bool $evocation = false;
    public bool $illusion = false;
    public bool $necromancy = false;
    public bool $transmutation = false;

    public function __construct(array $record)
    {
        $school = strtolower($record[self::RECORD_MAP['school']]);

        if (property_exists($this, $school)) {
            $this->$school = true;
        }
    }

    public function toArray(): array
    {
        return array_keys(array_filter((array) $this));
    }

    public static function listOptions(): array
    {
        return array_keys(get_class_vars(self::class));
    }

    /** @inheritDoc */
    public function checkCriteria(string $filter, array $values): bool
    {
        return count(array_intersect($values, $this->toArray())) > 0;
    }
}
