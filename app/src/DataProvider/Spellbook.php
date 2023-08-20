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

    private const DATA_MAP__GENERIC = [
        'name' => 'Spell',
        'concentration' => 'Concentration',
        'ritual' => 'Ritual',
        'rareSpellCount' => 'Rare Spell Count',
        'level' => 'Spell Level',
        'school' => 'Classical School'
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

    private function transcribe(int $id, array $record): Spell
    {
        $tags = array_filter($record, fn($value) => $value == 1);
        $tags = array_filter(array_keys($tags), fn($value) => !in_array($value, array_merge(
            self::DATA_MAP__ADVENTURE_CLASS,
            self::DATA_MAP__PUBLISHING,
            self::DATA_MAP__GENERIC
        )));

        return new Spell(
            (int) $id,
            (string) (new AsciiSlugger())->slug($record[self::DATA_MAP__GENERIC['name']])->lower(),
            (string) $record[self::DATA_MAP__GENERIC['name']],
            new Publishing(
                $record[self::DATA_MAP__PUBLISHING['publisher']],
                $record[self::DATA_MAP__PUBLISHING['source']],
                null, # TODO: Add page data
                $record[self::DATA_MAP__PUBLISHING['publisher']] == "EN Publishing",
            ),
            (bool) $record[self::DATA_MAP__GENERIC['concentration']] == 1,
            (bool) $record[self::DATA_MAP__GENERIC['ritual']] == 1,
            (int) $record[self::DATA_MAP__GENERIC['rareSpellCount']],
            (int) $record[self::DATA_MAP__GENERIC['level']],
            new ClassicalSchool($record[self::DATA_MAP__GENERIC['school']]),
            new AdventureClass(
                $record[self::DATA_MAP__ADVENTURE_CLASS['artificer']] == 1,
                $record[self::DATA_MAP__ADVENTURE_CLASS['bard']] == 1,
                $record[self::DATA_MAP__ADVENTURE_CLASS['cleric']] == 1,
                $record[self::DATA_MAP__ADVENTURE_CLASS['druid']] == 1,
                $record[self::DATA_MAP__ADVENTURE_CLASS['elementalist']] == 1,
                $record[self::DATA_MAP__ADVENTURE_CLASS['herald']] == 1,
                $record[self::DATA_MAP__ADVENTURE_CLASS['sorcerer']] == 1,
                $record[self::DATA_MAP__ADVENTURE_CLASS['warlock']] == 1,
                $record[self::DATA_MAP__ADVENTURE_CLASS['wielder']] == 1,
                $record[self::DATA_MAP__ADVENTURE_CLASS['witch']] == 1,
                $record[self::DATA_MAP__ADVENTURE_CLASS['wizard']] == 1
            ),
            new Tag(...$tags)
        );
    }
}
