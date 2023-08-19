<?php

namespace App\Entity\Map;

use App\Entity\AbstractEntity;

class ClassicalSchool extends AbstractEntity
{
    public bool $abjuration = false;
    public bool $conjuration = false;
    public bool $divination = false;
    public bool $enchantment = false;
    public bool $evocation = false;
    public bool $illusion = false;
    public bool $necromancy = false;
    public bool $transmutation = false;

    public function __construct(string $school)
    {
        $school = strtolower($school);

        if (property_exists($this, $school)) {
            $this->$school = true;
        }
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
