<?php

namespace App\Command;

use App\Entity\Galery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportDecorbanCommand extends Command
{
    protected static $defaultName = 'import-decorban';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Import content from old deCorban wordpress.')
            ->addArgument('file', InputArgument::REQUIRED, 'Fichier à importer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = file_get_contents($input->getArgument(('file')));
        $content = json_decode($file);
        $count = 0;

        foreach($content as $item) {
            // Create a DateTime object.
            $dateTime = new \DateTime();

            $galery = new Galery();
            $galery->setTitle($item->title);
            $galery->setDescription($item->description);
            $galery->setIsFree(false);
            $galery->setIsPublished(false);
            $galery->setCreatedAt($dateTime->setTimestamp((int) $item->timestamp));

            $this->entityManager->persist($galery);
            $this->entityManager->flush();
            $count++;
        }

        $io->success($count . ' galeries ont été importés.');

        return Command::SUCCESS;
    }
}
