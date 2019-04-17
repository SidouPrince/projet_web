<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Partenaire;
use AppBundle\Entity\Client;
use AppBundle\Entity\Etablissement;
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


class DefaultController extends Controller
{
    /**
    * @Route("/", name="homepage")
    */
    public function indexAction () {
      return $this->render('home.html.twig');
    }
/**
  @Route("/connexion",name="connecter")
  *
  */

    public function connexion(Request $request){
      $client=new Client();
      $form=$this->createFormBuilder($client)
      ->add('Email',EmailType::class)
      ->add('mot_de_passe',PasswordType::class)
             ->add('save', SubmitType::class, array('label' => 'Connecter'))
             ->getForm();

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $email=$form->get('Email')->getData();
        $mdp=$form->get('mot_de_passe')->getData();
        $cpt=0;
      $repository=$this->getDoctrine()->getRepository('AppBundle:Client');
      $list_email=$repository->findAll();
      for ($i=0; $i<count($list_email); $i++) { 
        if(($list_email[$i]->getEmail()==$email)&&($list_email[$i]->getMotDePasse()==$mdp)){
          $cpt++;
          return $this->redirectToRoute('profil');   
        }
      }
      if($cpt==0){
        return $this->render('erreur_connexion.html.twig',array('form'=>$form->createView()));
        //return $this->redirectToRoute('erreur');
      }
      }
      return $this->render('connexion.html.twig',array('form' => $form->createView()));
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
  @Route("/connexion/profil",name="profil")
  * 
  */   
    public function monprofil(Request $request){
      $etablissement = new Etablissement();
      $formEtablissement = $this -> createFormBuilder($etablissement)
      ->add('typeActivite',TextType::class)
      ->add('ville',TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Recherche'))
            ->getForm();
	 // tester si le formulaire est déjà rempli
      $formEtablissement->handleRequest($request);
      if ($formEtablissement->isSubmitted() && $formEtablissement->isValid()) {
          $activite=$formEtablissement->get('typeActivite')->getData();
          $ville=$formEtablissement->get('ville')->getData();
          $cpt=0;
          $repository=$this->getDoctrine()->getRepository('AppBundle:Etablissement');
         /* $list_activite=$repository->findByTypeActivite($activite);
          return $this->render('resultat.html.twig',array('list_activite' => $list_activite));*/
          $list_activite=$repository->findAll();
          return $this->render('resultat.html.twig',array('list_activite' => $list_activite,'activite'=>$activite,'ville'=>$ville));
      }      
      return $this->render('profil.html.twig',array('form' => $formEtablissement->createView()));
    }

}
