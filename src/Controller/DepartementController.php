<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Form\DepartementType;
use App\Repository\DepartementRepository;
use App\Utils\GestionDepartement;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/departement")
 */
class DepartementController extends AbstractController
{
    /**
     * @Route("/", name="departement_index", methods={"GET","POST"})
     */
    public function index(Request $request, DepartementRepository $departementRepository, GestionDepartement $gestionDepartement): Response
    {
        $departement = new Departement();
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slugify = new Slugify();
            $slug = $slugify->slugify($departement->getLibelle());
            if ($gestionDepartement->uniqName($slug)){
                $this->addFlash('danger', "Echec: ".$departement->getLibelle()." existe déjà! Veuillez enregistrer un autre");
                return $this->redirectToRoute('departement_index');
            }
            $departement->setSlug($slug);
            $entityManager->persist($departement);
            $entityManager->flush();

            $this->addFlash('success', $departement->getLibelle()." a été enregistré avec succès!");

            return $this->redirectToRoute('departement_index');
        }
        return $this->render('departement/index.html.twig', [
            'departements' => $departementRepository->findAll(),
            'departement' => $departement,
            'form' => $form->createView(),
            'current_menu'=>'Parametre'
        ]);
    }

    /**
     * @Route("/new", name="departement_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $departement = new Departement();
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($departement);
            $entityManager->flush();

            return $this->redirectToRoute('departement_index');
        }

        return $this->render('departement/new.html.twig', [
            'departement' => $departement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="departement_show", methods={"GET"})
     */
    public function show(Departement $departement): Response
    {
        return $this->render('departement/show.html.twig', [
            'departement' => $departement,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="departement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Departement $departement, DepartementRepository $departementRepository): Response
    {
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $slug = $slugify->slugify($departement->getLibelle());
            $departement->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $departement->getLibelle()." a été modifié avec succès!");

            return $this->redirectToRoute('departement_index');
        }

        return $this->render('departement/edit.html.twig', [
            'departements' => $departementRepository->findAll(),
            'departement' => $departement,
            'form' => $form->createView(),
            'current_menu'=>'Parametre'
        ]);
    }

    /**
     * @Route("/{id}", name="departement_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Departement $departement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$departement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($departement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('departement_index');
    }
}
