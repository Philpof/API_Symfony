<?php

namespace App\Entity;

use App\Repository\ElementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
// use ApiPlatform\Core\Annotation\ApiResource; // I don't want to use API PLATEFORME for this API

// On a créé le "groups" pour pouvoir prendre que certains éléments dans le controller afin de les serialiser en .json et éviter une boucle en prenant '$items'

/**
 * @ORM\Entity(repositoryClass=ElementRepository::class)
 */
class Element
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
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="element")
     * @Groups("element:read")
     */
    private $Items;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("element:read")
     * @Groups("item:read")
     */
    private $icon;

    public function __construct()
    {
        $this->Items = new ArrayCollection();
    }

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

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->Items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->Items->contains($item)) {
            $this->Items[] = $item;
            $item->setElement($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->Items->contains($item)) {
            $this->Items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getElement() === $this) {
                $item->setElement(null);
            }
        }

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }
}
