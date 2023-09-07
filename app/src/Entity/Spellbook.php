<?php

namespace App\Entity;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Spellbook extends AbstractEntity
{
    private array $spells = [];

    public function __construct(Spell ...$spells)
    {
        $this->spells = $spells;

        $this->sortSpells();
    }

    public function getSpell(int $id): ?Spell
    {
        return $this->spells[$id] ?? null;
    }

    public function getSpells(): array
    {
        return $this->spells;
    }

    public function getFilterOptions(string $attribute): array
    {
        $options = [];
        foreach ($this->spells as $spell) {
            $options[] = $spell->$attribute;
        }

        return array_unique($options);
    }

    public function getSpellsBy(string $filter, array $values): Spellbook
    {
        $spells = array_filter($this->spells, fn (Spell $spell) => $spell->checkCriteria($filter, $values));

        return new Spellbook(...$spells);
    }

    public function getSpellbookPage(int $page, int $limit): Spellbook
    {
        $this->sortSpells();

        return new Spellbook(...array_slice($this->spells, ($page - 1) * $limit, $limit));
    }

    public function getRandomSpells(int $spellCount): Spellbook
    {
        shuffle($this->spells);

        return new Spellbook(...array_slice($this->spells, 0, $spellCount));
    }

    public function toArray(): array
    {
        return array_map(fn (Spell $spell) => $spell->toArray(), $this->spells);
    }

    private function sortSpells(): void
    {
        usort($this->spells, fn (Spell $a, Spell $b) => $a->spellLevel === $b->spellLevel
            ? strcmp($a->spellSlug, $b->spellSlug)
            : $a->spellLevel <=> $b->spellLevel
        );
    }
}
