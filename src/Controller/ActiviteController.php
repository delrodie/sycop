<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use App\Utils\GestionActivite;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/activite")
 */
class ActiviteController extends AbstractController
{
    /**
     * @Route("/", name="activite_index", methods={"GET"})
     */
    public function index(ActiviteRepository $activiteRepository, GestionActivite $gestionActivite): Response
    {
        // Recuperation de l'user
        $user = $this->getUser();
        // Affectation selon le role de l'utilisateur
        if ($user->getRoles()[1] === 'ROLE_REGION') return  $this->redirectToRoute("regionale_index");
        if($user->getRoles()[1] === 'ROLE_DISTRICT') return $this->redirectToRoute("districtal_index");
        if ($user->getRoles()[1] == 'ROLE_NATIONAL') return $this->redirectToRoute('nationale_index');

        return $this->render('activite/index.html.twig', [
            'activites' => $activiteRepository->findGlobal($gestionActivite->annee()),
        ]);
    }

    /**
     * @Route("/new", name="activite_new", methods={"GET","POST"})
     */
    public function new(Request $request, GestionActivite $gestionActivite, ActiviteRepository $activiteRepository): Response
    {
        // Recuperation de l'user
        $user = $this->getUser();
        // Affectation selon le role de l'utilisateur
        if ($user->getRoles()[1] === 'ROLE_REGION') return $this->redirectToRoute('regionale_new');
        if($user->getRoles()[1] === 'ROLE_DISTRICT') return $this->redirectToRoute('districtal_new');
        if ($user->getRoles()[1] === 'ROLE_NATIONAL') return $this->redirectToRoute('nationale_new');

        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // creation du slug unique
            $slug = $gestionActivite->slug($activite);
            if ($activiteRepository->findOneBy(['slug'=>$slug])){
                $this->addFlash('danger', "Oups!! Cette activité existe déjà pour ce district. En cas d'erreur veuillez revoir le titre de l'activité.");
                return $this->redirectToRoute('activite_new');
            }
            else $activite->setSlug($slug);

            // Affectation du flag à l'activité
            $flag = $gestionActivite->Flag($activite->getDistrict()->getCode());
            $activite->setFlag($flag);

            // Recuperation de l'année en cours
            $anne = $gestionActivite->annee();

            $activite->setAnnee($anne);
            $activite->setSlug($slug);
            $activite->setCreatedAuthor($user->getUsername());
            $entityManager->persist($activite);
            $entityManager->flush();

            $this->addFlash('success', "Activité enregistrée avec succès!");

            return $this->redirectToRoute('activite_show',['slug'=>$activite->getSlug()]);
        }

        return $this->render('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="activite_show", methods={"GET"})
     */
    public function show(Activite $activite): Response
    {
        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="activite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Activite $activite, GestionActivite $gestionActivite): Response
    {
        // Recuperation de l'user
        $user = $this->getUser();
        // Affectation selon le role de l'utilisateur
        if ($user->getRoles()[1] === 'ROLE_REGION') return $this->redirectToRoute('regionale_index');
        if($user->getRoles()[1] === 'ROLE_DISTRICT') return $this->redirectToRoute('districtal_index');
        if ($user->getRoles()[1] === 'ROLE_NATIONAL') return $this->redirectToRoute('nationale_index');

        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // creation du slug unique
            $slug = $gestionActivite->slug($activite);
            $activite->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activite_index');
        }

        return $this->render('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="activite_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Activite $activite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activite_index');
    }
}
