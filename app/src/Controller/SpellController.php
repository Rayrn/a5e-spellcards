<?php

namespace App\Controller;

use App\DataProvider\GlobalConfig;
use App\DataProvider\SpellRecords;
use App\Entity\AllowedClassList;
use App\Entity\Spell;
use App\Entity\Spellbook;
use App\Entity\SpellSchool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpellController extends AbstractController
{
    private ?Spellbook $spellbook = null;

    public function __construct(
        private GlobalConfig $globalConfig,
        private SpellRecords $spellRecords,
        private RequestStack $requestStack
    )
    {
    }

    #[Route('/spellbook', name: 'spell--book')]
    #[cache(60)]
    public function spellBook(): Response
    {
        $spellbook = $this->getSpellbook();
        foreach ($this->requestStack->getCurrentRequest()->query as $key => $value) {
            if ($key === 'page') {
                continue;
            }

            foreach ((array) $value as $filterSet) {
                $spellbook = $spellbook->getSpellsBy($key, explode(',', $filterSet));
            }
        }

        return $this->render('partials/spellList.twig', array_merge(
            $this->globalConfig->getGlobalParameters(),
            [
                'title' => 'Spellbook',
                'spellbook' => $spellbook
            ]
        ));
    }

    #[Route('/spellbook/random', name: 'spell--random')]
    #[cache(1)]
    public function spellRandom(): Response
    {
        return $this->render('partials/spellList.twig', array_merge(
            $this->globalConfig->getGlobalParameters(),
            [
                'title' => 'Pew Pew Pew!',
                'spellbook' => $this->getSpellbook()->getRandomSpells(6),
            ]
        ));
    }

    private function getSpellbook(): Spellbook
    {
        if ($this->spellbook === null) {

            $spells = [];
            foreach ($this->spellRecords as $id => $spellRecord) {
                $spellRecord['id'] = $id;
                $spells[] = new Spell($spellRecord);
            }

            $this->spellbook = new Spellbook(...$spells);
        }

        return $this->spellbook;
    }
}
