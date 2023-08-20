<?php

namespace App\DataProvider;

use ArrayIterator;
use IteratorAggregate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GlobalConfig implements IteratorAggregate
{
    private const APP_PARAMETERS_MAP = [
        'app.display_name' => 'app_display_name',
        'app.release_version' => 'release_version',
        'app.pagination.limit' => 'limit'
    ];

    private array $globalParameters = [];
    private array $queryParameters = [];

    public function __construct(
        private ParameterBagInterface $parameterBag,
        private RequestStack $requestStack
    )
    {
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->getGlobalParameters());
    }

    public function getGlobalParameters(): array
    {
        $this->setGlobalParameters();

        return array_merge($this->globalParameters, $this->queryParameters);
    }

    public function setGlobalParameters(): void
    {
        if (empty($this->globalParameters)) {
            foreach (self::APP_PARAMETERS_MAP as $parameterName => $globalParameterName) {
                $this->globalParameters[$globalParameterName] = $this->parameterBag->get($parameterName);
            }
        }

        if (empty($this->queryParameters)) {
            $currentRequest = $this->requestStack->getCurrentRequest();

            if ($currentRequest instanceof Request) {
                $this->queryParameters['page'] = $currentRequest->query->get('page', 1);

                if ($this->queryParameters['page'] < 1) {
                    $this->queryParameters['page'] = 1;
                }
            }
        }
    }
}
