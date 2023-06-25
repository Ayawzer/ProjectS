<?php
/**
 * Change Password service.
 */

namespace App\Service;

use App\Entity\User;
use App\Model\ChangePasswordModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ChangePasswordService.
 */
class ChangePasswordService implements ChangePasswordServiceInterface
{
    /**
     * Entity manager.
     */
    private EntityManagerInterface $entityManager;

    /**
     * User Password Hasher Interface.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface      $entityManager  Entity Manager Interface
     * @param UserPasswordHasherInterface $passwordHasher User Password Hasher Interface
     * @param TranslatorInterface         $translator     Translator Interface
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->translator = $translator;
    }

    /**
     * Change Password.
     *
     * @param User                $user                User
     * @param ChangePasswordModel $changePasswordModel Change Password Model
     */
    public function changePassword(User $user, ChangePasswordModel $changePasswordModel): void
    {
        if (!$this->passwordHasher->isPasswordValid($user, $changePasswordModel->getOldPassword())) {
            throw new \Exception($this->translator->trans('message.password_incorrect'));
        }

        $newPassword = $this->passwordHasher->hashPassword($user, $changePasswordModel->getNewPassword());
        $user->setPassword($newPassword);

        $this->entityManager->flush();
    }
}
