<?php


namespace App\Utils;


use App\Repository\DistrictRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;

class GestionActivite
{
    private $em;
    private $districtRepository;

    public function __construct(EntityManagerInterface $entityManager, DistrictRepository $districtRepository)
    {
        $this->em = $entityManager;
        $this->districtRepository = $districtRepository;
    }

    /**
     * Recuperation du flag de l'activite
     * flag = 1 : Activité nationale
     * flag = 2 : activité de l'equipe regionale
     * flag = 3 : activité de district
     *
     * @param $districtID
     * @return int
     */
    public function Flag($districtCode)
    {
        switch ($districtCode){
            case '01':
                $flag = 1;
                break;
            case '02':
                $flag = 2;
                break;
            case '03':
                $flag = 3;
                break;
        }
        return $flag;
    }

    /**
     * Determination du slug de l'activité
     * @param $activite
     * @return string
     */
    public function slug($activite) : string
    {
        $slugify = new Slugify();
        $titre = $slugify->slugify($activite->getTitre());
        $district = $activite->getDistrict()->getSlug();
        $annee = $this->annee();
        $slug = $annee.'-'.$district.'-'.$titre;

        return $slug;
    }

    /**
     * Annee scoute encours
     *
     * @return string
     */
    public function annee()
    {
        $mois_encours = Date('m', time());
        if ($mois_encours > 9){
            $debut_annee = Date('Y', time());
            $fin_annee = Date('Y', time())+1;
            //$an = Date('y', time())+1;
        }else{
            $debut_annee = Date('Y', time())-1;
            $fin_annee = Date('Y', time());
            //$an = Date('y', time());
        }

        $annee = $debut_annee.'-'.$fin_annee;

        return $annee;
    }
}