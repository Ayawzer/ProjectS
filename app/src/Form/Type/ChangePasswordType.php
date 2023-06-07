<?php
namespace App\Form\Type;

use App\Model\ChangePasswordModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'oldPassword',
            PasswordType::class,
                [
                    'label' => 'label.old_password',
                ]
            );
        $builder->add(
            'newPassword',
            PasswordType::class,
            [
                'label' => 'label.new_password',
            ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangePasswordModel::class,
        ]);
    }
}
