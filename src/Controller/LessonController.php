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
    public function show( Lesson $lesson, Request $request, $id, progress $progress, ProgressRepository $progressRepository, SectionRepository $sectionRepository, FormationRepository $formationRepository): Response
    {

        $section = $lesson->getSection()->getId();

        $user = $this->getUser()->getRoles()[0];



        $formation = $sectionRepository->findOneBy(['id' => $section])->getFormation();

        $progress = new Progress();
        $formProgress = $this->createForm(ProgressType::class, $progress);
        $formProgress->handleRequest($request);

        $progress->setUser($this->getUser());
        $progress->setLesson($lesson);
        $progress->setFormation($formation);
        $progress->setLessonFinished(true);
        $progress->setFormationProgress($formation->getId());


        #
        if ($progressRepository->findBy(['user' => ['id' => $this->getUser()->getId()], 'formation' => ['id' => $formation->getId()]]) == null) {
            $progress->setFormationProgress(null);
        }

        # Mettre en place la validation d'une formation

        # Récupérer l'id de la formation
        $idformation = $formation->getId();
        #  Récupère toutes les section de la formation
        $sectionFormation = $sectionRepository->findBy(['formation' => ['id' => $idformation]]);
        $lessons = [];
        # Pour chaque section, récupère les leçon
        foreach ($sectionFormation as $sections) {
            $lessons [] = $sections->getLessons()->getValues();
        }

        # Pour chaque section, récupère le nombre de leçons
        $nombrelesson = 0;
        foreach ($lessons as $value) {
            $nombrelesson += count($value);
        }

        # Récupère toute les ligne du tableau de l'user avec la formation
        $Userlessons = $progressRepository->findBy(['user' => $this->getUser(), 'formation' => ['id' => $idformation]]);



        if ($formProgress->isSubmitted() && $formProgress->isValid()) {
            # si l'utilisateur a déjà terminer la leçon alors on ne rajoute pas de nouvelle ligne en bdd
            if ($progressRepository->findOneBy(['user' => ['id' => $this->getUser()->getId()]]) !== null) {
                if ($progressRepository->findBy(['lesson' => ['id' => $lesson->getId()], 'user' => ['id' => $this->getUser()->getId()]]) !== []) {
                    return $this->redirectToRoute('liste_lesson', ['id' => $section], Response::HTTP_SEE_OTHER);
                }
            }
            $progressRepository->add($progress);

            if (count($Userlessons) + 1 == $nombrelesson ){

                //  Modifier la ligne de la première leçon correspondante a la formation, afin de faciliter le filtrage des formations en cours / fini
                $formationFinished = new Progress();
                $formProgress = $this->createForm(ProgressType::class, $formationFinished);
                $formProgress->handleRequest($request);
                $formationFinished->setUser($this->getUser());
                $formationFinished->setFormation($formation);
                $formationFinished->setFormationFinished(true);
                $progressRepository->add($formationFinished);

                $firstLesson = $progressRepository->findOneBy(['user' => ['id' => $this->getUser()->getId()],'formation' => ['id' => $idformation], 'formation_progress' => null]);
                $firstLesson->setFormationProgress($formation->getId());
                $progressRepository->add($firstLesson);

            }

            return $this->redirectToRoute('app_formation_index', ['id' => $formation], Response::HTTP_SEE_OTHER);


        }

        $lessonFinish = $progressRepository->findOneBy(['user' => $this->getUser(), 'lesson' => $lesson->getId()]);


        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
            'section' => $section,
            'user' => $user,
            'formProgress' => $formProgress->createView(),
            'lessonFinish' => $lessonFinish
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
