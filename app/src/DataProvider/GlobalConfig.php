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
        return array_merge(
            $this->loadGlobalParameters(),
            $this->loadQueryParameters()
        );
    }

    private function loadGlobalParameters(): array
    {
        $globalParameters = [];
        foreach (self::APP_PARAMETERS_MAP as $parameterName => $globalParameterName) {
            $globalParameters[$globalParameterName] = $this->parameterBag->get($parameterName);
        }

        return $globalParameters;
    }

    private function loadQueryParameters(): array
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if (!($currentRequest instanceof Request)) {
            return [];
        }

        $queryParameters['query'] = $currentRequest->query->all();
        ksort($queryParameters['query']);
        unset($queryParameters['query']['page']);

        $currentPage = $currentRequest->query->getInt('page', 1);
        $queryParameters['page'] = $currentPage < 1 ? 1 : $currentPage;

        return $queryParameters;
    }
}
