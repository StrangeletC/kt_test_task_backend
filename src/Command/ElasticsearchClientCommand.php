<?php

namespace App\Command;

use App\Entity\Task;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Symfony\Component\Console\Command\Command;

/**
 * Class ElasticsearchClientCommand
 * @package App\Command
 */
abstract class ElasticsearchClientCommand extends Command
{
    // TODO: Unfinished and must placed in config
    private array $entitiesIndexes = [
        Task::class => [
            'indexName' => 'task',
            'indexBody' => [
                'mappings' => [
                    'properties' => [
                        'id' => [
                            'type' => 'keyword',
                        ],
                        'title' => [
                            'type' => 'keyword',
                        ],
                        'description' => [
                            'type' => 'keyword',
                        ],
                        'complete' => [
                            'type' => 'boolean'
                        ],
                        'created_at' => [
                            'type' => 'date',
                        ],
                        'deleted_at' => [
                            'type' => 'date',
                        ]
                    ],
                ],
            ],
        ],
    ];

    /**
     * @return array
     */
    protected function getIndexes(): array
    {
        return $this->entitiesIndexes;
    }

    /**
     * @return Client
     */
    protected function getClient(): Client
    {
        return ClientBuilder::create()->build();
    }

    /**
     * @param array $response
     * @return string
     */
    protected function getElasticResponseMessage(array $response): string
    {
        $message = [
            'Elasticsearch says:' => $response
        ];

        return json_encode($message, JSON_UNESCAPED_UNICODE);
    }
}
