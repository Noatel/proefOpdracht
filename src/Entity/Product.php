<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"product"})
     */
    private $name;


    /**
     * @Assert\NotBlank
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     *
     * @Assert\NotBlank
     * @ORM\OneToMany(targetEntity="App\Entity\OrderRule", mappedBy="product", orphanRemoval=true)
     *
     */
    private $orderRule;




    public function __construct()
    {
        $this->orderRule = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|OrderRule[]
     */
    public function getOrderRule(): Collection
    {
        return $this->orderRule;
    }

    public function addOrderRule(OrderRule $orderRule): self
    {
        if (!$this->orderRule->contains($orderRule)) {
            $this->orderRule[] = $orderRule;
            $orderRule->setProduct($this);
        }

        return $this;
    }

    public function removeOrderRule(OrderRule $orderRule): self
    {
        if ($this->orderRule->contains($orderRule)) {
            $this->orderRule->removeElement($orderRule);
            // set the owning side to null (unless already changed)
            if ($orderRule->getProduct() === $this) {
                $orderRule->setProduct(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }



}
