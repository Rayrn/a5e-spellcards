<?php

namespace App\DataProvider;

use ArrayIterator;
use IteratorAggregate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GlobalConfig implements IteratorAggregate
{
    private const GLOBAL_PARAMETERS_MAP = [
        'app.display_name' => 'app_display_name',
        'app.release_version' => 'app_version'
    ];

    private array $globalParameters = [];

    public function __construct(private ParameterBagInterface $parameterBag)
    {
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->getGlobalParameters());
    }

    public function getGlobalParameters(): array
    {
        if (empty($this->globalParameters)) {
            $this->setGlobalParameters();
        }

        return $this->globalParameters;
    }

    public function setGlobalParameters(): void
    {
        foreach (self::GLOBAL_PARAMETERS_MAP as $parameterName => $globalParameterName) {
            $this->globalParameters[$globalParameterName] = $this->parameterBag->get($parameterName);
        }
    }
}
