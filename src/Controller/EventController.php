<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

use OpenApi\Attributes as OA;

final class EventController extends AbstractController
{
    #[Route('/api/events', name: 'app_events', methods: ['GET'])]
    #[OA\Get(
        description: "Retourne tous les événements disponibles.",
        summary: "Récupère la liste des événements",
        tags: ["Événements"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des événements",
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "name", type: "string"),
                            new OA\Property(property: "date", type: "string"),
                            new OA\Property(property: "artist", type: "Artist"),
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 404,
                description: "Aucun événement trouvé"
            )
        ]
    )]
    public function getEvents(EventRepository $eventRepository): JsonResponse
    {
        $events = $eventRepository->findAll();

        $data = array_map(fn($events) => [
            "id" => $events->getId(),
            "name" => $events->getName(),
            "date" => $events->getDate(),
            "artist" => $events->getArtist()
        ], $events);

        if (!$data) {
            return $this->json(['message' => 'Aucun event trouvé']);
        }

        return $this->json($data);
    }

    #[Route('/api/events/{id}', name: 'api_events_show', methods: ['GET'])]
    #[OA\Get(
        description: "Retourne les informations d'un événement en fonction de son ID.",
        summary: "Récupère un événement spécifique",
        tags: ["Événements"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID de l'événement",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Détails de l'événement",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "name", type: "string"),
                        new OA\Property(property: "date", type: "string"),
                        new OA\Property(property: "artist", type: "Artist"),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Événement non trouvé"
            )
        ]
    )]
    public function getArtist(int $id, EventRepository $eventRepository): JsonResponse
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            return $this->json(['message' => 'Event non trouvé'], 404);
        }

        $data = [
            "id" => $event->getId(),
            "name" => $event->getName(),
            "date" => $event->getDate(),
            "artist" => $event->getArtist()
        ];

        return $this->json($data);
    }
}
