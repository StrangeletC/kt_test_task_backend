<?php

namespace App\Controller\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserCreateDataPersister
 * @package App\Controller\DataPersister
 */
final class UserCreateDataPersister implements DataPersisterInterface
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
        $data->setStatus(User::USER_STATUS_DEFAULT);

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
