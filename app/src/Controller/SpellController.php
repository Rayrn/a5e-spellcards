<?php

namespace App\Controller;

use App\DataProvider\GlobalConfig;
use App\DataProvider\Spellbook;
use App\Entity\Map\AdventureClass;
use App\Entity\Map\ClassicalSchool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpellController extends AbstractController
{
    public function __construct(
        private GlobalConfig $globalConfig,
        private Spellbook $spellbook,
        private RequestStack $requestStack,
    )
    {
    }

    #[Route('/spellbook', name: 'spell--book')]
    #[cache(60)]
    public function spellBook(): Response
    {
        $spellList = $this->spellbook->open();
        $spellList->sort();

        return $this->render('partials/spellList.twig', array_merge(
            $this->globalConfig->getGlobalParameters(),
            [
                'title' => 'Spells by Class',
                'spells' => $spellList,
            ]
        ));
    }

    #[Route('/spellbook/{filter}', name: 'spell--filter', requirements: ['filter' => 'class|level|school'])]
    #[cache(60)]
    public function spellFilter(string $filter): Response
    {
        $listOptions = match ($filter) {
            'class' => AdventureClass::listOptions(),
            'level' => range(0, 10),
            'school' => ClassicalSchool::listOptions(),
        };

        return $this->render('partials/filterList.twig', array_merge(
            $this->globalConfig->getGlobalParameters(),
            [
                'title' => 'Spells by ' . ucfirst($filter),
                'optionKey' => $filter,
                'optionList' => $listOptions,
                'optionType' => $filter == 'level' ? 'numeric' : 'text',
            ]
        ));
    }

    #[Route('/spellbook/random', name: 'spell--random')]
    #[cache(1)]
    public function spellRandom(): Response
    {
        $spellList = $this->spellbook->open()->shuffleSpells(6);
        $spellList->sort();

        return $this->render('partials/spellList.twig', array_merge(
            $this->globalConfig->getGlobalParameters(),
            [
                'title' => 'Pew Pew Pew!',
                'spells' => $spellList,
            ]
        ));
    }

    #[Route('/spellbook/{filter}/{value}', name: 'spell--search')]
    #[cache(60)]
    public function spellSearch(string $filter, mixed $value): Response
    {
        $spellList = $this->spellbook->open()->filter($filter, $value);
        $spellList->sort();

        if (empty($spellList->listSpells())) {
            throw $this->createNotFoundException('No spells found!');
        }

        return $this->render('partials/spellList.twig', array_merge(
            $this->globalConfig->getGlobalParameters(),
            [
                'title' => 'Spells by Class',
                'spells' => $spellList,
            ]
        ));
    }
}
