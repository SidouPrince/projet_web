<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * etablissement
 *
 * @ORM\Table(name="Etablissement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\etablissementRepository")
 */
class Etablissement
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
     * @var string
     *
     * @ORM\Column(name="nomSociete", type="string", length=50)
     */
    private $nomSociete;

    /**
     * @var string
     *
     * @ORM\Column(name="typeActivite", type="string", length=50)
     */
    private $typeActivite;

    /**
     * @var int
     *
     * @ORM\Column(name="employes", type="integer")
     */
    private $employes;

    /**
     * @var string
     *
     * @ORM\Column(name="rue", type="string", length=30)
     */
    private $rue;

    /**
     * @var int
     *
     * @ORM\Column(name="codePostale", type="integer")
     */
    private $codePostale;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=30)
     */
    private $ville;


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
     * Set nomSociete
     *
     * @param string $nomSociete
     *
     * @return etablissement
     */
    public function setNomSociete($nomSociete)
    {
        $this->nomSociete = $nomSociete;

        return $this;
    }

    /**
     * Get nomSociete
     *
     * @return string
     */
    public function getNomSociete()
    {
        return $this->nomSociete;
    }

    /**
     * Set typeActivite
     *
     * @param string $typeActivite
     *
     * @return etablissement
     */
    public function setTypeActivite($typeActivite)
    {
        $this->typeActivite = $typeActivite;

        return $this;
    }

    /**
     * Get typeActivite
     *
     * @return string
     */
    public function getTypeActivite()
    {
        return $this->typeActivite;
    }

    /**
     * Set employes
     *
     * @param integer $employes
     *
     * @return etablissement
     */
    public function setEmployes($employes)
    {
        $this->employes = $employes;

        return $this;
    }

    /**
     * Get employes
     *
     * @return int
     */
    public function getEmployes()
    {
        return $this->employes;
    }

    /**
     * Set rue
     *
     * @param string $rue
     *
     * @return etablissement
     */
    public function setRue($rue)
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * Get rue
     *
     * @return string
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set codePostale
     *
     * @param integer $codePostale
     *
     * @return etablissement
     */
    public function setCodePostale($codePostale)
    {
        $this->codePostale = $codePostale;

        return $this;
    }

    /**
     * Get codePostale
     *
     * @return int
     */
    public function getCodePostale()
    {
        return $this->codePostale;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return etablissement
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }
}

