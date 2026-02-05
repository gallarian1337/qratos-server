<?php

declare(strict_types=1);

namespace App\Controller\Front\Panel\Quiz;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/panneau-de-bord/questions', name: 'front_panel_question_index', methods: ['GET'])]
    public function __invoke(QuestionRepository $questionRepository): Response
    {
        return $this->render('front/views/panel/quiz/index.html.twig', ['questions' => $questionRepository->findAll()]);
    }
}
