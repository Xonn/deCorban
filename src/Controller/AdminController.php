<?php

namespace App\Controller;

use App\Entity\Galery;
use App\Entity\Picture;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class AdminController extends EasyAdminController
{
    /**
     * @var FileUploader
     */
    private $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }
    
    /**
    * @Route("/uploadImages/{galery}", name="admin_upload_images", options={"expose"=true})
    * @param ObjectManager $manager
    * @param Galery $galery
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function uploadImages(Request $request, ObjectManager $manager, Galery $galery): Response
    {
        $files = $request->files;
        $param = $request->request;
        $response = [];
        $uploadPath = $this->getParameter('server_upload_picture_path');
        $picturePath = $this->getParameter('app.path.picture');

        foreach($files as $file) {
            $filename = $this->fileUploader->upload($file, $uploadPath);

            $picture = new Picture();
            $picture->setGalery($galery);
            $picture->setFilename($filename);
            $picture->setImageFile($file);

            $manager->persist($picture);
            $manager->flush();
           
            $response = [
                'chunkIndex' => $param->get('chunkIndex'),         // the chunk index processed
                'initialPreview' => $picturePath . '/' . $picture->getFilename(), // the thumbnail preview data (e.g. image)
                'initialPreviewConfig' => [
                    [
                        'type' => 'image',      // check previewTypes (set it to 'other' if you want no content preview)
                        'caption' => $picture->getFilename(), // caption
                        'key' => $param->get('fileId'),       // keys for deleting/reorganizing preview
                        'fileId' => $param->get('fileId'),    // file identifier
                        'size' => $param->get('fileSize'),    // file size
                        'zoomData' => $picturePath . '/' . $picture->getFilename(), // separate larger zoom data
                    ]
                ],
                'append' => true
            ];
        }
        return new JsonResponse($response);
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
        $picturePath = $this->getParameter('app.path.picture');

        foreach($galery->getPictures() as $picture) {
            $response['preview'][] = $picturePath . '/' . $picture->getFileName();
            $response['config'][] = ['key' => $picture->getId()];
        }

        return new JsonResponse($response);
    }
}