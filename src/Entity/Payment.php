<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 */
class Payment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datePayment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cardNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cardType;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Ordere", mappedBy="payment", cascade={"persist", "remove"})
     */
    private $ordere;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePayment(): ?\DateTimeInterface
    {
        return $this->datePayment;
    }

    public function setDatePayment(?\DateTimeInterface $datePayment): self
    {
        $this->datePayment = $datePayment;

        return $this;
    }

    public function getCardNumber(): ?int
    {
        return $this->cardNumber;
    }

    public function setCardNumber(?int $cardNumber): self
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    public function setCardType(?string $cardType): self
    {
        $this->cardType = $cardType;

        return $this;
    }

    public function getOrdere(): ?Order
    {
        return $this->ordere;
    }

    public function setOrdere(?Order $ordere): self
    {
        $this->ordere = $ordere;

        // set (or unset) the owning side of the relation if necessary
        $newPayment = $ordere === null ? null : $this;
        if ($newPayment !== $ordere->getPayment()) {
            $ordere->setPayment($newPayment);
        }

        return $this;
    }
}
