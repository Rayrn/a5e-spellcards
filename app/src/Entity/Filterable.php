<?php

namespace App\Entity;

interface Filterable
{
    public function checkCriteria(string $filter, array $values): bool;
}
