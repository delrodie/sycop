<?php


namespace App\Utils;


use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;

class GestionRegion
{
    public function __construct(EntityManagerInterface $entityManager, RegionRepository $regionRepository)
    {
        $this->em = $entityManager;
        $this->regionRepository = $regionRepository;
    }

    /**
     * @param $slug
     * @return bool
     */
    public function nameUniq($slug)
    {
        $exist = $this->em->getRepository("App:Region")->findOneBy(['slug'=>$slug]);
        if ($exist) return true;
        else return false;
    }

    /**
     * Incrementation du nombre de district de la region
     * Renvoie du nombre pour le code du district
     * 
     * @param $regionID
     * @return int|string|null
     */
    public function addNombreDistrict($regionID)
    {
        $region = $this->regionRepository->findOneBy(['id'=>$regionID]);
        $nombre = $region->getNombreDistrict()+1;
        if ($nombre < 10) $nombre = '0'.$nombre;
        $region->setNombreDistrict($nombre);
        $this->em->flush();

        return $nombre;
    }
}