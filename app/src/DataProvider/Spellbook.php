<?php

namespace App\DataProvider;

use App\Entity\Map\AdventureClass;
use App\Entity\Map\ClassicalSchool;
use App\Entity\Map\Tag;
use App\Entity\Publishing;
use App\Entity\Spell;
use App\Entity\SpellList;
use ArrayIterator;
use IteratorAggregate;
use League\Csv\Reader;
use Symfony\Component\String\Slugger\AsciiSlugger;

class Spellbook implements IteratorAggregate
{
    private const DATA_MAP__PUBLISHING = [
        'publisher' => 'Publisher',
        'source' => 'Source',
        'page' => null,
    ];

    private const DATA_MAP__ADVENTURE_CLASS = [
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
        'wizard' => 'Wizard',
    ];

    private const DATA_MAP = [
        'name' => 'Spell',
        'publishing' => self::DATA_MAP__PUBLISHING,
        'concentration' => 'Concentration',
        'ritual' => 'Ritual',
        'rareSpellCount' => 'Rare Spell Count',
        'level' => 'Spell Level',
        'school' => 'Classical School',
        'classMap' => self::DATA_MAP__ADVENTURE_CLASS,
    ];

    private ?SpellList $spellList = null;

    public function __construct(private string $dataPath)
    {
    }

    public function open(): SpellList
    {
        $this->research();

        return $this->spellList;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator([]);
    }

    private function research(): void
    {
        if ($this->spellList) {
            return;
        }

        $reader = Reader::createFromPath($this->dataPath, 'r');
        $reader->setHeaderOffset(0);

        $spells = [];
        foreach ($reader->getRecords() as $id => $record) {
            $spells[] = $this->transcribe($id, $record);
        }

        $this->spellList = new SpellList(...$spells);
    }

    # TODO: This is a mess. Refactor.
    private function transcribe(int $id, array $record): Spell
    {
        $adventureClass = new AdventureClass(
            $record[self::DATA_MAP__ADVENTURE_CLASS['artificer']] == "1",
            $record[self::DATA_MAP__ADVENTURE_CLASS['bard']] == "1",
            $record[self::DATA_MAP__ADVENTURE_CLASS['cleric']] == "1",
            $record[self::DATA_MAP__ADVENTURE_CLASS['druid']] == "1",
            $record[self::DATA_MAP__ADVENTURE_CLASS['elementalist']] == "1",
            $record[self::DATA_MAP__ADVENTURE_CLASS['herald']] == "1",
            $record[self::DATA_MAP__ADVENTURE_CLASS['sorcerer']] == "1",
            $record[self::DATA_MAP__ADVENTURE_CLASS['warlock']] == "1",
            $record[self::DATA_MAP__ADVENTURE_CLASS['wielder']] == "1",
            $record[self::DATA_MAP__ADVENTURE_CLASS['witch']] == "1",
            $record[self::DATA_MAP__ADVENTURE_CLASS['wizard']] == "1"
        );

        $publishing = new Publishing(
            $record[self::DATA_MAP__PUBLISHING['publisher']],
            $record[self::DATA_MAP__PUBLISHING['source']],
            null, # TODO: Add page data
            $record[self::DATA_MAP__PUBLISHING['source']] === "Adventurer's Guide",
        );

        $nonTagKeyList = array_merge(
            self::DATA_MAP__ADVENTURE_CLASS,
            self::DATA_MAP__PUBLISHING,
            self::DATA_MAP,
            ['multiClass' => 'Multiclass'] # Used in 3rd Party Addons
        );

        $tags = array_filter($record, fn($value, $key) => ($value == "1" && !in_array($key, $nonTagKeyList)), ARRAY_FILTER_USE_BOTH);

        $slug = (new AsciiSlugger())->slug($record[self::DATA_MAP['name']])->lower();

        return new Spell(
            (int) $id,
            $slug,
            (string) $record[self::DATA_MAP['name']],
            $publishing,
            $record[self::DATA_MAP['concentration']] == "1",
            $record[self::DATA_MAP['ritual']] == "1",
            (int) $record[self::DATA_MAP['rareSpellCount']],
            (int) $record[self::DATA_MAP['level']],
            new ClassicalSchool($record[self::DATA_MAP['school']]),
            $adventureClass,
            new Tag(...array_keys($tags))
        );
    }
}
