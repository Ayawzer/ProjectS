<?php
/**
 * Wallet service.
 */

namespace App\Service;


use App\Entity\Wallet;
use App\Repository\WalletRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class WalletService implements WalletServiceInterface
{
    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Wallet repository.
     */
    private WalletRepository $walletRepository;

    private TaskRepository $taskRepository;

    /**
     * Constructor.
     *
     * @param WalletRepository   $walletRepository   Wallet repository
     * @param PaginatorInterface $paginator          Paginator
     * @param TaskRepository     $taskRepository     Task repository
     */
    public function __construct(WalletRepository $walletRepository, PaginatorInterface $paginator, TaskRepository $taskRepository)
    {
        $this->walletRepository = $walletRepository;
        $this->paginator = $paginator;
        $this->taskRepository = $taskRepository;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->walletRepository->queryAll(),
            $page,
            TaskRepository::PAGINATOR_ITEMS_PER_PAGE_WALLET
        );
    }

    /**
     * Can Wallet be deleted?
     *
     * @param Wallet $wallet Wallet entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Wallet $wallet): bool
    {
        try {
            $result = $this->taskRepository->countByWallet($wallet);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException) {
            return false;
        }
    }

    public function canAcceptTransaction(Wallet $wallet, float $transactionAmount, ?float $originalTransactionAmount = null): bool
    {
        $balance = $wallet->getBalance();

        // If originalTransactionAmount is provided, add it back to the balance
        if ($originalTransactionAmount !== null) {
            $balance += abs($originalTransactionAmount);
        }

        // Only check balance if the transaction amount is negative (a withdrawal).
        if ($transactionAmount < 0) {
            return ($balance - abs($transactionAmount)) >= 0;
        }

        // If the transaction amount is positive (a deposit), it's always acceptable.
        return true;
    }


    /**
     * Update the balance of a wallet based on a transaction amount.
     *
     * @param Wallet $wallet The wallet to update.
     * @param float $amount The amount of the transaction.
     */
    public function updateBalance(Wallet $wallet, float $amount): void
    {
        $wallet->setBalance($wallet->getBalance() + $amount);
        $this->walletRepository->save($wallet);
    }

    /**
     * Save entity.
     *
     * @param Wallet $wallet Wallet entity
     */
    public function save(Wallet $wallet): void
    {
        $this->walletRepository->save($wallet);
    }

    /**
     * Delete entity.
     *
     * @param Wallet $wallet Wallet entity
     */
    public function delete(Wallet $wallet): void
    {
        if (!null == $wallet->getId()) {
            $this->walletRepository->delete($wallet);
        }
    }
}