<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Etablissement;
use AppBundle\Entity\Partenaire;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class InitPassword extends Controller
{
	/**
     * @Route("/init-password/{id}", name="initPassword")
     */
    public function initPassword(Request $request, $id)
    {
    	$inscriptionTerminee = 0;

    	if ( $request->request->count() > 0 ) {
    		$repository = $this->getDoctrine()->getRepository(Partenaire::class);
    		$partenaire = $repository->findOneById($id);
    		$mdp = $request->request->get('password');
    		$partenaire->setPassword($mdp);
    		$manager = $this->getDoctrine()->getManager();
            $manager->persist($partenaire);
            $manager->flush();
            $inscriptionTerminee = 1;
    	}
    	

        
        return $this->render('init_password.html.twig', [
        		'inscriptionTerminee' => $inscriptionTerminee
        	]);
    }
}