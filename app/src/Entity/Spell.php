<?php

namespace App\Entity;

use InvalidArgumentException;
use App\Helpers\SlugifyTrait;

class Spell extends AbstractEntity implements Filterable
{
    use SlugifyTrait;

    public const RECORD_MAP = [
        'concentration' => 'Concentration',
        'rareSpellCount' => 'Rare Spell Count',
        'ritual' => 'Ritual',
        'spellLevel' => 'Spell Level',
        'spellName' => 'Spell',
        'spellSlug' => 'Spell'
    ];

    public const FILTER_MAP = [
        'class' => 'allowedClassList',
        'concentration' => 'concentration',
        'level' => 'spellLevel',
        'name' => 'spellSlug',
        'publisher-name' => 'publishing',
        'publisher-source' => 'publishing',
        'rare-spell-count' => 'rareSpellCount',
        'ritual' => 'ritual',
        'school' => 'spellSchool',
        'tag' => 'spellTagList'
    ];

    public int $id;

    public AllowedClassList $allowedClassList;
    public Publishing $publishing;
    public SpellSchool $spellSchool;
    public SpellTagList $spellTagList;

    public bool $concentration;
    public int $rareSpellCount;
    public bool $ritual;
    public int $spellLevel;
    public string $spellName;
    public string $spellSlug;

    public function __construct(array $spellRecord)
    {
        $this->id = (int) $spellRecord['id'];

        $this->concentration = (bool) $spellRecord[self::RECORD_MAP['concentration']] == 1;
        $this->rareSpellCount = (int) $spellRecord[self::RECORD_MAP['rareSpellCount']];
        $this->ritual = (bool) $spellRecord[self::RECORD_MAP['ritual']] == 1;
        $this->spellLevel = (int) $spellRecord[self::RECORD_MAP['spellLevel']];
        $this->spellName = (string) $spellRecord[self::RECORD_MAP['spellName']];
        $this->spellSlug = $this->slugify($this->spellName);

        $this->allowedClassList = new AllowedClassList($spellRecord);
        $this->publishing = new Publishing($spellRecord);
        $this->spellSchool = new SpellSchool($spellRecord);
        $this->spellTagList = new SpellTagList($spellRecord);
    }

    public function toArray(): array
    {
        $data = [];
        foreach ($this as $key => $value) {
            if ($value instanceof AbstractEntity) {
                $data[$key] = $value->toArray();
                continue;
            }

            $data[$key] = $value;
        }

        return $data;
    }

    /** @throws InvalidArgumentException */
    public function checkCriteria(string $filter, array $values): bool
    {
        if (!array_key_exists($filter, self::FILTER_MAP)) {
            throw new InvalidArgumentException ('Invalid search criteria');
        }

        $attribute = $this->{self::FILTER_MAP[$filter]};

        if ($attribute instanceof Filterable) {
            return $attribute->checkCriteria($filter, $values);
        }

        return in_array($attribute, $values);
    }
}
