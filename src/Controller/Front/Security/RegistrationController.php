<?php

declare(strict_types=1);

namespace App\Controller\Front\Security;

use App\Entity\User;
use App\Factory\UserFactoryInterface;
use App\Form\Front\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'front_registration', methods: ['GET', 'POST'])]
    public function __invoke(MailerInterface $mailer, Request $request, UserFactoryInterface $userFactory): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$picture = $pictureFactory->upload($form->getData());
            $user = $userFactory->create($form->all());
            //$userFactory->linkPicture($user, $picture);

            // envoi l'email
            $email = (new Email())
                ->from('noreply@qratos.fr')
                ->to($user->getEmail())
                ->subject('Inscription')
                ->text('Bienvenue sur le site qratos.fr')
            ;

            $mailer->send($email);

            $this->addFlash('success', 'inscription effectuée avec succes');

            return $this->redirectToRoute('front_login');
        }

        return $this->render('front/views/security/registration.html.twig', compact('form'));
    }
}
