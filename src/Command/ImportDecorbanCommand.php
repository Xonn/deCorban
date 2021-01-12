<?php

namespace App\Command;

use App\Entity\Galery;
use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\Asset\Package;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportDecorbanCommand extends Command
{
    protected static $defaultName = 'import-galeries';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Import galeries from deCorban wordpress.')
            ->addArgument('file', InputArgument::REQUIRED, 'Fichier à importer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = file_get_contents($input->getArgument(('file')));
        $content = json_decode($file);
        $count = 0;

        // Create a DateTime object.
        $dateTime = new \DateTime();

        foreach($content as $item) {
            
            $galery = new Galery();
            $galery->setTitle($item->title);
            $galery->setDescription($item->description);
            $galery->setIsFree(false);
            $galery->setIsPublished(false);
            $galery->setCreatedAt($dateTime->setTimestamp((int) $item->timestamp));

            $categories = explode(',', $item->categories);

            foreach($categories as $category) {
                $cat = $this->entityManager->getRepository(Category::class)->findOneBy(['name' => $category]);
                
                if ($cat) {
                    $galery->addCategory($cat);
                }
            }

            $this->entityManager->persist($galery);
            $this->entityManager->flush();
            $count++;
        }

        $io->success($count . ' galeries ont été importés.');

        return Command::SUCCESS;
    }
}
