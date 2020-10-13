<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("element:read")
     * @Groups("item:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("element:read")
     * @Groups("item:read")
     * @Assert\NotBlank(message="Le titre est obligatoire")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Element::class, inversedBy="Items")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("item:read")
     * @Assert\NotBlank(message="L'élément est obligatoire")
     */
    private $element;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getElement(): ?Element
    {
        return $this->element;
    }

    public function setElement(?Element $element): self
    {
        $this->element = $element;

        return $this;
    }
}
