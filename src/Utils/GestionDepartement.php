<?php


namespace App\Utils;


use Doctrine\ORM\EntityManagerInterface;

class GestionDepartement
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $slug
     * @return bool
     */
    public function uniqName($slug)
    {
        $exist = $this->em->getRepository('App:Departement')->findOneBy(['slug'=>$slug]);
        if ($exist) return true;
        else return false;
    }
}