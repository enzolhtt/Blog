<?php

namespace App\Entity;

use App\Repository\UserSecurityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserSecurityRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class UserSecurity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'userSecurity', targetEntity: Article::class)]
    private Collection $LesArticles;

    public function __construct()
    {
        $this->LesArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(){
        return $this->pseudo;
    }

    public function setPseudo(string $unpseudo)
    {
        $this->pseudo = $unpseudo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __toString()
    {
        return $this->pseudo;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getLesArticles(): Collection
    {
        return $this->LesArticles;
    }

    public function addLesArticle(Article $lesArticle): static
    {
        if (!$this->LesArticles->contains($lesArticle)) {
            $this->LesArticles->add($lesArticle);
            $lesArticle->setUserSecurity($this);
        }

        return $this;
    }

    public function removeLesArticle(Article $lesArticle): static
    {
        if ($this->LesArticles->removeElement($lesArticle)) {
            // set the owning side to null (unless already changed)
            if ($lesArticle->getUserSecurity() === $this) {
                $lesArticle->setUserSecurity(null);
            }
        }

        return $this;
    }
}
