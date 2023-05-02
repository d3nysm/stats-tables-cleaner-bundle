<?php

namespace D3nysm\Bundle\StatsTablesCleaner\Command;

use D3nysm\Bundle\StatsTablesCleaner\Service\StatsTablesCleanerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CleanCommand extends Command
{
    protected static $defaultName = 'stats-tables-cleaner:clean';
    protected static $defaultDescription = 'Run cleaning old entries';

    /** @var StatsTablesCleanerService */
    protected $service;

    public function __construct(StatsTablesCleanerService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $annotations = $this->service->findEntityAnnotations();
        ksort($annotations);


        foreach ($annotations as $entityClass => $annotation) {
            $io->text("<info>$entityClass</info> Start removing with interval <info>{$annotation->getInterval()}</info>");
            $affectedRows = $this->service->deleteOldEntriesByAnnotation($entityClass, $annotation);
            $io->text("<info>$entityClass</info> Deleted <info>{$affectedRows}</info> rows");
        }

        return Command::SUCCESS;
    }
}