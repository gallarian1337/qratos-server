<?php

declare(strict_types=1);

namespace App\Controller\Front\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/profil', name: 'front_profile_index', methods: ['GET'])]
    public function __invoke(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('front_login');
        }

        return $this->render('front/views/profile/index.html.twig');
    }
}
