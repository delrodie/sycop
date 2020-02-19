<?php


namespace App\Utils;


use Doctrine\ORM\EntityManagerInterface;

class GestionRegion
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
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
}