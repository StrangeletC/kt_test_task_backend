<?php

namespace App\Tests\Feature\Api;

use App\Entity\Task;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

/**
 * Class TaskResourceTest
 * @package App\Tests\Feature\Api
 */
class TaskResourceTest extends BaseApiTest
{
    use RefreshDatabaseTrait;

    private int $taskFixturesCount = 20;

    public function testGetCollection(): void
    {
        $route = '/api/tasks';

        $response = static::createClient()->request('GET', $route);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains(
            [
                '@context' => '/api/contexts/Task',
                '@id' => '/api/tasks',
                '@type' => 'hydra:Collection',
                'hydra:member' => [],
                'hydra:totalItems' => $this->taskFixturesCount,
            ]
        );

        self::assertCount($this->taskFixturesCount, $response->toArray()['hydra:member']);
        self::assertMatchesResourceCollectionJsonSchema(Task::class);
    }

    public function testGetOneItem(): void
    {
        $task = $this->getTaskFixture();

        $route = "/api/task/{$task->getId()}";

        static::createClient()->request('GET', $route);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains(
            [
                '@context' => '/api/contexts/Task',
                '@id' => "/api/task/{$task->getId()}",
                '@type' => 'Task',
                'id' => $task->getId(),
            ]
        );

        self::assertMatchesResourceCollectionJsonSchema(Task::class);
    }

    /**
     * @return Task
     */
    private function getTaskFixture(): Task
    {
        $user = $this->getUserFixture();

        return $user->getTasks()->first();
    }
}
