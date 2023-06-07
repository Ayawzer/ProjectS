<?php
namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordModel
{
    #[Assert\NotBlank]
    protected $oldPassword;

    #[Assert\Length(min: 3, max: 64)]
    #[Assert\NotBlank]
    protected $newPassword;

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }
}
