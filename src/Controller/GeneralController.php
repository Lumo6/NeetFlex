<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class GeneralController extends  AbstractController
{
    #[Route('/', name: 'app_accueil', methods: ['GET'])]
    public function accueil(): Response
    {
        return $this->render('general/accueil.html.twig');
    }
}