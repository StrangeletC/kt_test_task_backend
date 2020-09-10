<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"user"}},
 *     denormalizationContext={"groups"={"user"}},
 *     itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/user/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "path"="/user/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *     },
 *     collectionOperations={}
 * )
 */
class User implements UserInterface
{
    public const USER_STATUS_ACTIVE = 0;
    public const USER_STATUS_INACTIVE = 1;

    public const USER_STATUS_DEFAULT = self::USER_STATUS_ACTIVE;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
     * @Groups({"user"})
     */
    protected string $username;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     min = 2,
     *     max = 255,
     * )
     *
     * @Groups({"user"})
     */
    protected $firstName = null;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     min = 2,
     *     max = 255,
     * )
     *
     * @Groups({"user"})
     */
    protected $lastName = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     *
     * @Groups({"user"})
     */
    private string $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     * @Groups({"user"})
     */
    private int $status;

    /**
     * @var Collection|Task[]
     *
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="user_id")
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getSalt(): string
    {
        return 'salt';
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials(): void
    {
        // Unnecessary for this demo, but need to implement
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     * @return $this
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     * @return $this
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * @param Task $task
     * @return $this
     */
    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setUser($this);
        }

        return $this;
    }

    /**
     * @param Task $task
     * @return $this
     */
    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
    }
}
