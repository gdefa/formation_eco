<?php

namespace App\Controller;

use App\Entity\formation;
use App\Entity\lesson;
use App\Entity\Progress;
use App\Entity\user;
use App\Form\LessonType;
use App\Form\ProgressType;
use App\Form\RegistrationFormType;
use App\Repository\LessonRepository;
use App\Repository\ProgressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lesson')]
class LessonController extends AbstractController
{
    #[Route('/index', name: 'app_lesson_index', methods: ['GET'])]
    public function index(LessonRepository $lessonRepository): Response
    {
        return $this->render('lesson/index.html.twig', [
            'lessons' => $lessonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lesson_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LessonRepository $lessonRepository): Response
    {

        $lesson = new lesson();
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $lessonPicture */
            $lessonPicture = $form->get('picture')->getData();

            if ($lessonPicture) {
                $newFilename = uniqid() . '.' . $lessonPicture->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $lessonPicture->move(
                        $this->getParameter('kernel.project_dir') . '/public/lesson',
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Vous n\avez pas rempli le formulaire correctement.  ');
                }

                $lesson->setPicture($newFilename);
            }

            $lessonRepository->add($lesson);
            return $this->redirectToRoute('app_lesson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lesson/new.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lesson_show', methods: ['GET'])]
    public function show( request $request, lesson $lesson, progress $progress, user $user, formation $formation, progressRepository $progressRepository): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_homepage');
        }

        $section = $lesson->getSection()->getId();
        $user = $this->getUser()->getRoles()[0];

        $progress->setLessonFinished(false);

        $progress = new Progress();
        $formProgress = $this->createForm(ProgressType::class, $progress);
        $formProgress->handleRequest($request);
        $progress->setUser($this->getUser());
        $progress->setLesson($lesson);
        $progress->setFormation($formation);
        $progress->setFormationProgress($formation->getId());

        if ($form->isSubmitted() && $form->isValid()) {

            $progress->setLessonFinished(true);
            $progressRepository->add($progress);

        }

        if ($formProgress->isSubmitted() && $formProgress->isValid()) {
            # si l'utilisateur a déjà terminer la leçon alors on ne rajoute pas de nouvelle ligne en bdd
            if ($progressRepository->findOneBy(['user' => ['id' => $this->getUser()->getId()]]) !== null) {
                if ($progressRepository->findBy(['lesson' => ['id' => $lesson->getId()], 'user' => ['id' => $this->getUser()->getId()]]) !== []) {
                    return $this->redirectToRoute('app_section_show', ['id' => $section], Response::HTTP_SEE_OTHER);
                }
            }
            $progressRepository->add($progress);
            return $this->redirectToRoute('liste_lesson', ['id' => $section], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
            'section' =>$section,
            'progress' =>$progress,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lesson_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, lesson $lesson, LessonRepository $lessonRepository): Response
    {

        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lessonRepository->add($lesson);
            return $this->redirectToRoute('app_lesson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lesson/edit.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lesson_delete', methods: ['POST'])]
    public function delete(Request $request, lesson $lesson, LessonRepository $lessonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lesson->getId(), $request->request->get('_token'))) {
            $lessonRepository->remove($lesson);
        }

        return $this->redirectToRoute('app_lesson_index', [], Response::HTTP_SEE_OTHER);
    }
}
