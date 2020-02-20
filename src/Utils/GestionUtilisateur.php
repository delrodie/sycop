<?php


namespace App\Utils;


use App\Repository\DistrictRepository;
use App\Repository\GestionnaireRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class GestionUtilisateur
{
    /**
     * GestionUtilisateur constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, DistrictRepository $districtRepository, UserRepository $userRepository, GestionnaireRepository $gestionnaireRepository)
    {
        $this->em = $entityManager;
        $this->districtRepository = $districtRepository;
        $this->userRepository = $userRepository;
        $this->gestionnaireRepository = $gestionnaireRepository;
    }

    /**
     * @param $districtID
     * @return bool|string
     */
    public function createName($districtID)
    {
        $district = $this->districtRepository->findOneBy(['id'=>$districtID]);
        if (!$district)return false;
        else{
            $nom = $district->getNom();
            return $nom;
        }

    }

    /**
     * recherche du statut du l'utilisateur
     * 1 : Utilisateur national
     * 2 : Utilisateur regional
     * 3 : utilisateur district
     *
     * @param $userID
     * @return bool|int
     */
    public function findStatut($userID)
    {
        $user = $this->userRepository->findOneBy(['id'=>$userID]);
        if (!$user) return false;
        else{
            foreach ($user->getRoles() as $role){
                $val = $role;
            }

            switch ($val){
                case "ROLE_USER":
                    $statut = 3;
                    break;
                case "ROLE_DISTRICT":
                    $statut = 3;
                    break;
                case "ROLE_REGION":
                    $statut = 2;
                    break;
                case "ROLE_NATIONAL":
                    $statut = 1;
                    break;
                default:
                    $statut = 1;
            }

            return $statut;
        }
    }

    /**
     * Verification de l'existence de l'USER dans la table gestionnaire
     *
     * @param $userID
     * @return bool
     */
    public function existUser($userID)
    {
        $exist = $this->gestionnaireRepository->findOneBy(['user'=>$userID]);
        if ($exist) return true;
        else return false;
    }
}