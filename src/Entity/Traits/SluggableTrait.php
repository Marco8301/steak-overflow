<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

trait SluggableTrait
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $slug;

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
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function createSlug(): void
    {
        $this->setSlug((new AsciiSlugger())->slug($this->getTitle()));
    }
}
