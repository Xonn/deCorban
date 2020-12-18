<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use Stripe\Event;
use Stripe\Charge;
use Stripe\Stripe;
use App\Entity\User;
use App\Api\StripeApi;
use Stripe\StripeClient;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(StripeApi $stripeApi, EntityManagerInterface $em)
    {
        $this->stripeApi = $stripeApi;
        $this->em = $em;
    }

    /**
     * @Route("/stripe/checkout", name="stripe_checkout", options={"expose"="true"})
     */
    public function checkout(StripeApi $api) : Response
    {
        try {
            $api->createCustomer($this->getUser());

            return $this->json([
                'id' => $api->createPaymentSession($this->getUser()),
            ]);
        } catch (\Exception $e) {
            return $this->json(['title' => "Impossible de contacter l'API Stripe"], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @Route("/stripe/webhook", name="stripe_webhook")
     */
    public function index(Event $event): JsonResponse
    {
        switch ($event->type) {
            case 'charge.refunded':
                return $this->onRefund($event->data['object']);
            case 'checkout.session.completed':
                return $this->onCheckoutCompleted($event->data['object']);
            default:
                return $this->json([]);
        }
    }

    public function onCheckoutCompleted(Session $session): JsonResponse
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['stripeId' => $session->customer]);
        $user->setPremiumDuration('P30D');
        $this->em->flush();

        $this->addFlash('success', 'Votre abonnement est désormais actif pour une période de 1 mois.');

        return $this->json($session);
    }

    public function onRefund(Charge $charge): JsonResponse
    {
        return $this->json($charge);
    }
}
