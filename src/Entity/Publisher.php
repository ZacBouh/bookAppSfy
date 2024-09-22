<?php

namespace App\Entity;

use App\Repository\PublisherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublisherRepository::class)]
class Publisher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nationality = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $foundingDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $closureDate = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'branch')]
    private ?self $parentCompany = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parentCompany')]
    private Collection $branch;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'publisher')]
    private Collection $books;

    #[ORM\OneToMany(targetEntity: BookCollection::class, mappedBy: 'publisher')]
    private Collection $collections;

    public function __construct()
    {
        $this->branch = new ArrayCollection();
        $this->books = new ArrayCollection();
        $this->collections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getFoundingDate(): ?\DateTimeInterface
    {
        return $this->foundingDate;
    }

    public function setFoundingDate(?\DateTimeInterface $foundingDate): static
    {
        $this->foundingDate = $foundingDate;

        return $this;
    }

    public function getClosureDate(): ?\DateTimeInterface
    {
        return $this->closureDate;
    }

    public function setClosureDate(?\DateTimeInterface $closureDate): static
    {
        $this->closureDate = $closureDate;

        return $this;
    }

    public function getParentCompany(): ?self
    {
        return $this->parentCompany;
    }

    public function setParentCompany(?self $parentCompany): static
    {
        $this->parentCompany = $parentCompany;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getBranch(): Collection
    {
        return $this->branch;
    }

    public function addBranch(self $branch): static
    {
        if (!$this->branch->contains($branch)) {
            $this->branch->add($branch);
            $branch->setParentCompany($this);
        }

        return $this;
    }

    public function removeBranch(self $branch): static
    {
        if ($this->branch->removeElement($branch)) {
            // set the owning side to null (unless already changed)
            if ($branch->getParentCompany() === $this) {
                $branch->setParentCompany(null);
            }
        }

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

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setPublisher($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getPublisher() === $this) {
                $book->setPublisher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BookCollection>
     */
    public function getCollections(): Collection
    {
        return $this->collections;
    }

    public function addCollection(BookCollection $collection): static
    {
        if (!$this->collections->contains($collection)) {
            $this->collections->add($collection);
            $collection->setPublisher($this);
        }

        return $this;
    }

    public function removeCollection(BookCollection $collection): static
    {
        if ($this->collections->removeElement($collection)) {
            // set the owning side to null (unless already changed)
            if ($collection->getPublisher() === $this) {
                $collection->setPublisher(null);
            }
        }

        return $this;
    }
}
