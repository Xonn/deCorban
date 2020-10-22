<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait Stripe
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $stripeId = null;

    public function getStripeId(): ?string
    {
        return $this->stripeId;
    }

    public function setStripeId(?string $stripeId): self
    {
        $this->stripeId = $stripeId;

        return $this;
    }
}
