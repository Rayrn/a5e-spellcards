<?php

namespace App\Entity;

use App\Helpers\SlugifyTrait;

class Publishing extends AbstractEntity implements Filterable
{
    use SlugifyTrait;

    public const RECORD_MAP = [
        'publisherName' => 'Publisher',
        'source' => 'Source',
    ];

    public string $publisherName;
    public string $publisherSlug;
    public string $source;
    public string $sourceSlug;

    public function __construct(array $spellRecord)
    {
        $this->publisherName = (string) $spellRecord[self::RECORD_MAP['publisherName']];
        $this->publisherSlug = $this->slugify($this->publisherName);
        $this->source = (string) $spellRecord[self::RECORD_MAP['source']];
        $this->sourceSlug = $this->slugify($this->source);
    }

    public function toArray(): array
    {
        return (array) $this;
    }

    /** @inheritDoc */
    public function checkCriteria(string $filter, array $values): bool
    {
        if ($filter === 'publisher-name') {
            return in_array($this->publisherSlug, $values);
        }

        if ($filter === 'publisher-source') {
            return in_array($this->sourceSlug, $values);
        }

        return false;
    }
}
