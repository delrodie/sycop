<?php

namespace App\Controller;

use App\Entity\Region;
use App\Form\RegionType;
use App\Repository\RegionRepository;
use App\Utils\GestionRegion;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/region")
 */
class RegionController extends AbstractController
{
    /**
     * @Route("/", name="region_index", methods={"GET","POST"})
     */
    public function index(Request $request, RegionRepository $regionRepository, GestionRegion $gestionRegion): Response
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slugify = new Slugify();
            $slug = $slugify->slugify($region->getNom());
            if ($gestionRegion->nameUniq($slug)){
                $this->addFlash('danger', "Echec: ".$region->getNom()." existe déjà! Veuillez enregistrer une autre");
                return $this->redirectToRoute('region_index');
            }
            $region->setSlug($slug);
            $entityManager->persist($region);
            $entityManager->flush();

            $this->addFlash('success', $region->getNom()." a été enregistrée avec succès!");
            return $this->redirectToRoute('region_index');
        }

        return $this->render('region/index.html.twig', [
            'regions' => $regionRepository->findAll(),
            'region' => $region,
            'form' => $form->createView(),
            'current_menu' => 'Parametre'
        ]);
    }

    /**
     * @Route("/new", name="region_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($region);
            $entityManager->flush();

            return $this->redirectToRoute('region_index');
        }

        return $this->render('region/new.html.twig', [
            'region' => $region,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="region_show", methods={"GET"})
     */
    public function show(Region $region): Response
    {
        return $this->render('region/show.html.twig', [
            'region' => $region,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="region_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Region $region, RegionRepository $regionRepository): Response
    {
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $slug = $slugify->slugify($region->getNom());
            $region->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('region_index');
        }

        return $this->render('region/edit.html.twig', [
            'regions'=> $regionRepository->findAll(),
            'region' => $region,
            'form' => $form->createView(),
            'current_menu' => 'Parametre'
        ]);
    }

    /**
     * @Route("/{id}", name="region_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Region $region): Response
    {
        if ($this->isCsrfTokenValid('delete'.$region->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($region);
            $entityManager->flush();
        }

        return $this->redirectToRoute('region_index');
    }
}
