<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'le champ doit etre rempli')]
    #[Assert\Email(
        message: "L'email {{ value }} n'est pas un email valide.",
        normalizer: 'trim'
    )]
    #[Assert\NoSuspiciousCharacters]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * @var Collection<int, Operation>
     */
    #[ORM\OneToMany(targetEntity: Operation::class, mappedBy: 'user')]
    private Collection $operations;

    #[Assert\NotBlank(
        message: 'le champ doit etre rempli'
    )]
    #[Assert\PasswordStrength(
        message: 'Le mot de passe est trop faible !'
    )]
    #[Assert\NotCompromisedPassword(
        message: "Ce mot de passe a été divulgué."
    )]
    private ?string $plainPassword = null;

    public function __construct()
    {
        $this->operations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Operation>
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    public function addOperation(Operation $operation): static
    {
        if (!$this->operations->contains($operation)) {
            $this->operations->add($operation);
            $operation->setUser($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): static
    {
        if ($this->operations->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getUser() === $this) {
                $operation->setUser(null);
            }
        }

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
