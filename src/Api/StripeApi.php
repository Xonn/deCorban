<?php

namespace App\Api;

use App\Entity\Galery;
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

    public function createPaymentSession(User $user, ?Galery $galery, string $type): string
    {
        $params = [
            'cancel_url' => $this->urlGenerator->generate('home', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'customer' => $user->getStripeId(),
        ];

        // If type is rent, send galery id.
        if ($type == 'rent') {
            $params['success_url'] = $this->urlGenerator->generate('galery.show', ['slug' => $galery->getSlug(), 'transaction' => 'rent'], UrlGeneratorInterface::ABSOLUTE_URL);
            $params['line_items'] = [['price' => 'price_1I0Zg1E4KkenaKplbqL1G6Ze', 'quantity' => 1]];
            $params['metadata'] = ['galeryId' => $galery->getId()];
        } else {
            $params['success_url'] = $this->urlGenerator->generate('galery.show', ['slug' => $galery->getSlug(), 'transaction' => 'subscription'], UrlGeneratorInterface::ABSOLUTE_URL);
            $params['line_items'] = [['price' => 'price_1HfMkDE4KkenaKplnK8lkYHf', 'quantity' => 1]];
        }

        $session = $this->stripe->checkout->sessions->create($params);

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
