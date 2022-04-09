<?php

namespace App\Controller;

use App\Entity\formation;
use App\Form\FormationType;
use App\Repository\formationRepository;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/formation')]
class FormationController extends AbstractController
{
    private EntityManagerInterface $entityManager;


    #[Route('/index', name: 'app_formation_index', methods: ['GET'])]
    public function index(formationRepository $formationRepository): Response
    {
        return $this->render('formation/index.html.twig', [
            'formations' => $formationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_formation_new', methods: ['GET'])]
    public function new(Request $request, formationRepository $formationRepository): Response
    {
        if($this->getUser() == null || $this->getUser()->getRoles() !== ['ROLE_INSTRUCTEUR']){
            return $this->redirectToRoute('app_homepage');
        }

        $formation = new formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $formationPicture */
            $formationPicture = $form->get('picture')->getData();

            if ($formationPicture) {
                $newFilename = uniqid().'.'.$formationPicture->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $formationPicture->move(
                        $this->getParameter('kernel.project_dir') . '/public/Formation',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error' , 'Vous n\avez pas rempli le formulaire correctement.  ');
                }
                $formation->setPicture($newFilename);
            }
            $formationRepository->add($formation);

            return $this->redirectToRoute('app_section_new', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_formation_show', methods: ['GET','POST'])]
    public function show(Formation $formation , SectionRepository $SectionRepository , $id, ): Response
    {
        if ($this->getUser() == null){
            $this->addFlash('user_obligation', 'Vous devez vous créer un compte pour pouvoir accéder aux formations.');
            return $this->redirectToRoute('app_register_apprenant');
        }

        $sectionFormation = $SectionRepository->findBy(['formation' => ['id'=> $id]]);

        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
            'sectionsFormation' => $sectionFormation,
        ]);
    }



     #[Route('/{id}/edit', name:'app_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formation $formation, FormationRepository $formationRepository, $id): Response
    {
        if($this->getUser() == null){
            return $this->redirectToRoute('app_homepage');
        }

        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $formationPicture */
            $formationPicture = $form->get('picture')->getData();

            if ($formationPicture) {
                $newFilename = uniqid() . '.' . $formationPicture->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $formationPicture->move(
                        $this->getParameter('kernel.project_dir') . '/public/formation',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Vous n\avez pas rempli le formulaire correctement.  ');
                }


                $formation->setPicture($newFilename);
            }
            $formationRepository->add($formation);


            return $this->redirectToRoute('app_section_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formation_delete', methods: ['POST'])]
    public function delete(Request $request, formation $formation, formationRepository $formationRepository): Response
    {
        if( $this->getUser() == null || $this->getUser()->getRoles() !== ['ROLE_INSTRUCTEUR']){
            return $this->redirectToRoute('app_homepage');
        }
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $formationRepository->remove($formation);
        }

        return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
    }
}
