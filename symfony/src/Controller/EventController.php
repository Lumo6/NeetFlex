<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventCreationFormType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;


final class EventController extends AbstractController
{
    #[Route('/api/events', name: 'api_events', methods: ['GET'])]
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
                            new OA\Property(property: "artist", type: "object", properties: [
                                new OA\Property(property: "id", type: "integer"),
                                new OA\Property(property: "name", type: "string"),
                                new OA\Property(property: "image", type: "string"),
                                new OA\Property(property: "desc", type: "string"),
                            ]),
                            new OA\Property(property: "users", type: "array", items: new OA\Items(type: "string")),
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

        $data = array_map(fn($event) => [
            "id" => $event->getId(),
            "name" => $event->getName(),
            "date" => $event->getDate(),
            "artist" => $event->getArtist() ? [
                "id" => $event->getArtist()->getId(),
                "name" => $event->getArtist()->getName(),
                "image" => $event->getArtist()->getImage(),
                "desc" => $event->getArtist()->getDesc(),
            ] : null,
            "users" => array_map(fn($user) => $user->getEmail(), $event->getUsers()->toArray())
        ], $events);

        if (empty($data)) {
            return $this->json(['message' => 'Aucun événement trouvé'], 404);
        }

        return $this->json($data);
    }

    #[Route('/api/events/{id}', name: 'api_events_show', methods: ['GET'])]
    #[OA\Get(
        description: "Retourne les informations d'un événement en fonction de son ID, y compris les utilisateurs inscrits.",
        summary: "Récupère un événement spécifique avec les utilisateurs inscrits",
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
                        new OA\Property(property: "artist", type: "object", properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "name", type: "string"),
                            new OA\Property(property: "image", type: "string"),
                            new OA\Property(property: "desc", type: "string"),
                        ]),
                        new OA\Property(property: "users", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer"),
                                new OA\Property(property: "email", type: "string"),
                                new OA\Property(property: "name", type: "string"),
                            ]
                        )),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Événement non trouvé"
            )
        ]
    )]
    public function getEvent(int $id, EventRepository $eventRepository): JsonResponse
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            return $this->json(['message' => 'Événement non trouvé'], 404);
        }

        $data = [
            "id" => $event->getId(),
            "name" => $event->getName(),
            "date" => $event->getDate()->format('Y-m-d'),
            "artist" => $event->getArtist() ? [
                "id" => $event->getArtist()->getId(),
                "name" => $event->getArtist()->getName(),
                "image" => $event->getArtist()->getImage(),
                "desc" => $event->getArtist()->getDesc(),
            ] : null,
            "users" => array_map(fn($user) => [
                "id" => $user->getId(),
                "email" => $user->getEmail()
            ], $event->getUsers()->toArray())
        ];

        return $this->json($data);
    }

    #[Route('/events', name: 'app_events')]
    public function index(EventRepository $eventRepository, Request $request): Response
    {
        $dateFilter = $request->query->get('dateEvent');
        if ($dateFilter) {
            $date = \DateTime::createFromFormat('Y-m-d', $dateFilter);
            $events = $eventRepository->findByDate($date);
        } else {
            $events = $eventRepository->findBy([], ['id' => 'DESC']);
        }

        // Passer les événements à la vue
        return $this->render('event/index.html.twig', [
            'events' => $events,
            'dateFilter' => $dateFilter
        ]);
    }

    #[Route('/events/create', name: 'app_events_create')]
    public function createEvent(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventCreationFormType::class, $event);

        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $event->addUser($user);

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_events_success');
        }

        return $this->render('event/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/events/success', name: 'app_events_success')]
    public function success(): Response
    {
        return $this->render('event/success.html.twig');
    }

    #[Route('/events/follow', name: 'app_events_follow')]
    public function followEvent(EventRepository $eventRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir vos événements.');
        }

        $events = $eventRepository->findByUserEmail($user->getEmail());

        return $this->render('event/follow.html.twig', [
            'events' => $events
        ]);
    }

    #[Route('/events/{id}', name: 'app_events_show', methods: ['GET'])]
    public function show(int $id, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé.');
        }

        $isCreator = $event->getCreator() === $this->getUser();

        $isRegistered = $event->getUsers()->contains($this->getUser());

        return $this->render('event/show.html.twig', [
            'event' => $event,
            "isCreator" => $isCreator,
            "isRegistered" => $isRegistered,
        ]);
    }

    #[Route('/events/{id}/edit', name: 'app_events_edit')]
    public function editEvent(int $id, EventRepository $eventRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'existe pas.');
        }

        $user = $this->getUser();

        if ($event->getCreator() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier cet événement.');
        }

        $form = $this->createForm(EventCreationFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_events_show', ['id' => $event->getId()]);
        }

        return $this->render('event/edit.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }

    #[Route('/events/{id}/delete', name: 'app_events_delete', methods: ['POST'])]
    public function deleteEvent(int $id, EventRepository $eventRepository, EntityManagerInterface $entityManager): Response
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'existe pas.');
        }

        $user = $this->getUser();

        if ($event->getCreator() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer cet événement.');
        }

        $entityManager->remove($event);
        $entityManager->flush();

        // Redirection après la suppression
        return $this->redirectToRoute('app_home');
    }

    #[Route('/events/{id}/register', name: 'app_events_register', methods: ['POST'])]
    public function register(EventRepository $eventRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $event = $eventRepository->find($id);

        if ($event) {
            $user = $this->getUser();
            $event->addUser($user);

            $entityManager->flush();
        }

        return $this->redirectToRoute('app_events_show', ['id' => $id]);
    }

    #[Route('/events/{id}/unregister', name: 'app_events_unregister', methods: ['POST'])]
    public function unregister(EventRepository $eventRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $event = $eventRepository->find($id);

        if ($event) {
            $user = $this->getUser();
            $event->removeUser($user);

           $entityManager->flush();
        }

        return $this->redirectToRoute('app_events_show', ['id' => $id]);
    }
}
