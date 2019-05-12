<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Partenaire;
use AppBundle\Entity\Client;
use AppBundle\Entity\Etablissement;
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


class EtablissementDetails extends Controller
{
    /**
    * @Route("/etablissement/{id}", name="etablissement")
    */
    public function indexAction (Request $request, $id) {
        $repository=$this->getDoctrine()->getRepository('AppBundle:imageEtablissement');
        $images = $repository->findBy(
              array('proprietaire' => $id));
        dump($images);
        //selectionner les infos du letablissement
        $rep=$this->getDoctrine()->getRepository('AppBundle:Etablissement');
        $infos = $rep->findBy(
              array('id' => $id));

        //selectionner les services du letablissement
        $rep=$this->getDoctrine()->getRepository('AppBundle:Services');
        $services = $rep->findBy(
              array('idEtablissement' => $id));

      return $this->render('etablissement.html.twig',[
        "images" => $images,
        "infos" => $infos[0],
        "services" => $services
      ]);
    }
}

