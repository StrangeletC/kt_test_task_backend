<?php

namespace App\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ElasticsearchClientDeleteIndexCommand
 * @package App\Command
 */
class ElasticsearchClientDeleteIndexCommand extends ElasticsearchClientCommand
{
    /**
     * @inheritDoc
     */
    protected static $defaultName = 'elasticsearchClient:deleteIndex';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setDescription('Delete Elasticsearch indexes');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $elasticsearchClient = $this->getClient();

        $indexesList = $this->getIndexes();

        foreach ($indexesList as $class => $params) {
            $io->writeln("Deleting index for {$class}");

            $params = [
                'index' => $params['indexName'],
            ];

            try {
                $response = $elasticsearchClient->indices()->delete($params);
            } catch (Exception $exception) {
                $io->error($exception->getMessage());

                return Command::FAILURE;
            }

            $io->writeln($this->getElasticResponseMessage($response));
        }

        $io->success('Indexes deleted successfully');

        return Command::SUCCESS;
    }
}
