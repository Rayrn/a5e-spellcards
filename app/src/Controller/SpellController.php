<?php

namespace App\Controller;

use App\DataProvider\GlobalConfig;
use App\DataProvider\Spellbook;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SpellController extends AbstractController
{
    public function __construct(
        private GlobalConfig $globalConfig,
        private Spellbook $spellbook
    ) {}

    #[Route('/spell/random', name: 'spell--random')]
    public function spellRandom(): Response
    {
        $spellList = $this->spellbook->open();
        $spell = $spellList->getSpell(rand(0, count($spellList->toArray()) - 1));

        $context = $this->globalConfig->getGlobalParameters();

        return $this->render('partials/spellDetail.twig', array_merge(
            $this->globalConfig->getGlobalParameters(),
            [
                'title' => 'Random Spell',
                'spell' => $spell
            ]
        ));
    }

    #[Route('/spell/class', name: 'spell--class')]
    public function spellClass(): Response
    {

        $context = $this->globalConfig->getGlobalParameters();

        return $this->render('partials/spellList.twig', array_merge(
            $this->globalConfig->getGlobalParameters(),
            [
                'title' => 'Spells by Class'
            ]
        ));
    }

    #[Route('/spell/level', name: 'spell--level')]
    public function spellLevel(): Response
    {
        $context = $this->globalConfig->getGlobalParameters();

        return $this->render('partials/spellList.twig', array_merge(
            $this->globalConfig->getGlobalParameters(),
            [
                'title' => 'Spells by Level'
            ]
        ));
    }

    #[Route('/spell/school', name: 'spell--school')]
    public function spellSchool(): Response
    {
        $context = $this->globalConfig->getGlobalParameters();

        return $this->render('partials/spellList.twig', array_merge(
            $this->globalConfig->getGlobalParameters(),
            [
                'title' => 'Spells by School'
            ]
        ));
    }

    #[Route('/spell/detail/{id}', name: 'spell--detail')]
    public function spellDetail(int $id): Response
    {
        $context = $this->globalConfig->getGlobalParameters();

        $spell = $this->spellbook->open()->getSpell($id - 1);

        if (!$spell) {
            throw $this->createNotFoundException('The spell does not exist');
        }

        return $this->render('partials/spellDetail.twig', array_merge(
            $this->globalConfig->getGlobalParameters(),
            [
                'spell' => $spell,
            ]
        ));
    }
}
