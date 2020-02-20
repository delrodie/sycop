<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DistrictRepository")
 */
class District
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
    private $localite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="districts")
     */
    private $region;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Gestionnaire", mappedBy="district")
     */
    private $gestionnaires;

    public function __construct()
    {
        $this->gestionnaires = new ArrayCollection();
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

    public function getLocalite(): ?string
    {
        return $this->localite;
    }

    public function setLocalite(?string $localite): self
    {
        $this->localite = $localite;

        return $this;
    }

    public function getRegion(): ?region
    {
        return $this->region;
    }

    public function setRegion(?region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(?int $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Gestionnaire[]
     */
    public function getGestionnaires(): Collection
    {
        return $this->gestionnaires;
    }

    public function addGestionnaire(Gestionnaire $gestionnaire): self
    {
        if (!$this->gestionnaires->contains($gestionnaire)) {
            $this->gestionnaires[] = $gestionnaire;
            $gestionnaire->setDistrict($this);
        }

        return $this;
    }

    public function removeGestionnaire(Gestionnaire $gestionnaire): self
    {
        if ($this->gestionnaires->contains($gestionnaire)) {
            $this->gestionnaires->removeElement($gestionnaire);
            // set the owning side to null (unless already changed)
            if ($gestionnaire->getDistrict() === $this) {
                $gestionnaire->setDistrict(null);
            }
        }

        return $this;
    }
}
