<?php

namespace App\Controller;

use App\Entity\District;
use App\Form\DistrictType;
use App\Repository\DistrictRepository;
use App\Repository\RegionRepository;
use App\Utils\GestionDistrict;
use App\Utils\GestionRegion;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/district")
 */
class DistrictController extends AbstractController
{
    /**
     * @Route("/", name="district_index", methods={"GET","POST"})
     */
    public function index(Request $request, DistrictRepository $districtRepository, GestionDistrict $gestionDistrict, GestionRegion $gestionRegion): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', "Il faudrait avoir un acces au moins de region");
        $district = new District();
        $form = $this->createForm(DistrictType::class, $district);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->slugify($district->getNom());
            if ($gestionDistrict->nameUniq($slug)){
                $this->addFlash('danger', "Echec: ".$district->getNom()." existe déjà! Veuillez enregistrer une autre");
                return $this->redirectToRoute('region_index');
            }
            // Recuperation du code du district
            $code = $gestionRegion->addNombreDistrict($district->getRegion());
            $pos = $code;
            if ($code === '01'){
                $structure = substr($district->getNom(),7,9);
                if ($structure === 'regionale') $code = "02";
                else $code = "01";
            }else{
                $code = "03";
            }

            $district->setSlug($slug);
            $district->setCode($code);
            $district->setPosition($pos);
            $entityManager->persist($district);
            $entityManager->flush();

            $this->addFlash('success', $district->getNom()." a été enregistré avec succès!");

            return $this->redirectToRoute('district_index');
        }

        return $this->render('district/index.html.twig', [
            'districts' => $districtRepository->findAll(),
            'district' => $district,
            'form' => $form->createView(),
            'current_menu' => 'Parametre'
        ]);
    }

    /**
     * @Route("/new", name="district_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $district = new District();
        $form = $this->createForm(DistrictType::class, $district);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($district);
            $entityManager->flush();

            return $this->redirectToRoute('district_index');
        }

        return $this->render('district/new.html.twig', [
            'district' => $district,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="district_show", methods={"GET"})
     */
    public function show(District $district): Response
    {
        return $this->render('district/show.html.twig', [
            'district' => $district,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="district_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, District $district, DistrictRepository $districtRepository): Response
    {
        $form = $this->createForm(DistrictType::class, $district);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->slugify($district->getNom());
            $district->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $district->getNom()." a été modifié avec succès!");

            return $this->redirectToRoute('district_index');
        }

        return $this->render('district/edit.html.twig', [
            'districts' => $districtRepository->findAll(),
            'district' => $district,
            'form' => $form->createView(),
            'current_menu' => 'Parametre'
        ]);
    }

    /**
     * @Route("/{id}", name="district_delete", methods={"DELETE"})
     */
    public function delete(Request $request, District $district): Response
    {
        if ($this->isCsrfTokenValid('delete'.$district->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($district);
            $entityManager->flush();
        }

        return $this->redirectToRoute('district_index');
    }
}
