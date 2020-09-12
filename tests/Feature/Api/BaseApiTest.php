<?php

namespace App\Tests\Feature\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;

/**
 * Class BaseApiTest
 * @package App\Tests\Feature\Api
 */
abstract class BaseApiTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        static::bootKernel();
    }

    /**
     * @return object|null
     */
    protected function getDoctrineContainer()
    {
        return static::$container->get('doctrine');
    }

    /**
     * @return User|null
     */
    protected function getUserFixture(): ?User
    {
        return $this->getDoctrineContainer()
            ->getRepository(User::class)
            ->findOneBy(['username' => 'ivan']);
    }
}
