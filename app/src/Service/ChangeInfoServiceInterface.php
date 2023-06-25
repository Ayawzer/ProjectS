<?php
/**
 * Change information service interface.
 */

namespace App\Service;

use App\Entity\User;
use App\Model\ChangeInfoModel;

/**
 * Interface ChangeInfoServiceInterface.
 */
interface ChangeInfoServiceInterface
{
    /**
     * Change Information.
     *
     * @param User            $user            User
     * @param ChangeInfoModel $changeInfoModel Change Info Model
     */
    public function changeInfo(User $user, ChangeInfoModel $changeInfoModel): void;
}
