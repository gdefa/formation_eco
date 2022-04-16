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
        // creer une variable formations qui recherche dans le repertoire les dernières formations crées
        $formations = $formationRepository->findBy([], ['id' => 'desc'], 3);
        return $this->render('home/homeAccueil.html.twig', [
            'formations' => $formations
        ]);
    }
}
