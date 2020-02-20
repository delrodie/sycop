<?php


namespace App\Utils;


use App\Repository\DistrictRepository;
use Doctrine\ORM\EntityManagerInterface;

class GestionDistrict
{
    public function __construct(EntityManagerInterface $entityManager, DistrictRepository $districtRepository)
    {
        $this->em = $entityManager;
        $this->districtRepository = $districtRepository;
    }

    /**
     * @param $slug
     * @return bool
     */
    public function nameUniq($slug)
    {
        $exist = $this->em->getRepository('App:District')->findOneBy(['slug'=>$slug]);
        if ($exist) return true;
        else return false;
    }

    /**
     * @param $districtID
     * @return bool
     */
    public function addUser($districtID)
    {
        $district = $this->districtRepository->findOneBy(['id'=>$districtID]);
        $district->setUser($district->getUser()+1);
        $this->em->flush();

        return true;
    }
}