<?php

namespace App\Entity\Map;

use App\Entity\AbstractEntity;

class AdventureClass extends AbstractEntity
{
    public function __construct(
        public bool $artificer = false,
        public bool $bard = false,
        public bool $cleric = false,
        public bool $druid = false,
        public bool $elementalist = false,
        public bool $herald = false,
        public bool $sorcerer = false,
        public bool $warlock = false,
        public bool $wielder = false,
        public bool $witch = false,
        public bool $wizard = false
    )
    {
    }

    public function getActive(): array
    {
        return array_keys(
            array_filter($this->toArray(), fn($value) => $value === true)
        );
    }

    public function toArray(): array
    {
        return (array) $this;
    }

    public static function listOptions(): array
    {
        return array_keys(get_class_vars(self::class));
    }
}
