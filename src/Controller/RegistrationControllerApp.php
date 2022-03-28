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
        $user = new User();
        $form = $this->createForm(RegistrationFormTypeApp::class, $user);
        $form->handleRequest($request);
        //$user->setIsAccepted(true);


        if ($user->getEmail('defa7@live.fr')) {
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPseudo('admin');
        }
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
            // do anything else you need here, like send an email

            $this->addFlash('inscription-apprenant', 'Félicitations, tu viens de t\'inscrire en tant qu\'apprenant. Connecte-toi !  ');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/registerApp.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}