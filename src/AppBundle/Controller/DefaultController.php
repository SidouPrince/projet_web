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
        // informations personnelles partenaires
        $partenaire = new Partenaire();
        $form = $this -> createFormBuilder($partenaire)
        ->add('nom')
        ->add('prenom')
        ->add('email', EmailType::class)
        ->add('telephone', NumberType::class)
        ->getForm();

        //informations concernant letablissement
        $etablissement = new Etablissement();
        $formEtablissement = $this -> createFormBuilder($etablissement)
        ->add('nomSociete')
        ->add('typeActivite')
        ->add('employes', NumberType::class)
        ->add('rue')
        ->add('codePostale', NumberType::class)
        ->add('ville')
        ->getForm();
        $s = 1;
        //traiter les infos qui sont envoyées par les forms
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($partenaire);
            $manager->flush();
            $s = 11;
            
        }
        //traiter les infos qui sont envoyées etablissement
        $formEtablissement->handleRequest($request);
        dump($formEtablissement);
            if ( $formEtablissement->isSubmitted() && $formEtablissement->isValid() ) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($etablissement);
                $manager->flush();
                $s = 14;
            }
        

        return $this->render('partenaire.html.twig', [
            'partenaire' => $form->createView(),
            'formEtablissement' => $formEtablissement->createView(),
            's' => $s 
        ]);
    }
}
