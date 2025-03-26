<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistCreationFormType;
use App\Repository\ArtistRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

final class ArtistController extends AbstractController
{
    #[Route('/api/artists', name: 'api_artists', methods: ['GET'])]
    #[OA\Get(
        description: "Retourne un tableau de tous les artistes disponibles.",
        summary: "Récupère la liste des artistes",
        tags: ["Artistes"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des artistes",
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "name", type: "string"),
                            new OA\Property(property: "desc", type: "string"),
                            new OA\Property(property: "image", type: "string")
                        ]
                    )
                )
            )
        ]
    )]
    public function getArtists(ArtistRepository $artistRepository): JsonResponse
    {
        $artists = $artistRepository->findAll();

        $data = array_map(fn($artist) => [
            "id" => $artist->getId(),
            "name" => $artist->getName(),
            "desc" => $artist->getDesc(),
            "image" => $artist->getImage()
        ], $artists);

        if (!$data) {
            return $this->json(['message' => 'Aucun artiste trouvé'], 404);
        }

        return $this->json($data);
    }

    #[Route('/api/artists/{id}', name: 'api_artist_show', methods: ['GET'])]
    #[OA\Get(
        description: "Retourne les informations d'un artiste en fonction de son ID, y compris ses événements.",
        summary: "Récupère un artiste spécifique avec ses événements",
        tags: ["Artistes"],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "ID de l'artiste",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Détails de l'artiste avec ses événements",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "name", type: "string"),
                        new OA\Property(property: "desc", type: "string"),
                        new OA\Property(property: "image", type: "string"),
                        new OA\Property(
                            property: "events",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer"),
                                    new OA\Property(property: "name", type: "string"),
                                    new OA\Property(property: "date", type: "string", format: "date"),
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Artiste non trouvé"
            )
        ]
    )]
    public function getArtist(int $id, ArtistRepository $artistRepository, EventRepository $eventRepository): JsonResponse
    {
        $artist = $artistRepository->find($id);

        if (!$artist) {
            return $this->json(['message' => 'Artiste non trouvé'], 404);
        }

        // Récupérer les événements associés à l'artiste
        $events = $eventRepository->findBy(['artist' => $artist]);

        $data = [
            "id" => $artist->getId(),
            "name" => $artist->getName(),
            "desc" => $artist->getDesc(),
            "image" => $artist->getImage(),
            "events" => array_map(fn($event) => [
                "id" => $event->getId(),
                "name" => $event->getName(),
                "date" => $event->getDate()->format('Y-m-d'),
            ], $events)
        ];

        return $this->json($data);
    }

    #[Route('/artists', name: 'app_artists')]
    public function index(ArtistRepository $artistRepository, Request $request): Response
    {
        $nameArtist = $request->query->get('nameArtist', '');
        if ($nameArtist) {
            $artists = $artistRepository->searchByName($nameArtist);
        } else {
            $artists = $artistRepository->findBy([], ['id' => 'DESC']);
        }

        return $this->render('artist/index.html.twig', [
            'artists' => $artists,
            "searchTermArtist" => $nameArtist,
        ]);
    }

    #[Route('/artists/create', name: 'app_artists_create')]
    public function createArtist(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $artist = new Artist();
        $form = $this->createForm(ArtistCreationFormType::class, $artist);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile instanceof UploadedFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('artist_images_directory'),
                    $newFilename
                );
                $artist->setImage($newFilename);
            }

            $entityManager->persist($artist);
            $entityManager->flush();

            return $this->redirectToRoute('app_artists_success');
        }

        return $this->render('artist/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/artists/{id}/edit', name: 'app_artists_edit')]
    public function editArtist(int $id, Request $request, ArtistRepository $artistRepository, EntityManagerInterface $entityManager): Response
    {
        $artist = $artistRepository->find($id);

        if (!$artist) {
            throw $this->createNotFoundException('Artist not found');
        }

        $originalImage = $artist->getImage();
        $form = $this->createForm(ArtistCreationFormType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                if ($originalImage) {
                    $filesystem = new Filesystem();
                    $imagePath = $this->getParameter('artist_images_directory') . '/' . $originalImage;

                    if ($filesystem->exists($imagePath)) {
                        $filesystem->remove($imagePath);
                    }
                }

                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('artist_images_directory'),
                    $newFilename
                );

                $artist->setImage($newFilename);
            } else {
                $artist->setImage($originalImage);
            }

            $entityManager->persist($artist);
            $entityManager->flush();

            return $this->redirectToRoute('app_artists_show', ['id' => $artist->getId()]);
        }

        return $this->render('artist/edit.html.twig', [
            'form' => $form->createView(),
            'artist' => $artist,
        ]);
    }


    #[Route('/artists/success', name: 'app_artists_success')]
    public function success(): Response
    {
        return $this->render('artist/success.html.twig');
    }

    #[Route('/artists/{id}', name: 'app_artists_show', methods: ['GET'])]
    public function showArtist(int $id, ArtistRepository $artistRepository, EventRepository $eventRepository): Response
    {
        $artist = $artistRepository->find($id);

        if (!$artist) {
            throw $this->createNotFoundException('Artiste non trouvé.');
        }

        $events = $eventRepository->findByArtist($id);

        return $this->render('artist/show.html.twig', [
            'artist' => $artist,
            'events' => $events
        ]);
    }

}
