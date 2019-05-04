<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Partenaire;
use AppBundle\Entity\Client;
use AppBundle\Entity\Etablissement;
use AppBundle\Entity\Services;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\imageEtablissement;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class DefaultController extends Controller
{
    /**
    * @Route("/", name="homepage")
    */
    public function indexAction () {
      return $this->render('home.html.twig');
    }

/**
  @Route("/login",name="login")
  *
  */
    public function connexion(Request $request){
      $client = new Client();
      $partenaire = new Partenaire();
      $noUser = 0;

      $formClient = $this -> createFormBuilder($client)
      ->add('email')
      ->add('motDePasse', PasswordType::class)
      ->add('save', SubmitType::class, array('label' => 'Se connecter'))
      ->getForm();

      $formPartenaire = $this -> createFormBuilder($partenaire)
      ->add('email')
      ->add('password', PasswordType::class)
      ->add('save', SubmitType::class, array('label' => 'Sidentifier'))
      ->getForm();

      $formClient->handleRequest($request);
      //si le formClient est soumité
      if ( $formClient->isSubmitted() && $formClient->isValid() ) {
          $email = $formClient->get('email')->getData();
          $mdp = $formClient->get('motDePasse')->getData();
          
          $repository=$this->getDoctrine()->getRepository('AppBundle:Client');
          $utilisateur = $repository->findBy(
              array('email' => $email, 'motDePasse' => $mdp));
          if ( $utilisateur ) {
            $session = $request ->getSession();
            $session->set('email', $email);
            $session->set('id', $utilisateur[0]->getId());
            $session->set('role', 'ROLE_CLIENT');
            return $this-> redirectToRoute('acceuil');
          }else
          {
            $noUser = 1;
          }

      }
      $formPartenaire->handleRequest($request);
      if ( $formPartenaire->isSubmitted() && $formPartenaire->isValid() ) {
          $email = $formPartenaire->get('email')->getData();
          $mdp = $formPartenaire->get('password')->getData();

          $repository=$this->getDoctrine()->getRepository('AppBundle:Partenaire');
          $partenaire = $repository->findBy(
              array('email' => $email, 'password' => $mdp));
          if ( $partenaire ) {
            $session = $request ->getSession();
            $session->set('email', $email);
            $session->set('id', $partenaire[0]->getId());
            $session->set('role', 'ROLE_PARTENAIRE');
            return $this-> redirectToRoute('profilePartenaire');
          }else
          {
            $noUser = 1;
          }
      }

      //Si le formPartenaire est validé

      return $this->render('login.html.twig', [
        'formClient' => $formClient->createView(),
        'formPartenaire' => $formPartenaire->createView(),
        'noUser' => $noUser
      ]);    

      }

     
    /**
     * @Route("/inscription", name="inscrire")
     */
    public function createAction (Request $request) {
      // Créer un sondage via un formulaire

      $poll = new Client();

      $form = $this->createFormBuilder($poll)
	  ->add('Email',EmailType::class)
	  ->add('Nom_de_famille',TextType::class)
    ->add('Prenom',TextType::class)
    ->add('mot_de_passe',PasswordType::class)
    ->add('Code_postal',NumberType::class)
    ->add('Sexe',ChoiceType::class,
        array ('choices' => array ('Homme' => 1, 'Femme' => 0),
         'expanded' => true))
            ->add('save', SubmitType::class, array('label' => 'Rejoignez nous'))
            ->getForm();

      // tester si le formulaire est déjà rempli
      $form->handleRequest($request);
      
      if ($form->isSubmitted() && $form->isValid()) {
          $email=$form->get('Email')->getData();
          $cpt=0;
          $repository=$this->getDoctrine()->getRepository('AppBundle:Client');
          $list_email=$repository->findAll();
          for ($i=0; $i < count($list_email); $i++) { 
            if($list_email[$i]->getEmail()==$email){
              $cpt++;
            }
          }
          if($cpt==0){
            $em = $this->getDoctrine()->getManager();
            $em->persist($poll); // prépare l'insertion dans la BD
            $em->flush(); // insère dans la BD
            return $this->render('created.html.twig');
          }
          else{
            return $this->render('erreur_inscription.html.twig',array('form'=>$form->createView()));
          }
      }

          // afficher le formulaire s'il n'est pas déjà rempli
          return $this->render('create.html.twig', array('form' => $form->createView()));
    }        
    /**
    *@Route("/devenir-partenaire", name="devenirPartenaire")
    */
    
    public function devenirPartenaire(Request $request)
    {
        $dejaExiste = 0;/*designer si un utilisateur existe déja
                        * = 0 veut dire il n'existe pas !
                        */
        // informations personnelles partenaires
        $partenaire = new Partenaire();
        $form = $this -> createFormBuilder($partenaire)
        ->add('nom')
        ->add('prenom')
        ->add('email', EmailType::class)
        ->add('telephone', NumberType::class)
        ->getForm();

        $repository = $this->getDoctrine()->getRepository(Partenaire::class);

        //traiter les infos qui sont envoyées par les forms
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            $existePartenaire = $repository->findOneByEmail($partenaire->getEmail());
            //s'il n'existe pas déja dans la base de données
            if (!$existePartenaire) {
                $partenaire->setPassword('default');
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($partenaire);
                $manager->flush();

                //on passe à l'étape suivante
                return $this->redirectToRoute('partenaireEtablissement',[
                    'idProprietaire' => $partenaire->getId()
                ]);
                
            }else{
                $dejaExiste = 1;
            }    
        }
        return $this->render('partenaire1.html.twig', [
            'partenaire' => $form->createView(),
            'dejaExiste' => $dejaExiste
        ]);
    }
    /**
    *@Route("/acceuil", name="acceuil")
    */
    public function acceuil(Request $request){
      $session = $request ->getSession();

      if ( $session->get('role') == "ROLE_CLIENT" ) {
      $etablissement = new Etablissement();
      $formEtablissement1 = $this -> createFormBuilder($etablissement)
      ->add('codePostale',NumberType::class)
      ->add('save', SubmitType::class, array('label' => 'Recherche'))
      ->getForm();

      $formEtablissement2 = $this -> createFormBuilder($etablissement)
      ->add('nomSociete',TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Recherche'))
            ->getForm();


   // tester si le formulaire est déjà rempli
      $formEtablissement1->handleRequest($request);
      $formEtablissement2->handleRequest($request);
      $choix = 0;

      if ( $formEtablissement1->isSubmitted() && $formEtablissement1->isValid() ) {

          $activite = $request->request->get('select');//recuperer le type de l'activité
        
          $code = $formEtablissement1->get('codePostale')->getData();

           $repository=$this->getDoctrine()->getRepository('AppBundle:Etablissement');
          $list_activite=$repository->findBy(
              array('typeActivite' => $activite, 'codePostale' => $code));
          return $this->render('acceuil.html.twig',array('form' => $formEtablissement1->createView(),
            'resultat' => $list_activite,
            'form2'=>$formEtablissement2->createView()
          ));
      }
      elseif($formEtablissement2 -> isSubmitted() && $formEtablissement2 -> isValid()){
        $societe = $formEtablissement2 -> get('nomSociete') -> getData();
        $repository = $this->getDoctrine()->getRepository('AppBundle:Etablissement');
        $list_societe = $repository->findByNomSociete($societe);
        return $this->render('acceuil.html.twig',array('form' => $formEtablissement1->createView(),
            'resultat' => $list_societe,
            'form2'=>$formEtablissement2->createView()
          ));
      }
      
      return $this->render('acceuil.html.twig',array('form' => $formEtablissement1->createView(),'form2'=>$formEtablissement2->createView()
    ));
      }else{
        return $this->redirectToRoute('login');
      }
    }

    /**
    *@Route("/deconnexion", name="deconnexion")
    */
    public function deconnexion(Request $request){
      $session = $request ->getSession();
      
      $session->invalidate();
      return $this->redirectToRoute('login');
     
    }
 /**
  @Route("/profil-partenaire",name="profilePartenaire")
  * 
  */ public function mes_informations(Request $request){
      $session = $request->getSession();
      if ( $session ->get('role') == 'ROLE_PARTENAIRE' ) {
          $image = new imageEtablissement();
      
      $form = $this -> createFormBuilder($image)
      ->add('nomImage',FileType::class)
      ->add('save', SubmitType::class, array('label' => 'Charger'))
      ->getForm();

      //pour initialiser les prix !
      $service = new Services();
      $formService = $this -> createFormBuilder($service)
      ->add('prix', NumberType::class)
      ->add('service')
      ->add('save', SubmitType::class, array('label' => 'valider'))
      ->getForm();

      //rechrecher les etablissements que ce partenaire est proprietaire
      $repository=$this->getDoctrine()->getRepository('AppBundle:Etablissement');
              $listeEtablissement = $repository->findBy(
              array('idProprietaire' => $session->get('id')));
      $nomEtablissement = $repository->findBy(
              array('nomSociete' => $request->request->get('select')));
       $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
        

          $file = $image->getNomImage();
          $fileName = $file->getClientOriginalName();
          $file -> move(
            $this->getParameter('image_directory'),
            $fileName
          );
          $manager = $this->getDoctrine()->getManager();
          if ( $request ->request->get('principale') == "ok" ) {
              $repository=$this->getDoctrine()->getRepository('AppBundle:Etablissement');
              
              
              
              $nomEtablissement[0]->setImagePrincipale($fileName);
              $manager->persist($nomEtablissement[0]);
              $manager->flush();
          }else{
           
           $image->setProprietaire($nomEtablissement[0]->getId());
           $image -> setNomImage($fileName);
           $manager->persist($image);
           $manager->flush();
        }
    }
    dump($request);
    return $this->render('profilPartenaire.html.twig',array(
      'form' => $form->createView(),
      'listeEtablissement' => $listeEtablissement,
      'formService' => $formService->createView()
    ));
  }else{
    return $this->redirectToRoute('login');
  }      
      }  
      /**
      @Route("/profil-partenaire/ajout-service",name="ajoutService") 
      */  
      public function ajout_service(Request $request){
        $manager = $this->getDoctrine()->getManager();
        $service = new Services();

        $data = $request->getContent();
        $data = json_decode($data, true);

        $service->setService($data['service']);
        $service->setIdEtablissement($data['societe']);
        $service->setPrix($data['prix']);

        $manager->persist($service);
        $manager->flush();
        return $this->json(['code' => 200,
          'message' => 'ça Marche frerot'
        ], 200);
      }
  }

