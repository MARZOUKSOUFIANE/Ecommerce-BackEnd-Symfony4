<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductsRepository")
 * @ApiResource(normalizationContext={"groups"={"product"}})
 * @ApiFilter(BooleanFilter::class, properties={"selected","promotion","available"})
 * @ApiFilter(SearchFilter::class, properties={"name": "partial"})
 */

class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"product"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product"})
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"product"})
     */
    private $currentPrice;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"product"})
     */
    private $promotion;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"product"})
     */
    private $selected;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"product"})
     */
    private $available;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"product"})
     */
    private $photoName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     * @Groups({"product"})
     */
    private $category;

    /**
     * @var int
     * @Groups({"product"})
     */
    private $quantity=1;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="product")
     * @Groups({"none"})
     */
    private $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCurrentPrice(): ?float
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(float $currentPrice): self
    {
        $this->currentPrice = $currentPrice;

        return $this;
    }

    public function getPromotion(): ?bool
    {
        return $this->promotion;
    }

    public function setPromotion(bool $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getSelected(): ?bool
    {
        return $this->selected;
    }

    public function setSelected(bool $selected): self
    {
        $this->selected = $selected;

        return $this;
    }

    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getPhotoName(): ?string
    {
        return $this->photoName;
    }

    public function setPhotoName(?string $photoName): self
    {
        $this->photoName = $photoName;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->contains($orderItem)) {
            $this->orderItems->removeElement($orderItem);
            // set the owning side to null (unless already changed)
            if ($orderItem->getProduct() === $this) {
                $orderItem->setProduct(null);
            }
        }

        return $this;
    }

}
