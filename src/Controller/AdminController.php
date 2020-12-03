<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Galery;
use App\Entity\Attachment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class AdminController extends EasyAdminController
{    
    // private EntityManagerInterface $em;

    // public function __construct(EntityManagerInterface $em)
    // {
    //     $this->em = $em;
    // }

    /**
    * @Route("/uploadImages/{galery}", name="admin_upload_images", options={"expose"=true})
    * @param EntityManagerInterface $manager
    * @param Galery $galery
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function uploadImages(Request $request, EntityManagerInterface $manager, Galery $galery): Response
    {
        $files = $request->files;

        foreach($files as $file) {
            $attachment = new Attachment();
            $attachment->setGalery($galery);
            $attachment->setFile($file);

            $manager->persist($attachment);
            $manager->flush();
        }

        return new JsonResponse();
    }

    /**
    * @Route("/getImages/{galery}", name="admin_get_images", options={"expose"=true})
    * @param Galery $galery
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function getImages(Request $request, Galery $galery): Response
    {
        if(!$galery) {
            return new Response();
        }
        
        $response = [];
        $picturePath = $this->getParameter('path.attachments');

        foreach($galery->getAttachments() as $attachment) {
            $source = $picturePath . '/' . $attachment->getFileName();

            array_push($response, [
                'source' => $source,
                'options' => ['type' => 'local']
            ]);
        }

        return new JsonResponse($response);
    }

    /**
    * @Route("/deleteImage/{galery}", name="admin_delete_image", options={"expose"=true})
    * @param Galery $galery
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function deleteImage(Request $request, Galery $galery): Response
    {
        if(!$galery) {
            return new Response();
        }

        return new JsonResponse('ok');
    }

    // /**
    //  * @Route("/admin/premium/add", name="premium_add", methods="GET")
    //  */
    // public function onAddPremium(): Response
    // {
    //     $user = $this->em->getRepository(User::class)->findOneBy(['stripeId' => 'cus_IH1fTybn6WdXbN']);

    //     $user->setPremium('P30D');
    //     $this->em->flush();

    //     $this->addFlash('success', 'Votre abonnement est désormais actif pour une période de 1 mois.');
        
    //     return $this->redirectToRoute('security_user_profile');
    // }
    // /**
    //  * @Route("/admin/premium/remove", name="premium_remove", methods="GET")
    //  */
    // public function onRemovePremium(): Response
    // {
    //     $user = $this->em->getRepository(User::class)->findOneBy(['stripeId' => 'cus_IH1fTybn6WdXbN']);
    //     $user->setPremium(null);
    //     $this->em->flush();
        
    //     $this->addFlash('success', 'Votre abonnement a été annulé.');
        
    //     return $this->redirectToRoute('security_user_profile');
    // }
}