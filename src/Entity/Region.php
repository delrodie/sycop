<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegionRepository")
 */
class Region
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $couleur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreDistrict;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\District", mappedBy="region")
     */
    private $districts;

    public function __construct()
    {
        $this->districts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getNombreDistrict(): ?int
    {
        return $this->nombreDistrict;
    }

    public function setNombreDistrict(?int $nombreDistrict): self
    {
        $this->nombreDistrict = $nombreDistrict;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return Collection|District[]
     */
    public function getDistricts(): Collection
    {
        return $this->districts;
    }

    public function addDistrict(District $district): self
    {
        if (!$this->districts->contains($district)) {
            $this->districts[] = $district;
            $district->setRegion($this);
        }

        return $this;
    }

    public function removeDistrict(District $district): self
    {
        if ($this->districts->contains($district)) {
            $this->districts->removeElement($district);
            // set the owning side to null (unless already changed)
            if ($district->getRegion() === $this) {
                $district->setRegion(null);
            }
        }

        return $this;
    }

}
