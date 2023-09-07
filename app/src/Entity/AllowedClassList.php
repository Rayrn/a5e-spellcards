<?php

namespace App\Entity;

class AllowedClassList extends AbstractEntity implements Filterable
{
    public const RECORD_MAP = [
        'artificer' => 'Artificer',
        'bard' => 'Bard',
        'cleric' => 'Cleric',
        'druid' => 'Druid',
        'elementalist' => 'Elementalist',
        'herald' => 'Herald',
        'sorcerer' => 'Sorcerer',
        'warlock' => 'Warlock',
        'wielder' => 'Wielder',
        'witch' => 'Witch',
        'wizard' => 'Wizard'
    ];

    public bool $artificer = false;
    public bool $bard = false;
    public bool $cleric = false;
    public bool $druid = false;
    public bool $elementalist = false;
    public bool $herald = false;
    public bool $sorcerer = false;
    public bool $warlock = false;
    public bool $wielder = false;
    public bool $witch = false;
    public bool $wizard = false;

    public function __construct(array $spellRecord)
    {
        foreach (self::RECORD_MAP as $key => $value) {
            $this->$key = (bool) $spellRecord[$value] == 1;
        }
    }

    public function toArray(): array
    {
        return array_keys(array_filter((array) $this));
    }

    /** @inheritDoc */
    public function checkCriteria(string $filter, array $values): bool
    {
        return count(array_intersect($values, $this->toArray())) > 0;
    }
}
