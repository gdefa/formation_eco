<?php

namespace App\Controller;

use App\Entity\Progress;
use App\Form\ProgressType;
use App\Repository\ProgressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/progress')]
class ProgressController extends AbstractController
{
    #[Route('/index', name: 'app_progress_index', methods: ['GET', 'POST'])]
    public function index(ProgressRepository $progressRepository): Response
    {

        if ($this->getUser() !== null) {

            $formationTerminee = $progressRepository->findBy(['user' => ['id' => $this->getUser()->getId()], 'formation_finished' => true]);

            return $this->render('progress/index.html.twig', [
                'formations' => $formationRepository->findAll(),
                'formationTerminees' => $formationTerminee,
                'progress' => $progressRepository->findAll(),
            ]);

        }
    }

    #[Route('/new/progression', name: 'app_progress_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProgressRepository $progressRepository, progress $progress): Response
    {
        $progress = new Progress();

        $form = $this->createForm(ProgressType::class, $progress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $progressRepository->add($progress);
            return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('progress/new.html.twig', [
            'progress' => $progress,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/show', name: 'app_progress_show', methods: ['GET', 'POST'])]
    public function show(string $slug, Progress $progress): Response
    {
        return $this->render('progress/show.html.twig', [
            'progress' => $progress,
        ]);
    }

    #[Route('/{slug}/edit/progression', name: 'app_progress_edit', methods: ['GET', 'POST'])]
    public function edit(string $slug, Request $request, Progress $progress, ProgressRepository $progressRepository): Response
    {
        $form = $this->createForm(ProgressType::class, $progress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $progressRepository->add($progress);
            return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('progress/edit.html.twig', [
            'progress' => $progress,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/delete', name: 'app_progress_delete', methods: ['POST'])]
    public function delete(string $slug, Request $request, Progress $progress, ProgressRepository $progressRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$progress->getId(), $request->request->get('_token'))) {
            $progressRepository->remove($progress);
        }

        return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
    }
}
