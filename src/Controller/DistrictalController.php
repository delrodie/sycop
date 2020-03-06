<?php


namespace App\Controller;


use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Form\DistrictalType;
use App\Form\NationaleType;
use App\Repository\ActiviteRepository;
use App\Repository\GestionnaireRepository;
use App\Utils\GestionActivite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/activite-de-district")
 */
class DistrictalController extends AbstractController
{
    /**
     * @Route("/", name="districtal_index", methods={"GET"})
     */
    public function index(ActiviteRepository $activiteRepository, GestionnaireRepository $gestionnaireRepository)
    {
        // Recuperation de l'user
        $user = $this->getUser(); //dd($user);
        // Affectation selon le role de l'utilisateur
        if ($user->getRoles()[1] === 'ROLE_REGION') return $this->redirectToRoute("regionale_index");
        if($user->getRoles()[1] === 'ROLE_NATIONAL') return $this->redirectToRoute("nationale_index");
        if ($user->getRoles()[1] === 'ROLE_ADMIN') return $this->redirectToRoute('activite_index');

        //Recupération du district de l'utilisateur
        $gestionnaire = $gestionnaireRepository->findOneBy(['user'=>$user->getId()]);//dd($gestionnaire);

        //Affichage des activités du district.
        $activites = $activiteRepository->findByDistrict($gestionnaire->getDistrict()->getId());

        return $this->render('activite/district_index.html.twig',[
            'activites' => $activites
        ]);
    }

    /**
     * @Route("/new", name="districtal_new", methods={"GET","POST"})
     */
    public function new(Request $request, GestionActivite $gestionActivite, ActiviteRepository $activiteRepository, GestionnaireRepository $gestionnaireRepository)
    {
        // Recuperation de l'user
        $user = $this->getUser();
        // Affectation selon le role de l'utilisateur
        if ($user->getRoles()[1] === 'ROLE_REGION') return $this->redirectToRoute("regionale_new");
        if($user->getRoles()[1] === 'ROLE_NATIONAL') return $this->redirectToRoute("nationale_new");
        if ($user->getRoles()[1] === 'ROLE_ADMIN') return $this->redirectToRoute('activite_new');

        //Recupération du district de l'utilisateur
        $gestionnaire = $gestionnaireRepository->findOneBy(['user'=>$user->getId()]);

        $activite = new Activite();
        $form = $this->createForm(DistrictalType::class, $activite, ['district'=>$gestionnaire->getDistrict()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // creation du slug unique
            $slug = $gestionActivite->slug($activite);
            if ($activiteRepository->findOneBy(['slug'=>$slug])){
                $this->addFlash('danger', "Oups!! Cette activité existe déjà pour ce district. En cas d'erreur veuillez revoir le titre de l'activité.");
                return $this->redirectToRoute('districtal_new');
            }
            else $activite->setSlug($slug); //dd($activite->getDistrict()->getCode());

            // Affectation du flag à l'activité
            $flag = $gestionActivite->Flag('03');
            $activite->setFlag($flag);

            // Recuperation de l'année en cours
            $anne = $gestionActivite->annee();

            $activite->setAnnee($anne);
            $activite->setSlug($slug);
            $activite->setCreatedAuthor($user->getUsername());
            $entityManager->persist($activite);
            $entityManager->flush();

            $this->addFlash('success', "Activité enregistrée avec succès!");

            return $this->redirectToRoute('districtal_show',['slug'=>$activite->getSlug()]);
        }

        return $this->render('activite/district_new.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="districtal_show", methods={"GET"})
     */
    public function show(Activite $activite): Response
    {
        return $this->render('activite/district_show.html.twig', [
            'activite' => $activite,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="districtal_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Activite $activite, GestionActivite $gestionActivite, GestionnaireRepository $gestionnaireRepository): Response
    {
        // Recuperation de l'user
        $user = $this->getUser();
        // Affectation selon le role de l'utilisateur
        if ($user->getRoles()[1] === 'ROLE_REGION') return $this->redirectToRoute("regionale_edit",['slug'=>$activite->getSlug()]);
        if($user->getRoles()[1] === 'ROLE_NATIONAL') return $this->redirectToRoute("nationale_index");
        if ($user->getRoles()[1] === 'ROLE_ADMIN') return $this->redirectToRoute('activite_edit',['slug'=>$activite->getSlug()]);

        //Recupération du district de l'utilisateur
        $gestionnaire = $gestionnaireRepository->findOneBy(['user'=>$user->getId()]);

        $form = $this->createForm(DistrictalType::class, $activite, ['district'=>$gestionnaire->getDistrict()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { //dd($activite);

            // creation du slug unique
            $slug = $gestionActivite->slug($activite);
            $activite->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('districtal_show',['slug'=>$activite->getSlug()]);
        }

        return $this->render('activite/district_edit.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }
}