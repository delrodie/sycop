<?php


namespace App\Utils;


use Doctrine\ORM\EntityManagerInterface;

class GestionDistrict
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function nameUniq($slug)
    {
        $exist = $this->em->getRepository('App:District')->findOneBy(['slug'=>$slug]);
        if ($exist) return true;
        else return false;
    }
}