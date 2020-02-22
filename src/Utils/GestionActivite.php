<?php


namespace App\Utils;


use Doctrine\ORM\EntityManagerInterface;

class GestionActivite
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
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