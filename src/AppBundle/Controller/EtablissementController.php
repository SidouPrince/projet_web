<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Etablissement;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EtablissementController extends Controller{
	/**
     * @Route("/devenir-partenaire/etablissement/{idProprietaire}", name="partenaireEtablissement")
     */
	public function partenaireEtablissement(Request $request, $idProprietaire){
		$etablissementExiste = 0;

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

         $repository = $this->getDoctrine()->getRepository(Etablissement::class);
        //traiter les infos qui sont envoyées etablissement
        $formEtablissement->handleRequest($request);
        if ( $formEtablissement->isSubmitted() && $formEtablissement->isValid() ) {
                $existeEtablissement = $repository->findOneByNomSociete($etablissement->getNomSociete());
                //si un nom pareil existe pas dans la bdd
                if (!$existeEtablissement) {
                    $etablissement->setidProprietaire($idProprietaire);
                    $etablissement->setImagePrincipale('default.jpeg');
                    $manager = $this->getDoctrine()->getManager();

                    $manager->persist($etablissement);
                    $manager->flush();
                    return $this->redirectToRoute('initPassword', [
                        "id" => $idProprietaire
                    ]);
            }else{
            	$etablissementExiste = 1;
            }
        }

		return $this->render('devenir-partenaire2.html.twig', [
			'formEtablissement' => $formEtablissement->createView(),
			'etablissementExiste' => $etablissementExiste
		]);
	}	
}