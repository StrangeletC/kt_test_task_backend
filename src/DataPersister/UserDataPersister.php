<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserDataPersister
 * @package App\DataPersister
 */
final class UserDataPersister implements DataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface  $passwordEncoder;

    /**
     * UserCreateDataPersister constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritDoc
     */
    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     * @inheritDoc
     */
    public function persist($data)
    {
        $password = $this->passwordEncoder->encodePassword($data, $data->getPassword());
        $data->setPassword($password);

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    /**
     * @param User $data
     * @inheritDoc
     */
    public function remove($data)
    {
        $tasks = $data->getTasks();

        /** @var Task $task */
        foreach ($tasks as $task) {
            $data->removeTask($task);
        }

        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
