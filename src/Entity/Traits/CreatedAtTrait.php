<?php 

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait CreatedAtTrait
{

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {

        if($this->createdAt === null)
        $this->createdAt = new \DateTimeImmutable("NOW");
    }

     public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

}