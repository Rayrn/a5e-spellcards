<?php

namespace App\Entity;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SpellList extends AbstractEntity
{
    private array $spells = [];

    public function __construct(Spell ...$spells)
    {
        $this->spells = $spells;
    }

    /** @throws NotFoundHttpException */
    public function getPage(int $page, int $limit): SpellList
    {
        $spells = array_slice($this->spells, ($page - 1) * $limit, $limit);

        if (empty($spells)) {
            throw new NotFoundHttpException('No spells found!');
        }

        return new SpellList(...$spells);
    }

    public function getSpell(int $id): ?Spell
    {
        return $this->spells[$id] ?? null;
    }

    public function filter(string $filter, string $value): SpellList
    {
        $spells = array_filter($this->spells, fn (Spell $spell) => match ($filter) {
            'class' => in_array($value, $spell->classMap->getActive()) === true,
            'school' => in_array($value, $spell->school->getActive()) === true,
            'tag' => in_array($value, $spell->tag->getActive()) === true,

            'publisherName' => $spell->publishing->publisherName == $value,
            'source' => $spell->publishing->source == $value,
            'isCore' => $spell->publishing->isCore == $value,

            default => $spell->$filter == $value,
        });

        return new SpellList(...$spells);
    }

    public function listSpells(): array
    {
        return $this->spells;
    }

    public function shuffleSpells(int $count): SpellList
    {
        $spells = $this->spells;
        shuffle($spells);
        $spells = array_slice($spells, 0, $count);

        return new SpellList(...$spells);
    }

    public function sort(): void
    {
        usort($this->spells, fn (Spell $a, Spell $b) => $a->level === $b->level
            ? strcmp($a->name, $b->name)
            : $a->level <=> $b->level
        );
    }

    public function toArray(): array
    {
        return array_map(fn (Spell $spell) => $spell->toArray(), $this->spells);
    }
}
