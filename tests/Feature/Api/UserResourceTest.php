<?php

namespace App\Tests\Feature\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserResourceTest
 * @package App\Tests\Feature\Api
 */
class UserResourceTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testGetOneItem(): void
    {
        $client = static::createClient();

        $iri = $this->findIriBy(User::class, ['username' => 'ivan']);

        $client->request('GET', $iri);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains(
            [
                '@context' => '/api/contexts/User',
                '@id' => $iri,
                '@type' => 'User',
                'username' => 'ivan',
                'firstName' => 'Ivan',
                'lastName' => 'Ivanov',
                'email' => 'ivan@example.com'
            ]
        );

        self::assertMatchesResourceCollectionJsonSchema(User::class);
    }

    public function testCreate(): void
    {
        $uniquePostfix = $this->createUniquePostfix();

        $newUser = [
            'username' => "steve_{$uniquePostfix}",
            'firstName' => 'Steve',
            'lastName' => 'Mops',
            'email' => "stevemops{$uniquePostfix}@example.com",
            'password' => 'qwerty'
        ];

        $route = '/api/users';

        static::createClient()->request(
            'POST',
            $route,
            [
                'json' => $newUser
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains(
            [
                '@context' => '/api/contexts/User',
                '@type' => 'User',
                'username' => $newUser['username'],
                'firstName' => $newUser['firstName'],
                'lastName' => $newUser['lastName'],
                'email' => $newUser['email']
            ]
        );

        self::assertMatchesResourceItemJsonSchema(User::class);
    }

    public function testCreateInvalidUser(): void
    {
        $route = '/api/users';

        $invalidUser = [
            'firstName' => 'Steve',
            'lastName' => 'Mops',
            'email' => "stevemops@example.com",
            'password' => 'qwerty'
        ];

        static::createClient()->request(
            'POST',
            $route,
            [
                'json' => $invalidUser
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains(
            [
                '@context' => '/api/contexts/ConstraintViolationList',
                '@type' => 'ConstraintViolationList',
                'hydra:title' => 'An error occurred',
                'hydra:description' => 'username: This value should not be blank.',
                'violations' => [
                    [
                        'propertyPath' => 'username',
                        'message' => 'This value should not be blank.',
                    ],
                ],
            ],
        );
    }

    public function testUpdateUser(): void
    {
        $client = static::createClient();

        $iri = $this->findIriBy(User::class, ['username' => 'ivan']);

        $updatedUser = [
            'firstName' => 'Bob'
        ];

        $r =$client->request(
            'PUT',
            $iri,
            [
                'json' => $updatedUser
            ]
        );

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => $iri,
            '@type' => 'User',
            'firstName' => $updatedUser['firstName'],
        ]);
    }

    public function testDeleteUser(): void
    {
        $client = static::createClient();

        $iri = $this->findIriBy(User::class, ['username' => 'ivan']);

        $client->request('DELETE', $iri);

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        self::assertNull(
            static::$container
                ->get('doctrine')
                ->getRepository(User::class)
                ->findOneBy(['username' => 'ivan'])
        );
    }

    /**
     * @return string
     */
    private function createUniquePostfix(): string
    {
        return uniqid('', false);
    }
}
