<?php


namespace App\Controller;


use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Form\NationaleType;
use App\Repository\ActiviteRepository;
use App\Repository\GestionnaireRepository;
use App\Utils\GestionActivite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/activite-nationale")
 */
class NationaleController extends AbstractController
{
    /**
     * @Route("/", name="nationale_index", methods={"GET"})
     */
    public function index(ActiviteRepository $activiteRepository, GestionnaireRepository $gestionnaireRepository)
    {
        // Recuperation de l'user
        $user = $this->getUser(); //dd($user);
        // Affectation selon le role de l'utilisateur
        if ($user->getRoles()[1] === 'ROLE_REGION') return $this->redirectToRoute("regionale_index");
        if($user->getRoles()[1] === 'ROLE_DISTRICT') return $this->redirectToRoute("districtal_index");
        if ($user->getRoles()[1] === 'ROLE_ADMIN') return $this->redirectToRoute('activite_index');

        //Recupération du district de l'utilisateur
        $gestionnaire = $gestionnaireRepository->findOneBy(['user'=>$user->getId()]);//dd($gestionnaire);

        //Affichage des activités du district.
        $activites = $activiteRepository->findByDistrict($gestionnaire->getDistrict()->getId());

        return $this->render('activite/nationale_index.html.twig',[
            'activites' => $activites
        ]);
    }

    /**
     * @Route("/new", name="nationale_new", methods={"GET","POST"})
     */
    public function new(Request $request, GestionActivite $gestionActivite, ActiviteRepository $activiteRepository, GestionnaireRepository $gestionnaireRepository)
    {
        // Recuperation de l'user
        $user = $this->getUser();
        // Affectation selon le role de l'utilisateur
        if ($user->getRoles()[1] === 'ROLE_REGION') return $this->redirectToRoute("regionale_new");
        if($user->getRoles()[1] === 'ROLE_DISTRICT') return $this->redirectToRoute("districtal_new");
        if ($user->getRoles()[1] === 'ROLE_ADMIN') return $this->redirectToRoute('activite_new');

        //Recupération du district de l'utilisateur
        $gestionnaire = $gestionnaireRepository->findOneBy(['user'=>$user->getId()]);

        $activite = new Activite();
        $form = $this->createForm(NationaleType::class, $activite, ['district'=>$gestionnaire->getDistrict()]);
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

            return $this->redirectToRoute('nationale_show',['slug'=>$activite->getSlug()]);
        }

        return $this->render('activite/nationale_new.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="nationale_show", methods={"GET"})
     */
    public function show(Activite $activite): Response
    {
        return $this->render('activite/nationale_show.html.twig', [
            'activite' => $activite,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="nationale_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Activite $activite, GestionActivite $gestionActivite, GestionnaireRepository $gestionnaireRepository): Response
    {
        // Recuperation de l'user
        $user = $this->getUser();
        // Affectation selon le role de l'utilisateur
        if ($user->getRoles()[1] === 'ROLE_REGION') return $this->redirectToRoute('regionale_index');
        if($user->getRoles()[1] === 'ROLE_DISTRICT') return $this->redirectToRoute('districtal_index');
        if ($user->getRoles()[1] === 'ROLE_ADMIN') return $this->redirectToRoute('activite_edit',['slug'=>$activite->getSlug()]);

        //Recupération du district de l'utilisateur
        $gestionnaire = $gestionnaireRepository->findOneBy(['user'=>$user->getId()]);

        $form = $this->createForm(NationaleType::class, $activite, ['district'=>$gestionnaire->getDistrict()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { //dd($activite);

            // creation du slug unique
            $slug = $gestionActivite->slug($activite);
            $activite->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activite_index');
        }

        return $this->render('activite/nationale_edit.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }
}