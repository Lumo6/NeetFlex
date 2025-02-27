<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class GeneralController extends  AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(ArtistRepository $artistRepository, EventRepository $eventRepository, Request $request): Response
    {
        // User
        $user = $this->getUser();
        $email = $user?->getEmail();

        // Recherche artiste
        $nameArtist = $request->query->get('nameArtist', '');
        if ($nameArtist) {
            $artists = $artistRepository->searchByName($nameArtist);
        } else {
            $artists = $artistRepository->findAll();
        }

        $dateFilter = $request->query->get('dateEvent');
        if ($dateFilter) {
            $date = \DateTime::createFromFormat('Y-m-d', $dateFilter);
            $events = $eventRepository->findByDate($date);
        } else {
            $events = $eventRepository->findAll();
        }

        return $this->render('general/home.html.twig', [
            "user" => $email,
            "artists" => $artists,
            "events" => $events,
            "searchTermArtist" => $nameArtist,
            'dateFilter' => $dateFilter
        ]);
    }
}