<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    #[Route('/about', name: 'about')]
    public function aboutUs() : Response
    {

        // Chemin vers le fichier JSON
        $jsonFilePath = 'data/team.json';

        // Lire le contenu du fichier JSON
        $jsonContent = file_get_contents($jsonFilePath);

        // Décodez le contenu JSON en un tableau PHP
        $data = json_decode($jsonContent, true);

        // Passez les données à Twig pour les afficher
        return $this->render('main/about_us.html.twig', [
            'data' => $data,
        ]);



        return $this->render('main/about_us.html.twig');
    }
}
