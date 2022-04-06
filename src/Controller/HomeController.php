<?php

namespace App\Controller;

use App\Repository\formationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findBy([], ['id' => 'desc'], 3);
        return $this->render('home/card.html.twig', [
            'controller_name' => 'HomeController',
            'formations' => $formations
        ]);
    }
}
