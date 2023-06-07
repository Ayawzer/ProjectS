<?php
/**
 * Wallet entity.
 */

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Wallet Task.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: WalletRepository::class)]
#[ORM\Table(name: 'wallet')]
class Wallet
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
     * Type.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $type = null;

    /**
     * Balance.
     *
     * @var float|null
     */
    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\Type('float')]
    #[Assert\GreaterThanOrEqual(0.0, message: 'Balance must be zero or greater')]
    private ?float $balance = 0.0;

    /**
     * Title.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $title = null;


    #[ORM\OneToMany(targetEntity: Task::class, fetch: "EXTRA_LAZY", mappedBy: 'wallet' )]
    private $transactions;

    // Initialize the $transactions property as a Doctrine Collection in the Wallet's constructor.

    public function __construct() {
        $this->transactions = new ArrayCollection();
    }

    // Add a getter method for the transactions.

    /**
     * @return Collection|Task[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
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
     * Getter for type.
     *
     * @return string|null Type
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Setter for type.
     *
     * @param string|null $type Type
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Getter for balance.
     *
     * @return string|null Balance
     */
    public function getBalance(): float
    {
        $balance = 0.0;
        foreach ($this->transactions as $transaction) {
            $balance += floatval($transaction->getAmount());
        }

        return $balance;
    }


    /**
     * Setter for balance.
     *
     * @param string|null $balance Balance
     */
    public function setBalance(?string $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}

