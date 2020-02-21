<?php


namespace App\Utils;


use App\Entity\Gestionnaire;
use App\Entity\User;
use App\Repository\DistrictRepository;
use App\Repository\GestionnaireRepository;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GestionUtilisateur
{
    /**
     * GestionUtilisateur constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        DistrictRepository $districtRepository,
        UserRepository $userRepository,
        GestionnaireRepository $gestionnaireRepository,
        GestionDistrict $gestionDistrict,
        RegionRepository $regionRepository)
    {
        $this->em = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->districtRepository = $districtRepository;
        $this->userRepository = $userRepository;
        $this->gestionnaireRepository = $gestionnaireRepository;
        $this->regionRepository = $regionRepository;
        $this->gestDistrict = $gestionDistrict;
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

    public function createUser($regionID)
    {
        // affectation du nom utilisateur
        $region = $this->regionRepository->findOneBy(['id'=>$regionID]);
        if ($region->getSlug() === 'grand-bassam') $name = 'bassam';
        elseif ($region->getSlug() === 'san-pedro') $name = 'sanpedro';
        else $name = $region->getSlug();

        // Liste des district
        $districts = $this->districtRepository->findByRegionWithoutUser($regionID);
        foreach ($districts as $district){
            $username = $name.''.$district->getCode();
            $mail = $username.'@scoutascci.org';
            $password = $this->generatePassword(6);
            $cle = '@scci-'.$password.'-v1.0#sycop!';

            //Creation du compte utilisateur
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($mail);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setRoles(['ROLE_DISTRICT']);
            $this->em->persist($user);
            $this->em->flush();

            // Creation du compte gestionnaire
            $gestionnaire = new Gestionnaire();
            $gestionnaire->setUser($user);
            $gestionnaire->setDistrict($district);
            $gestionnaire->setNom($district->getNom());
            $gestionnaire->setStatut(3);
            $gestionnaire->setCle($cle);
            $this->em->persist($gestionnaire);
            $this->em->flush();

            // Mise a jour du champ User de district
            $this->gestDistrict->addUser($gestionnaire->getDistrict());
        }

        return true;

    }

    /**
     * Generation du mot de passe selon le nombre de caractère souhaité
     * @param $nbChar
     * @return false|string
     */
    protected function generatePassword($nbChar)
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle($str),1,$nbChar);
    }
}