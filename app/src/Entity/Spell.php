<?php

namespace App\Entity;

class Spell extends AbstractEntity
{
    public function __construct(
        public int $id,
        public string $name,
        public Publishing $publishing,
        public bool $concentration,
        public bool $ritual,
        public int $rareSpellCount,
        public int $spellLevel,
        public Map\ClassicalSchool $school,
        public Map\AdventureClass $classMap,
        public Map\Tag $tag
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'publishing' => $this->publishing->toArray(),
            'concentration' => $this->concentration,
            'ritual' => $this->ritual,
            'rareSpellCount' => $this->rareSpellCount,
            'spellLevel' => $this->spellLevel,
            'school' => $this->school->toArray(),
            'classMap' => $this->classMap->toArray(),
            'tag' => $this->tag->toArray(),
        ];
    }
}
