<?php

namespace App\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ElasticsearchClientCreateIndexCommand
 * @package App\Command
 */
class ElasticsearchClientCreateIndexCommand extends ElasticsearchClientCommand
{
    /**
     * @inheritDoc
     */
    protected static $defaultName = 'elasticsearchClient:createIndex';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setDescription('Create Elasticsearch indexes');
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
            $io->writeln("Creating index for {$class}");

            $params = [
                'index' => $params['indexName'],
                'body' => $params['indexBody'],
            ];

            try {
                $response = $elasticsearchClient->indices()->create($params);
            } catch (Exception $exception) {
                $io->error($exception->getMessage());

                return Command::FAILURE;
            }

            $io->writeln($this->getElasticResponseMessage($response));
        }

        $io->success('Indexes created successfully');

        return Command::SUCCESS;
    }
}
