<?php

namespace App\Controller;

use App\Entity\Galery;
use App\Entity\Picture;
use App\Service\FileUploader;
use Symfony\Component\Form\Form;
use App\Repository\GaleryRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
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
    * @Route("/uploadImages", name="admin_upload_images", options={"expose"=true})
    * @param GaleryRepository $GaleryRepository
    * @param ObjectManager $manager
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function uploadImages(Request $request, GaleryRepository $GaleryRepository, ObjectManager $manager): Response
    {
        $galery = $GaleryRepository->find(1);
        $files = $request->files;
        $param = $request->request;
        $response = [];
        
        foreach($files as $file) {
            $filename = $this->fileUploader->upload($file, $this->getParameter('server_upload_image_path'));

            $picture = new Picture();
            $picture->setGalery($galery);
            $picture->setFilename($filename);
            $picture->setImageFile($file);
            $manager->persist($picture);
            $manager->flush();
           
            $response = [
                'chunkIndex' => $param->get('chunkIndex'),         // the chunk index processed
                'initialPreview' => '/upload/image/' . $picture->getFilename(), // the thumbnail preview data (e.g. image)
                'initialPreviewConfig' => [
                    [
                        'type' => 'image',      // check previewTypes (set it to 'other' if you want no content preview)
                        'caption' => $picture->getFilename(), // caption
                        'key' => $param->get('fileId'),       // keys for deleting/reorganizing preview
                        'fileId' => $param->get('fileId'),    // file identifier
                        'size' => $param->get('fileSize'),    // file size
                        'zoomData' => '/upload/image/' . $picture->getFilename(), // separate larger zoom data
                    ]
                ],
                'append' => true
            ];
        }
        //dd($request);
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
        foreach($galery->getPictures() as $picture) {
            $response['preview'][] = '/upload/image/' . $picture->getFileName();
            $response['config'][] = ['key' => $picture->getId()];
        }

        return new JsonResponse($response);
    }

    public function persistGaleryEntity(Galery $galery, Form $form)
    {
        //if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            dd($data);
            // dd($data);
            // if($data->getPictureFiles()) {
            //     $pictures = $data->getPictureFiles()->getPictureFiles();

            //     foreach($pictures as $file) {
            //         dump($file);
            //         $picture = new Picture();
            //         $picture->setGalery($galery);
            //         $picture->setFilename($file->getClientOriginalName());
            //         $picture->setImageFile($file);
            //         //dd($file->getClientOriginalName());
            //         $this->em->persist($picture);
            //         $this->em->flush();
            //         parent::createNewEntity();
            //     }
            //    // die();
            // }
            
        //}
    }

    // public function persistEntity($entity)
    // {
    //     dump($entity);
    //     parent::persistEntity($entity);
    // }
}