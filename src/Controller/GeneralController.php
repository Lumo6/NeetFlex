<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class GeneralController extends  AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('general/home.html.twig');
    }
}