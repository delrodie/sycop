<?php

namespace App\Controller;

use App\Repository\ActiviteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(ActiviteRepository $activiteRepository)
    {
        return $this->render('default/index.html.twig', [
            'current_menu' => 'Accueil',
            'activites' => $activiteRepository->findGlobal(),
            'cpteur_national' => $activiteRepository->findNombreByStructure(1),
            'cpteur_regional' => $activiteRepository->findNombreByStructure(2),
            'cpteur_district' => $activiteRepository->findNombreByStructure(3),
            'total_activite' =>$activiteRepository->findNombreByParticipant(),
            'activite_jeune' => $activiteRepository->findNombreByParticipant('JEUNE'),
            'activite_adulte' => $activiteRepository->findNombreByParticipant('ADULTE')
        ]);
    }
}
