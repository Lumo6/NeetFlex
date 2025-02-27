<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class UserController extends AbstractController
{
    #[Route('/users', name: 'app_users_list')]
    public function listUsers(UserRepository $userRepository, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
}
