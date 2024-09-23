<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
class User
{

    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message:"Un email est nécessaire")]
    #[Assert\Email(message:'Email non valide')]
    private ?string $email = null;
    
    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message:"Un pseudo est nécessaire")]
    #[Assert\Length(min: 3, minMessage: "Un pseudo doit avoir au moins 3 caractères")]
    private ?string $nickName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\OneToMany(targetEntity: Copy::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $ownedCopies;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePic = null;

    public function __construct()
    {
        $this->ownedCopies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

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

    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    public function setNickName(string $nickName): static
    {
        $this->nickName = $nickName;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * @return Collection<int, Copy>
     */
    public function getOwnedCopies(): Collection
    {
        return $this->ownedCopies;
    }

    public function addOwnedCopy(Copy $ownedCopy): static
    {
        if (!$this->ownedCopies->contains($ownedCopy)) {
            $this->ownedCopies->add($ownedCopy);
            $ownedCopy->setOwner($this);
        }

        return $this;
    }

    public function removeOwnedCopy(Copy $ownedCopy): static
    {
        if ($this->ownedCopies->removeElement($ownedCopy)) {
            // set the owning side to null (unless already changed)
            if ($ownedCopy->getOwner() === $this) {
                $ownedCopy->setOwner(null);
            }
        }

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

    public function getProfilePic(): ?string
    {
        return $this->profilePic;
    }

    public function setProfilePic(?string $profilePic): static
    {
        $this->profilePic = $profilePic;

        return $this;
    }

}
