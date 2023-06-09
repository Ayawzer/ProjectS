<?php
/**
 * Task repository.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Task;
use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TaskRepository.
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in configuration files.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE_TASK = 10;
    public const PAGINATOR_ITEMS_PER_PAGE_CATEGORY = 10;
    public const PAGINATOR_ITEMS_PER_PAGE_WALLET = 10;

    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function queryNotAll(array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->queryAll($filters);

        return $queryBuilder;
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(array $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial transaction.{id, createdAt, updatedAt, title, amount}',
                'partial category.{id, title}',
                'partial wallet.{id, title}'

            )
            ->join('transaction.category', 'category')
            ->join('transaction.wallet', 'wallet')
            ->orderBy('transaction.updatedAt', 'DESC');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder          $queryBuilder Query builder
     * @param array<string, object> $filters      Filters array
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['category']) && $filters['category'] instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters['category']);
        }

        return $queryBuilder;
    }

    /**
     * Count tasks by category.
     *
     * @param Category $category Category
     *
     * @return int Number of tasks in category
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByCategory(Category $category): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('transaction.id'))
            ->where('transaction.category = :category')
            ->setParameter(':category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Count tasks by Wallet.
     *
     * @param Wallet $wallet Wallet
     *
     * @return int Number of tasks in wallet
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countByWallet(Wallet $wallet): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('transaction.id'))
            ->where('transaction.wallet = :wallet')
            ->setParameter(':wallet', $wallet)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Save entity.
     *
     * @param Task $task Task entity
     */
    public function save(Task $task): void
    {
        $this->_em->persist($task);
        $this->_em->flush();
    }

    /**
     * Delete entity.
     *
     * @param Task $task Task entity
     */
    public function delete(Task $task): void
    {
        $this->_em->remove($task);
        $this->_em->flush();
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('transaction');
    }

    public function findTransactionsForWalletByDateRange(Wallet $wallet, ?\DateTimeInterface $dateFrom, ?\DateTimeInterface $dateTo)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.wallet = :wallet')
            ->setParameter('wallet', $wallet)
            ->orderBy('t.createdAt', 'DESC');


        if ($dateFrom) {
            $qb->andWhere('t.createdAt >= :dateFrom')
                ->setParameter('dateFrom', $dateFrom);
        }

        if ($dateTo) {
            $qb->andWhere('t.createdAt <= :dateTo')
                ->setParameter('dateTo', $dateTo);
        }

        return $qb->getQuery()->getResult();
    }


}