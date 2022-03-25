<?php

namespace App\Controller;

use App\Entity\formation;
use App\Form\FormationType;
use App\Repository\formationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/formation')]
class FormationController extends AbstractController
{
    #[Route('/', name: 'app_formation_index', methods: ['GET'])]
    public function index(formationRepository $formationRepository): Response
    {
        return $this->render('formation/index.html.twig', [
            'formations' => $formationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, formationRepository $formationRepository): Response
    {
        $formation = new formation();
        /* La personne qui creer une formation est la personne connecte */
        $formation->setUser($this->getUser());
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

        return $this->renderForm('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

        #[Route('/{id}', name: 'app_formation_show', methods: ['GET'])]
    public function show(formation $formation): Response
    {
        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, formation $formation, formationRepository $formationRepository): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
                $formationRepository->add($formation);
                return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('formation/edit.html.twig', [
                'formation' => $formation,
                'form' => $form,
            ]);
        }
    }

    #[Route('/{id}', name: 'app_formation_delete', methods: ['POST'])]
    public function delete(Request $request, formation $formation, formationRepository $formationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $formationRepository->remove($formation);
        }

        return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
    }
}
