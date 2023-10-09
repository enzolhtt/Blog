<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;


    #[ORM\ManyToOne(inversedBy: 'lesCategories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'LesCategories', targetEntity: Categorie::class)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Categorie::class)]
    private Collection $LesCategories;

    #[ORM\ManyToOne(inversedBy: 'LesArticles')]
    private ?UserSecurity $userSecurity = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->LesCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /*public function getUserSecurity(): ?UserSecurity
    {
        return $this->userSecurity;
    }

    public function setUserSecurity(?UserSecurity $userSecurity): static
    {
        $this->userSecurity = $userSecurity;

        return $this;
    }*/

    public function __toString()
    {
        return $this->titre;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getUserSecurity(): ?UserSecurity
    {
        return $this->userSecurity;
    }

    public function setUserSecurity(?UserSecurity $userSecurity): static
    {
        $this->userSecurity = $userSecurity;

        return $this;
    }

}
