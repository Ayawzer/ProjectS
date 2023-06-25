<?php
/**
 * Change password service interface.
 */

namespace App\Service;

use App\Entity\User;
use App\Model\ChangePasswordModel;

/**
 * Interface ChangePasswordServiceInterface.
 */
interface ChangePasswordServiceInterface
{
    /**
     * Change Password.
     *
     * @param User                $user                User
     * @param ChangePasswordModel $changePasswordModel Change Password Model
     */
    public function changePassword(User $user, ChangePasswordModel $changePasswordModel): void;
}
