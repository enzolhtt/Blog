<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Article::class)]
    private Collection $lesCategories;

    public function __construct()
    {
        $this->lesCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getLesCategories(): Collection
    {
        return $this->lesCategories;
    }

    public function addLesCategory(Article $lesCategory): static
    {
        if (!$this->lesCategories->contains($lesCategory)) {
            $this->lesCategories->add($lesCategory);
            $lesCategory->setCategorie($this);
        }

        return $this;
    }

    public function removeLesCategory(Article $lesCategory): static
    {
        if ($this->lesCategories->removeElement($lesCategory)) {
            // set the owning side to null (unless already changed)
            if ($lesCategory->getCategorie() === $this) {
                $lesCategory->setCategorie(null);
            }
        }

        return $this;
    }
}
