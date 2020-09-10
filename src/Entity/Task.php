<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TaskRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"task"}},
 *     denormalizationContext={"groups"={"task"}},
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
 *     },
 *     collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/tasks",
 *          },
 *     },
 * )
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @Groups({"task"})
     */
    private User $user;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"task"})
     */
    private string $title;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups({"task"})
     */
    private $description = null;

    /**
     * @ORM\Column(type="boolean", length=255)
     *
     * @Groups({"task"})
     */
    private bool $complete;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"task"})
     */
    private DateTimeInterface $created_at;

    /**
     * @var DateTimeInterface|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"task"})
     */
    private $deleted_at = null;

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
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): self
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
        return $this->created_at;
    }

    /**
     * @param DateTimeInterface $created_at
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deleted_at;
    }

    /**
     * @param DateTimeInterface $deleted_at
     * @return $this
     */
    public function setDeletedAt(DateTimeInterface $deleted_at): self
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }
}
