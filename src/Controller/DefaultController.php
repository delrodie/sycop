<?php

namespace App\Controller;

use App\Repository\ActiviteRepository;
use App\Repository\GestionnaireRepository;
use App\Utils\GestionActivite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(ActiviteRepository $activiteRepository, GestionnaireRepository $gestionnaireRepository, GestionActivite $gestionActivite)
    {
        $annee = $gestionActivite->annee();
        // Recuperation de l'user
        $user = $this->getUser();
        // Affectation selon le role de l'utilisateur

        // Si l'utisateur est une region alors afficher template region
        if ($user->getRoles()[1] === 'ROLE_REGION') {
            //Recupération du district de l'utilisateur
            $gestionnaire = $gestionnaireRepository->findOneBy(['user'=>$user->getId()]);//dd();
            $regionID = $gestionnaire->getDistrict()->getRegion()->getId();

            return $this->render('default/index_region.html.twig', [
                'current_menu' => 'Accueil',
                'activites' => $activiteRepository->findByRegion($regionID),
                'cpteur_regional' => $activiteRepository->findNombreByStructureNiveauRegion($annee,$regionID, 2),
                'cpteur_district' => $activiteRepository->findNombreByStructureNiveauRegion($annee,$regionID,3),
                'total_activite' =>$activiteRepository->findNombreByParticipantNiveauRegiona($annee,$regionID,2),
                'activite_jeune' => $activiteRepository->findNombreByParticipantNiveauRegiona($annee,$regionID,2,'JEUNE'),
                'activite_adulte' => $activiteRepository->findNombreByParticipantNiveauRegiona($annee,$regionID,2,'ADULTE')
            ]);
        };

        // SI l'utisateur est un district alors afficher template district
        if($user->getRoles()[1] === 'ROLE_DISTRICT') {
            //Recupération du district de l'utilisateur
            $gestionnaire = $gestionnaireRepository->findOneBy(['user'=>$user->getId()]);
            $districtID = $gestionnaire->getDistrict()->getId();
            return $this->render('default/index_district.html.twig', [
                'current_menu' => 'Accueil',
                'activites' => $activiteRepository->findByDistrict($districtID),
                'total_activite' =>$activiteRepository->findNombreByParticipantNiveauDistrict($annee, $districtID),
                'activite_jeune' => $activiteRepository->findNombreByParticipantNiveauDistrict($annee, $districtID,'JEUNE'),
                'activite_adulte' => $activiteRepository->findNombreByParticipantNiveauDistrict($annee, $districtID,'ADULTE')
            ]);
        }

        return $this->render('default/index.html.twig', [
            'current_menu' => 'Accueil',
            'activites' => $activiteRepository->findGlobal($gestionActivite->annee()),
            'cpteur_national' => $activiteRepository->findNombreByStructure($gestionActivite->annee(), 1),
            'cpteur_regional' => $activiteRepository->findNombreByStructure($gestionActivite->annee(), 2),
            'cpteur_district' => $activiteRepository->findNombreByStructure($gestionActivite->annee(), 3),
            'total_activite' =>$activiteRepository->findNombreByParticipant($gestionActivite->annee()),
            'activite_jeune' => $activiteRepository->findNombreByParticipant($gestionActivite->annee(),'JEUNE'),
            'activite_adulte' => $activiteRepository->findNombreByParticipant($gestionActivite->annee(),'ADULTE')
        ]);
    }
}
