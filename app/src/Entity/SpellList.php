<?php

namespace App\Entity;

class SpellList extends AbstractEntity
{
    private array $spells = [];

    public function __construct(Spell ...$spells)
    {
        $this->spells = $spells;
    }

    public function getSpell(int $id): ?Spell
    {
        return $this->spells[$id] ?? null;
    }

    public function toArray(): array
    {
        return $this->spells;
    }
}
