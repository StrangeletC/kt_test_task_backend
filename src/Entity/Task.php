<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TaskRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"task:read"}},
 *     denormalizationContext={"groups"={"task:write"}},
 *     itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/task/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "path"="/task/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *          "update"={
 *              "method"="PUT",
 *              "path"="/task/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *     },
 *     collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/tasks",
 *          },
 *          "create"={
 *              "method"="POST",
 *              "path"="/tasks",
 *          },
 *     },
 * ),
 * @ApiFilter(
 *     OrderFilter::class,
 *     properties={"id", "title", "description", "complete"},
 *     arguments={"orderParameterName"="order"}
 * )
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={"title": "description"}
 * )
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     *
     * @Groups({"task:read"})
     */
    private int $id;

    /**
     * @var User|null $user
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @Groups({"task:read", "task:write"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"task:read", "task:write"})
     *
     * @ApiFilter(SearchFilter::class, strategy="ipartial")
     */
    private string $title;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups({"task:read", "task:write"})
     *
     * @ApiFilter(SearchFilter::class, strategy="ipartial")
     */
    private $description = null;

    /**
     * @ORM\Column(type="boolean", length=255)
     *
     * @Groups({"task:read"})
     */
    private bool $complete;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"task:read"})
     */
    private DateTimeInterface $createdAt;

    /**
     * @var DateTimeInterface|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"task:read"})
     */
    private $deletedAt = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return bool
     */
    public function getComplete(): bool
    {
        return $this->complete;
    }

    /**
     * @param string $complete
     * @return $this
     */
    public function setComplete(string $complete): self
    {
        $this->complete = $complete;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    /**
     * @param DateTimeInterface|null $deletedAt
     * @return $this
     */
    public function setDeletedAt(?DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
