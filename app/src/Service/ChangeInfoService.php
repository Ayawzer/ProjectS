<?php
/**
 * Change Information service.
 */

namespace App\Service;

use App\Entity\User;
use App\Model\ChangeInfoModel;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ChangePasswordService.
 */
class ChangeInfoService implements ChangeInfoServiceInterface
{
    /**
     * Entity manager.
     */
    private EntityManagerInterface $entityManager;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager Entity Manager Interface
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Change Information.
     *
     * @param User            $user            User
     * @param ChangeInfoModel $changeInfoModel Change Info Model
     */
    public function changeInfo(User $user, ChangeInfoModel $changeInfoModel): void
    {
        $user->setEmail($changeInfoModel->getEmail());
        $this->entityManager->flush();
    }
}
