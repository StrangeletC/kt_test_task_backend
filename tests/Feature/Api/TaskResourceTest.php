<?php

namespace App\Tests\Feature\Api;

use App\Entity\Task;
use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;

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

        self::assertMatchesResourceItemJsonSchema(Task::class);
    }

    public function testCreate(): void
    {
        $user = $this->getUserFixture();

        $userIri = $this->findIriBy(User::class, ['username' => $user->getUsername()]);

        $newTask = [
            'user' => $userIri,
            'title' => 'Task title',
            'description' => 'Task description'
        ];

        $route = '/api/tasks';

        static::createClient()->request(
            'POST',
            $route,
            [
                'json' => $newTask
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains(
            [
                '@context' => '/api/contexts/Task',
                '@type' => 'Task',
                'user' => $newTask['user'],
                'title' => $newTask['title'],
                'description' => $newTask['description'],
                'complete' => false,
            ]
        );

        self::assertMatchesResourceItemJsonSchema(Task::class);
    }

    public function testUpdate(): void
    {
        $task = $this->getTaskFixture();
        $user = $this->getUserFixture();

        $userIri = $this->findIriBy(User::class, ['username' => $user->getUsername()]);
        $taskIri = $this->findIriBy(Task::class, ['id' => $task->getId()]);

        $updatedTask = [
            'title' => 'Another title'
        ];

        static::createClient()->request(
            'PUT',
            $taskIri,
            [
                'json' => $updatedTask
            ]
        );

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains(
            [
                '@context' => '/api/contexts/Task',
                '@id' => $taskIri,
                '@type' => 'Task',
                'id' => $task->getId(),
                'user' => $userIri,
                'title' => $updatedTask['title'],
            ]
        );

        self::assertMatchesResourceItemJsonSchema(Task::class);
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
