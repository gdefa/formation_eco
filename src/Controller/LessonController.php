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
    public function index(LessonRepository $LessonRepository): Response
    {
        return $this->render('lesson/index.html.twig', [
            'lessons' => $lessonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_lesson_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LessonRepository $lessonRepository): Response
    {
        if( $this->getUser()->getIsAccepted() == false || $this->getUser()->getRoles() == ['ROLE_APPRENANT'] ){
            return $this->redirectToRoute('app_homepage');
        }

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
    public function show( lesson $lesson, Request $request, LessonRepository $lessonRepository, SectionRepository $sectionRepository, formationRepository $formationRepository): Response
    {

        $section = $lesson->getSection()->getId();
        $formation = $sectionRepository->findOneBy(['id' => $section])->getFormation();

       # $Progress = new Progress();
        # $formProgress = $this->createForm(ProgressType::class, $Progress);
        #$formProgress->handleRequest($request);

        #if ($formProgress->isSubmitted() && $formProgress->isValid()) {
          #  $Progress->setUser($this->getUser());
           # $Progress->setLesson($lesson);
            #$Progress->setFormation($formation);
            #$Progress->setLessonFinished(true);
            #$Progress->setProgressFormation($formation->getId());
        #}



        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
            'section' => $section,
            #'user' => $user,
            #'Progress' => $Progress,
            #'form' => $formProgress->createView(),
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
