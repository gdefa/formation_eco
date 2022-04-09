<?php

namespace App\Controller;

use App\Entity\quizz;
use App\Form\QuizzType;
use App\Form\QuizzAppType;
use App\Repository\QuizzRepository;
use App\Repository\SectionRepository;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quizz')]
class QuizzController extends AbstractController
{
    #[Route('/', name: 'app_quizz_index', methods: ['GET'])]
    public function index(QuizzRepository $quizzRepository, $id, $app): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_homepage');
        }

        $quizInstructeur = $quizzRepository->findOneBy(['section' => ['id' => $id], 'user' => null]);
        $quizApprenant = $quizzRepository->findOneBy(['section' => ['id' => $id], 'user' => ['id' => $app]]);

        $section = $quizInstructeur->getSection()->getId();

        return $this->render('quizz/index.html.twig', [
            'quizInstructeur' => $quizInstructeur,
            'quizApprenant' => $quizApprenant,
            'section' => $section
        ]);
    }

    #[Route('/new', name: 'app_quizz_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuizzRepository $quizzRepository, EntityManagerInterface $entityManager, SectionRepository $sectionRepository): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_homepage');
        }

        $quizz = new quizz();
        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quizz);
            $entityManager->flush();
            $this->addFlash('quizz-valid', 'Vous venez de créer un Quiz pour la section.');
            return $this->redirectToRoute('app_lesson_index');
        }

        return $this->renderForm('quizz/new.html.twig', [
            'quizz' => $quizz,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quizz_show', methods: ['GET'])]
    public function ResponseApprenant(Request $request, EntityManagerInterface $entityManager, $id, QuizzRepository $quizzRepository, SectionRepository $sectionRepository): Response
    {
        if ($quizzRepository->findBy(['section' => ['id' => $id]]) == []) {
            $this->addFlash('questionnaire-null', 'Il n\'y a pas de quiz pour le moment, mais revient vite, l\'instructeur est sûrement en train de l\'écrire.');
            return $this->redirectToRoute('app_section_show', ['id' => $id]);
        }

        if ($quizzRepository->findBy(['section' => ['id' => $id], 'user' => ['id' => $this->getUser()->getId()]]) !== []) {
            return $this->redirectToRoute('app_quiz_index', ['id' => $id, 'app' => $this->getUser()->getId()]);
        }


        $quizz = $quizzRepository->findOneBy(['section' => ['id' => $id]]);

        $sectionid = $quizz->getSection()->getId();

        $sectionTitle = $sectionRepository->findOneBy(['id' => $sectionid])->getTitle();


        $question1 = $quizz->getQuestion1();
        $question2 = $quizz->getQuestion2();
        $question3 = $quizz->getQuestion3();


        $responseQuiz = new Quizz();
        $form = $this->createForm(QuizApprenantType::class, $responseQuiz);
        $form->handleRequest($request);
        $responseQuiz->setSection($quizz->getSection());
        $responseQuiz->setUser($this->getUser());
        $responseQuiz->setQuestion1($question);
        $responseQuiz->setQuestion2($question2);
        $responseQuiz->setQuestion3($question3);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($responseQuiz);
            $entityManager->flush();

            return $this->redirectToRoute('app_quiz_index', ['id' => $id, 'app' => $this->getUser()->getId()]);
        }

        return $this->renderForm('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
            'q1' => $question1,
            'q2' => $question2,
            'q3' => $question3,
            'sectionTitle' => $sectionTitle,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_quizz_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, quizz $quizz, QuizzRepository $quizzRepository): Response
    {

        if ($this->getUser() !== ['ROLE_INSTRUCTEUR']) {
            return $this->redirectToRoute('app_homepage');
        }
        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quizzRepository->add($quizz);
            return $this->redirectToRoute('app_quizz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quizz/edit.html.twig', [
            'quizz' => $quizz,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quizz_delete', methods: ['POST'])]
    public function delete(Request $request, quizz $quizz, QuizzRepository $quizzRepository): Response
    {
        if ($this->getUser() !== ['ROLE_INSTRUCTEUR']) {
            return $this->redirectToRoute('app_homepage');
        }

        if ($this->isCsrfTokenValid('delete'.$quizz->getId(), $request->request->get('_token'))) {
            $quizzRepository->remove($quizz);
        }

        return $this->redirectToRoute('app_quizz_index', [], Response::HTTP_SEE_OTHER);
    }
}
