<?php

namespace App\Controller;

use App\Entity\Gestionnaire;
use App\Form\GestionnaireType;
use App\Repository\GestionnaireRepository;
use App\Repository\RegionRepository;
use App\Utils\GestionDistrict;
use App\Utils\GestionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/gestionnaire")
 */
class GestionnaireController extends AbstractController
{
    /**
     * @Route("/", name="gestionnaire_index", methods={"GET","POST"})
     */
    public function index(Request $request, GestionnaireRepository $gestionnaireRepository, GestionUtilisateur $gestionUtilisateur, GestionDistrict $gestionDistrict): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', "Accès réservé aux administrateurs");
        $gestionnaire = new Gestionnaire();
        $form = $this->createForm(GestionnaireType::class, $gestionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Si l'USER a déjà été ajouté alors refuser l'enregistrement
            if ($gestionUtilisateur->existUser($gestionnaire->getUser())){
                $this->addFlash('danger', "ECHEC: L'utilisateur ".$gestionnaire->getUser()->getUsername()." a déjà été associé à un district!");
                return $this->redirectToRoute('gestionnaire_index');
            }

            // creation du nom a ajouter au gestionnaire
            $nom = $gestionUtilisateur->createName($gestionnaire->getDistrict());
            if (!$nom){
                $this->addFlash('danger', "Echec veuillez recommencer");
                return $this->redirectToRoute('gestionnaire_index');
            }

            // Recherche du statut de l'utilisateur
            $statut = $gestionUtilisateur->findStatut($gestionnaire->getUser());
            if (!$statut){
                $this->addFlash('danger', "Aucun statut trouvé pour cet utilisateur. Veuillez recommencer");
                return $this->redirectToRoute('gestionnaire_index');
            }

            $gestionnaire->setNom($nom);
            $gestionnaire->setStatut($statut); //dd($statut);
            $entityManager->persist($gestionnaire);
            $entityManager->flush();

            $gestionDistrict->addUser($gestionnaire->getDistrict());

            $this->addFlash('success', "Le gestionnaire ".$gestionnaire->getNom()." a été ajouté avec succès!");

            return $this->redirectToRoute('gestionnaire_index');
        }
        return $this->render('gestionnaire/index.html.twig', [
            'gestionnaires' => $gestionnaireRepository->findAll(),
            'gestionnaire' => $gestionnaire,
            'form' => $form->createView(),
            'current_menu' => 'Security'
        ]);
    }

    /**
     * @Route("/new", name="gestionnaire_new", methods={"GET","POST"})
     */
    public function new(Request $request, GestionnaireRepository $gestionnaireRepository, GestionUtilisateur $gestionUtilisateur, RegionRepository $regionRepository): Response
    {
        $region = $request->get('region');
        if ($region){
            $gestionUtilisateur->createUser($region);
            $this->addFlash('success', "Les utilisateurs des districts de la région ".$regionRepository->findOneBy(['id'=>$region])->getNom()." ont été générés avec succès!");
        }
        //
        $gestionnaire = new Gestionnaire();
        $form = $this->createForm(GestionnaireType::class, $gestionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gestionnaire);
            $entityManager->flush();

            return $this->redirectToRoute('gestionnaire_index');
        }

        return $this->render('gestionnaire/new.html.twig', [
            'gestionnaire' => $gestionnaire,
            'form' => $form->createView(),
            'gestionnaires' => $gestionnaireRepository->findAll(),
            'regions' => $regionRepository->findDiocese(),
        ]);
    }

    /**
     * @Route("/{id}", name="gestionnaire_show", methods={"GET"})
     */
    public function show(Gestionnaire $gestionnaire): Response
    {
        return $this->render('gestionnaire/show.html.twig', [
            'gestionnaire' => $gestionnaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="gestionnaire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Gestionnaire $gestionnaire, GestionnaireRepository $gestionnaireRepository, GestionUtilisateur $gestionUtilisateur): Response
    {
        $form = $this->createForm(GestionnaireType::class, $gestionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // creation du nom a ajouter au gestionnaire
            $nom = $gestionUtilisateur->createName($gestionnaire->getDistrict());

            // Recherche du statut de l'utilisateur
            $statut = $gestionUtilisateur->findStatut($gestionnaire->getUser());

            $gestionnaire->setNom($nom);
            $gestionnaire->setStatut($statut); //dd($statut);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gestionnaire_index');
        }

        return $this->render('gestionnaire/edit.html.twig', [
            'gestionnaires' => $gestionnaireRepository->findAll(),
            'gestionnaire' => $gestionnaire,
            'form' => $form->createView(),
            'current_menu' => 'Security'
        ]);
    }

    /**
     * @Route("/{id}", name="gestionnaire_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Gestionnaire $gestionnaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gestionnaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gestionnaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gestionnaire_index');
    }
}
