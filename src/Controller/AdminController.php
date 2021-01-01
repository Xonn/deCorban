<?php

namespace App\Controller;

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
    /**
    * @Route("/admin/uploadImages/{galery}", name="admin_upload_images")
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
    * @Route("/admin/getImages/{galery}", name="admin_get_images")
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
    * @Route("/admin/deleteImage/{galery}/{fileName}", name="admin_delete_image")
    * @param EntityManagerInterface $manager
    * @param Galery $galery
    * @param Attachment $attachment
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function deleteImage(Request $request, EntityManagerInterface $manager, Galery $galery, Attachment $attachment): Response
    {
        if(!$galery) {
            return new Response('No galery given');
        }

        if(!$attachment) {
            return new Response('No attachment given');
        }

        // Remove attachment from galery.
        $galery->removeAttachment($attachment);

        $manager->remove($attachment);
        $manager->persist($attachment);
        $manager->flush();

        return new JsonResponse();
    }
}