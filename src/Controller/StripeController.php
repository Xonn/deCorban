<?php

namespace App\Controller;

use Stripe\Event;
use Stripe\Charge;
use App\Entity\User;
use App\Api\StripeApi;
use App\Entity\Galery;
use App\Entity\Payment;
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
     * @Route("/stripe/checkout/{galery}/{type}", name="stripe_checkout", options={"expose"="true"})
     * @param Galery $galery
     */
    public function checkout(?Galery $galery = null, string $type, StripeApi $api) : Response
    {
        try {
            $api->createCustomer($this->getUser());

            return $this->json([
                'id' => $api->createPaymentSession($this->getUser(), $galery, $type),
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
                break;
            case 'checkout.session.completed':
                return $this->onCheckoutCompleted($event->data['object']);
                break;
            default:
                return $this->json([]);
                break;
        }
    }

    public function onCheckoutCompleted(Session $session): JsonResponse
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['stripeId' => $session->customer]);

        // Create new payment entry
        $payment = new Payment();
        $payment->setUser($user);
        $payment->setStartDate(new \DateTime());

        // Store payment intent id (Stripe)
        $payment->setPId($session->payment_intent);
        
        // If galeryId is set, user rent a galery
        if (isset($session->metadata->galeryId)) {
            $galery = $this->em->getRepository(Galery::class)->find($session->metadata->galeryId);

            $payment->setType('rent');
            $payment->setGalery($galery);
            $payment->setPremiumDuration('P1D');
        } else {
            $payment->setType('subscription');
            $payment->setPremiumDuration('P30D');
        }

        $this->em->persist($payment);
        $this->em->flush();

        return $this->json($session);
    }

    public function onRefund(Charge $charge): JsonResponse
    {
        return $this->json($charge);
    }
}
