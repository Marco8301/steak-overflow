<?php

namespace App\Entity;

use App\Entity\Traits\SluggableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 * @ORM\Table(name="questions")
 * @ORM\HasLifecycleCallbacks
 */
class Question
{
    use TimestampableTrait;
    use SluggableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le titre de votre question ne peut être vide")
     * @Assert\Length(min="5", minMessage="Le titre de votre question doit contenir au moins 5 caractères")
     */
    private ?string $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Le contenu de votre question ne peut être vide")
     * @Assert\Length(min="10", minMessage="Le contenu de votre question doit contenir au moins 10 caractères")
     */
    private ?string $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isClosed = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getIsClosed(): ?bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(bool $isClosed): self
    {
        $this->isClosed = $isClosed;

        return $this;
    }
}
