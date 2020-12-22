<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 */
class Payment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="payments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Galery::class, inversedBy="payments")
     */
    private $galery;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getGalery(): ?Galery
    {
        return $this->galery;
    }

    public function setGalery(?Galery $galery): self
    {
        $this->galery = $galery;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getPId(): ?string
    {
        return $this->pId;
    }

    public function setPId(string $pId): self
    {
        $this->pId = $pId;

        return $this;
    }

    public function setPremiumDuration(?string $interval): self
    {
        $date = new \DateTime();

        if ($this->isPremium()) {
            $endDate = $this->getEndDate();
            $date->setTimestamp($endDate->getTimestamp());
        }

        $this->endDate = $interval == null ? $interval : $date->add(new \DateInterval($interval));

        return $this;
    }

    public function isPremium(): bool
    {
        $now = new \DateTime();
        $endDate = $this->getEndDate();

        return $endDate > $now;
    }

    public function getRemainingTime(): string
    {
        $diff = date_diff($this->getEndDate(), new DateTime())->format('%H h %I');

        return $diff;
    }

    public function get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getPId();
    }
}
