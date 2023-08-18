<?php

namespace App\Controller;

use App\DataProvider\GlobalConfig;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(private GlobalConfig $globalConfig)
    {
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        $context = $this->globalConfig->getGlobalParameters();

        return $this->render('home/home.twig', array_merge(
            $this->globalConfig->getGlobalParameters(),
            [
                'title' => 'Home',
                'description' => 'Home page',
            ]
        ));
    }
}
