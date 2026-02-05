<?php

declare(strict_types=1);

namespace App\Controller\Front\Panel\Quiz;

use App\Entity\Quiz\Question;
use App\Form\Front\Panel\Question\CreateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateController extends AbstractController
{
    #[Route('/panneau-de-bord/questions/creation', name: 'front_panel_question_create', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $question = new Question();
        $form = $this->createForm(CreateType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }

        return $this->render('front/views/panel/quiz/create.html.twig', compact('form'));
    }
}
