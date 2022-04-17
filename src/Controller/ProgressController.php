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

            #$formationFinis = $progressRepository->findBy(['user' => ['id' => $this->getUser()->getId()], 'formation_finished' => true]);

            return $this->render('progress/index.html.twig', [
                #'formationFinis' => $formationFinis,
                'progress' => $progressRepository->findAll(),
            ]);

        }
    }

}
