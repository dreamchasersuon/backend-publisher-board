<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Constraints;

class UserGetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('offset', IntegerType::class, [
                'constraints' => [ new Constraints\NotBlank, new Constraints\PositiveOrZero ]
            ])
            ->add('limit', IntegerType::class, [
                'constraints' => [ new Constraints\NotBlank, new Constraints\PositiveOrZero ]
            ])
        ;
    }
}
