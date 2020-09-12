<?php

namespace App\Tests\Feature\Api;

use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserResourceTest
 * @package App\Tests\Feature\Api
 */
class UserResourceTest extends BaseApiTest
{
    use RefreshDatabaseTrait;

    public function testGetOneItem(): void
    {
        $iri = $this->findIriBy(User::class, ['username' => 'ivan']);

        static::createClient()->request('GET', $iri);

        self::assertResponseIsSuccessful();
        $this->assertCorrectHeaders();

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
        $this->assertCorrectHeaders();

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
        $this->assertCorrectHeaders();

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
        $iri = $this->findIriBy(User::class, ['username' => 'ivan']);

        $updatedUser = [
            'firstName' => 'Bob'
        ];

        static::createClient()->request(
            'PUT',
            $iri,
            [
                'json' => $updatedUser
            ]
        );

        self::assertResponseIsSuccessful();
        $this->assertCorrectHeaders();

        self::assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => $iri,
            '@type' => 'User',
            'firstName' => $updatedUser['firstName'],
        ]);
    }

    public function testDeleteUser(): void
    {
        $iri = $this->findIriBy(User::class, ['username' => 'ivan']);

        static::createClient()->request('DELETE', $iri);

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        self::assertNull($this->getUserFixture());
    }

    /**
     * @return string
     */
    private function createUniquePostfix(): string
    {
        return uniqid('', false);
    }
}
