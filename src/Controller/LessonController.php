<?php

namespace App\Controller;

use App\Entity\lesson;
use App\Entity\Progress;
use App\Form\LessonType;
use App\Form\ProgressType;
use App\Repository\formationRepository;
use App\Repository\LessonRepository;
use App\Repository\ProgressRepository;
use App\Repository\SectionRepository;
use JetBrains\PhpStorm\NoReturn;
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

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    #[Route('/{id}', name: 'app_lesson_show', methods: ['GET'])]
    public function show(Lesson $lesson, Request $request, SectionRepository $sectionRepository, FormationRepository $formationRepository, progress $progress, progressrepository $progressRepository): Response
    {

        $section = $lesson->getSection()->getId();

        $formation = $sectionRepository->findOneBy(['id' => $section])->getFormation();


        $progressFinish = new Progress();
        $form = $this->createForm(ProgressType::class, $progressFinish);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

        $progressFinish->setLesson($lesson);
        $progressFinish->setFormation($formation);
        $progressFinish->setUser($this->getUser());
        $progressFinish->setLessonFinished(true);
        $progressFinish->setFormationFinished($formation->getId());
        $progressRepository->add($progressFinish);
        }


        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
            'section' => $section,
            'user' => $user,
            'form' => $form->createView(),
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
