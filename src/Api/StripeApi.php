<?php

namespace App\Api;

use Stripe\Plan;
use Stripe\Stripe;
use App\Entity\User;
use Stripe\Customer;
use Stripe\StripeClient;
use Stripe\Subscription;
use Stripe\PaymentIntent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeApi
{
    private StripeClient $stripe;
    protected $urlGenerator;
    protected $em;

    public function __construct(string $privateKey, UrlGeneratorInterface $urlGenerator, EntityManagerInterface $em)
    {
        Stripe::setApiVersion('2020-08-27');
        $this->stripe = new StripeClient($privateKey);
        $this->urlGenerator = $urlGenerator;
        $this->em = $em;
    }

    /**
     * Crée un customer stripe et sauvegarde l'id dans l'utilisateur.
     */
    public function createCustomer(User $user): User
    {
        if ($user->getStripeId()) {
            return $user;
        }
        $client = $this->stripe->customers->create([
            'metadata' => [
                'user_id' => (string) $user->getId(),
            ],
            'email' => $user->getEmail(),
            'name' => $user->getUsername(),
        ]);
        $user->setStripeId($client->id);
        $this->em->flush();

        return $user;
    }

    public function getCustomer(string $customerId): Customer
    {
        return $this->stripe->customers->retrieve($customerId);
    }

    public function getSubscription(string $subscriptionId): Subscription
    {
        return $this->stripe->subscriptions->retrieve($subscriptionId);
    }

    public function getPaymentIntent(string $id): PaymentIntent
    {
        return $this->stripe->paymentIntents->retrieve($id);
    }

    public function createPaymentSession(User $user): string
    {
        $session = $this->stripe->checkout->sessions->create([
            'cancel_url' => $this->urlGenerator->generate('home', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'success_url' => $this->urlGenerator->generate('security_user_profile', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'mode' => 'payment',
            'payment_method_types' => [
                'card',
            ],
            'customer' => $user->getStripeId(),
            'line_items' => [['price' => 'price_1HfMkDE4KkenaKplnK8lkYHf', 'quantity' => 1]],
        ]);

        return $session->id;
    }

    /**
     * Renvoie l'url du profil d'abonnement stripe.
     */
    public function getBillingUrl(User $user, string $returnUrl): string
    {
        return $this->stripe->billingPortal->sessions->create([
            'customer' => $user->getStripeId(),
            'return_url' => $returnUrl,
        ])->url;
    }

    public function getPlan(string $id): Plan
    {
        return $this->stripe->plans->retrieve($id);
    }
}
