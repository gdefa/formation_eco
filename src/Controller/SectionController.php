<?php

namespace App\Controller;

use App\Entity\section;
use App\Entity\lesson;
use App\Form\SectionType;
use App\Repository\LessonRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/section')]
class SectionController extends AbstractController
{


     #[Route('/{id}/liste/lesson', name:'liste_lesson', methods: ['GET'])]
    public function listeLesson($id , SectionRepository $sectionRepository , LessonRepository $lessonRepository): Response
    {
        if( $this->getUser() == null){
            return $this->redirectToRoute('app');
        }


        $sectionEncour = $sectionRepository->findBy(['id' => $id])[0];
        $formationsectionId = $sectionEncour->getFormation()->getId();
        $lesson = $lessonRepository->findBy(['section' => ['id' => $sectionEncour->getId()]]);


        return $this->render('section/show.html.twig', [
            'sectionEncour' => $sectionEncour,
            'lesson' => $lesson,
            'formation' => $formationsectionId
        ]);
    }


    #[Route('/', name: 'app_section_index', methods: ['GET'])]
    public function index(SectionRepository $sectionRepository): Response
    {

        return $this->render('section/index.html.twig', [
            'sections' => $sectionRepository->findAll(),

        ]);
    }

    #[Route('/new', name: 'app_section_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SectionRepository $sectionRepository): Response
    {
        #Création des permissions d'accès
        if( $this->getUser()->getIsAccepted() == false || $this->getUser()->getRoles() == ['ROLE_APPRENANT'] ){
            return $this->redirectToRoute('app_homepage');
        }

        $section = new section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sectionRepository->add($section);
            return $this->redirectToRoute('app_section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('section/new.html.twig', [
            'section' => $section,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_section_show', methods: ['GET'])]
    public function show( section $section, lessonRepository $lessonRepository, $id): Response
    {

        #Recherche les lessons d'une section
        $lessonSection = $lessonRepository->findBy(['section' => ['id'=> $id]]);

        return $this->render('section/show.html.twig', [
            'section' => $section,
            'lessonsSection' => $lessonSection,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_section_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, section $section, SectionRepository $sectionRepository): Response
    {
        if( $this->getUser() == null || $this->getUser()->getRoles() !== ['ROLE_INSTRUCTEUR']){
            return $this->redirectToRoute('app_homepage');
        }
        
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sectionRepository->add($section);
            return $this->redirectToRoute('app_section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('section/edit.html.twig', [
            'section' => $section,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_section_delete', methods: ['POST'])]
    public function delete(Request $request, section $section, SectionRepository $sectionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $sectionRepository->remove($section);
        }

        return $this->redirectToRoute('app_section_index', [], Response::HTTP_SEE_OTHER);
    }
}
