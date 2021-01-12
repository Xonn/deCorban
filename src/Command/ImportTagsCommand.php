<?php

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportTagsCommand extends Command
{
    protected static $defaultName = 'import-categories';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Import categories from deCorban wordpress.')
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
            $category = new Category();
            
            $category->setName(mb_convert_case($item->name, MB_CASE_TITLE, 'UTF-8'));

            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $count++;
        }

        $io->success($count . ' cetégories ont été importés.');

        return Command::SUCCESS;
    }
}
