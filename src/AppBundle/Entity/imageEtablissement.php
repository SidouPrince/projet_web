<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * imageEtablissement
 *
 * @ORM\Table(name="image_etablissement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\imageEtablissementRepository")
 */
class imageEtablissement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="proprietaire", type="integer")
     */
    private $proprietaire;

    /**
     * @var string
     *
     * @ORM\Column(name="nomImage", type="string", length=255)
     */
    private $nomImage;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set proprietaire
     *
     * @param integer $proprietaire
     *
     * @return imageEtablissement
     */
    public function setProprietaire($proprietaire)
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    /**
     * Get proprietaire
     *
     * @return int
     */
    public function getProprietaire()
    {
        return $this->proprietaire;
    }

    /**
     * Set nomImage
     *
     * @param string $nomImage
     *
     * @return imageEtablissement
     */
    public function setNomImage($nomImage)
    {
        $this->nomImage = $nomImage;

        return $this;
    }

    public function getNomImage()
    {
        return $this->nomImage;
    }
}

