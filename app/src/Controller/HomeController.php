<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('home/home.html.twig', [
            'app_display_name' => $this->getParameter('app.display_name'),
            'app_version' => $this->getParameter('app.release_version')
        ]);
    }
}
