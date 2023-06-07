<?php
/**
 * Task entity.
*/

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class Task.
 *
 * @psalm-suppress MissingConstructor
 *
 */
#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table(name: 'transactions')]
class Task
{
    /**
     * Primary key.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Created at.
     *
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?DateTimeImmutable $createdAt;

    /**
     * Updated at.
     *
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?DateTimeImmutable $updatedAt;

    /**
     * Title.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $title;

    /**
     * Category.
     *
     * @var Category
     */
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "transactions",fetch: "EXTRA_LAZY")]
    #[Assert\NotBlank]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\NotBlank]
    private ?string $amount = null;

    #[ORM\ManyToOne(targetEntity: Wallet::class, inversedBy: "transactions", fetch: "EXTRA_LAZY")]
    #[Assert\NotBlank]
    #[ORM\JoinColumn(nullable: false)]
    private ?Wallet $wallet = null;

    #[ORM\Column(type: Types::FLOAT)]
    private $balanceAfterTransaction;

    public function setBalanceAfterTransaction($balanceAfterTransaction): self
    {
        $this->balanceAfterTransaction = $balanceAfterTransaction;

        return $this;
    }

    public function getBalanceAfterTransaction(): ?float
    {
        return $this->balanceAfterTransaction;
    }

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for created at.
     *
     * @return DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

//    /**
//     * Setter for created at.
//     *
//     * @param DateTimeImmutable|null $createdAt Created at
//     */
//    public function setCreatedAt(?DateTimeImmutable $createdAt): void
//    {
//        $this->createdAt = $createdAt;
//    }

    /**
     * Getter for updated at.
     *
     * @return DateTimeImmutable|null Updated at
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

//    /**
//     * Setter for updated at.
//     *
//     * @param DateTimeImmutable|null $updatedAt Updated at
//     */
//    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
//    {
//        $this->updatedAt = $updatedAt;
//    }

    /**
     * Getter for title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string|null $title Title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for category
     *
     * @return Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for category
     *
     * @param Category|null $category Category
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }
}

