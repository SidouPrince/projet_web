<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Partenaire;
use AppBundle\Entity\Etablissement;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
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
}
