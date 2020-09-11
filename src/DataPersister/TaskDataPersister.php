<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Task;
use App\Service\DateTimeHelper;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TaskDataPersister
 * @package App\DataPersister
 */
class TaskDataPersister implements DataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var DateTimeHelper
     */
    private DateTimeHelper $dateTimeHelper;

    /**
     * TaskDataPersister constructor.
     * @param EntityManagerInterface $entityManager
     * @param DateTimeHelper $dateTimeHelper
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        DateTimeHelper $dateTimeHelper
    ) {
        $this->entityManager = $entityManager;
        $this->dateTimeHelper = $dateTimeHelper;
    }

    /**
     * @inheritDoc
     */
    public function supports($data): bool
    {
        return $data instanceof Task;
    }

    /**
     * @param Task $data
     * @inheritDoc
     */
    public function persist($data)
    {
        $data->setCreatedAt($this->dateTimeHelper->getCurrentDateTime());
        $data->setComplete(false);

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    /**
     * @param Task $data
     * @inheritDoc
     */
    public function remove($data)
    {
        $data->setDeletedAt($this->dateTimeHelper->getCurrentDateTime());

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
}
