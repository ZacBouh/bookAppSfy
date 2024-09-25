<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publicationDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $summary = null;

    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy:"booksWritten", cascade:['persist'])]
    #[ORM\JoinTable(name: "books_writers")]
    #[ORM\JoinColumn(name: "book_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "writer_id", referencedColumnName: "id")]
    private Collection $writer;

    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: "booksDrawn", cascade: ['persist'])]
    #[ORM\JoinTable(name: "books_pencilers")]
    #[ORM\JoinColumn(name: "book_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "penciler_id", referencedColumnName: "id")]
    private Collection $penciler;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Publisher $publisher = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?BookCollection $collection = null;

    #[ORM\OneToMany(targetEntity: Copy::class, mappedBy: 'book')]
    private Collection $copies;

    public function __construct()
    {
        $this->writer = new ArrayCollection();
        $this->penciler = new ArrayCollection();
        $this->copies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(?\DateTimeInterface $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getWriter(): Collection
    {
        return $this->writer;
    }

    public function addWriter(Author $writer): static
    {
        if (!$this->writer->contains($writer)) {
            $this->writer->add($writer);
        }

        return $this;
    }

    public function removeWriter(Author $writer): static
    {
        $this->writer->removeElement($writer);

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getPenciler(): Collection
    {
        return $this->penciler;
    }

    public function addPenciler(Author $penciler): static
    {
        if (!$this->penciler->contains($penciler)) {
            $this->penciler->add($penciler);
        }

        return $this;
    }

    public function removePenciler(Author $penciler): static
    {
        $this->penciler->removeElement($penciler);

        return $this;
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    public function setPublisher(?Publisher $publisher): static
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getCollection(): ?BookCollection
    {
        return $this->collection;
    }

    public function setCollection(?BookCollection $collection): static
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @return Collection<int, Copy>
     */
    public function getCopies(): Collection
    {
        return $this->copies;
    }

    public function addCopy(Copy $copy): static
    {
        if (!$this->copies->contains($copy)) {
            $this->copies->add($copy);
            $copy->setBook($this);
        }

        return $this;
    }

    public function removeCopy(Copy $copy): static
    {
        if ($this->copies->removeElement($copy)) {
            // set the owning side to null (unless already changed)
            if ($copy->getBook() === $this) {
                $copy->setBook(null);
            }
        }

        return $this;
    }


}
