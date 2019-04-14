<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Partenaire;
use AppBundle\Entity\Etablissement;

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
    public function devenirPartenaire()
    {
        // informations personnelles partenaires
        $partenaire = new Partenaire();
        $form = $this -> createFormBuilder($partenaire)
        ->add('nom')
        ->add('prenom')
        ->add('email')
        ->add('telephone')
        ->getForm();

        //informations concernant letablissement
        $etablissement = new Etablissement();
        $formEtablissement = $this -> createFormBuilder($etablissement)
        ->add('nomSociete')
        ->add('typeActivite')
        ->add('employes')
        ->add('rue')
        ->add('codePostale')
        ->add('ville')
        ->getForm();

        return $this->render('partenaire.html.twig', [
            'partenaire' => $form->createView(),
            'formEtablissement' => $formEtablissement->createView()
        ]);
    }
}
