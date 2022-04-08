<?php


namespace App\Controller;

use App\Entity\user;
use App\Form\RegistrationFormTypeApp;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationControllerApp extends AbstractController
{
    #[Route('/register/apprenant', name: 'app_register_apprenant')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new user();
        $form = $this->createForm(RegistrationFormTypeApp::class, $user);
        $form->handleRequest($request);
        $user->setRoles(['ROLE_APPRENANT']);


        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash('inscription-apprenant', 'Inscription réussie, direction le login !  ');

            return $this->redirectToRoute('login');
        }

        return $this->render('registration/registerApp.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}