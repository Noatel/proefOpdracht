<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Serializer\Annotation\MaxDepth;


/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 *
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    
    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"order"})
     */
    private $reference;

    /**
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"order"})
     */
    private $email;

    /**
     * @JMS\MaxDepth(1)
     * @ORM\OneToMany(targetEntity="App\Entity\OrderRule", mappedBy="order")
     * @JMS\Groups({"product"})
     */
    private $orderRule;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Address", inversedBy="orders")
     * @JMS\Groups({"address"})
     */
    private $address;

    public function __construct()
    {
        $this->orderRule = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|OrderRule[]
     */
    public function getOrderRule(): Collection
    {
        return $this->orderRule;
    }

    public function addOrder(OrderRule $orderRule): self
    {
        if (!$this->orderRule->contains($orderRule)) {
            $this->orderRule[] = $orderRule;
            $orderRule->setOrder($this);
        }

        return $this;
    }

    public function removeOrder(OrderRule $orderRule): self
    {
        if ($this->orderRule->contains($orderRule)) {
            $this->orderRule->removeElement($orderRule);
            // set the owning side to null (unless already changed)
            if ($orderRule->getOrder() === $this) {
                $orderRule->setOrder(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

}
