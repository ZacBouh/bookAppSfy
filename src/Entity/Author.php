<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nickname = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfDeath = null;


    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: "writer")]
    private Collection $booksWritten;

    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: "penciler")]
    private Collection $booksDrawn;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'alias')]
    private ?self $realAuthor = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'realAuthor')]
    private Collection $alias;

    public function __construct()
    {
        $this->alias = new ArrayCollection();
        $this->booksWritten = new ArrayCollection();
        $this->booksDrawn = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
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

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getDateOfDeath(): ?\DateTimeInterface
    {
        return $this->dateOfDeath;
    }

    public function setDateOfDeath(?\DateTimeInterface $dateOfDeath): static
    {
        $this->dateOfDeath = $dateOfDeath;

        return $this;
    }

   
    /**
     * @return Collection<int, Book>
     */
    public function getBooksWritten(): Collection
    {
        return $this->booksWritten;
    }

    public function addBookWritten(Book $book): static
    {
        if (!$this->booksWritten->contains($book)) {
            $this->booksWritten->add($book);
            $book->addWriter($this);
        }

        return $this;
    }

    public function removeBookWritten(Book $book): static
    {
        if ($this->booksWritten->removeElement($book)) {
            $book->removeWriter($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooksDrawn(): Collection
    {
        return $this->booksDrawn;
    }

    public function addBookDrawn(Book $book): static
    {
        if (!$this->booksDrawn->contains($book)) {
            $this->booksDrawn->add($book);
            $book->addWriter($this);
        }

        return $this;
    }

    public function removeBookDrawn(Book $book): static
    {
        if ($this->booksDrawn->removeElement($book)) {
            $book->removeWriter($this);
        }

        return $this;
    }

    public function getRealAuthor(): ?self
    {
        return $this->realAuthor;
    }

    public function setRealAuthor(?self $realAuthor): static
    {
        $this->realAuthor = $realAuthor;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getAlias(): Collection
    {
        return $this->alias;
    }

    public function addAlias(self $alias): static
    {
        if (!$this->alias->contains($alias)) {
            $this->alias->add($alias);
            $alias->setRealAuthor($this);
        }

        return $this;
    }

    public function removeAlias(self $alias): static
    {
        if ($this->alias->removeElement($alias)) {
            // set the owning side to null (unless already changed)
            if ($alias->getRealAuthor() === $this) {
                $alias->setRealAuthor(null);
            }
        }

        return $this;
    }

    
}
