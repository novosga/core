<?php

declare(strict_types=1);

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Form;

use Novosga\Entity\ClienteInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Length;

class ClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nome', TextType::class, [
                'label' => 'label.name',
                'constraints' => [
                    new NotNull(),
                    new Length([ 'min' => 3 ]),
                ],
            ])
            ->add('documento', TextType::class, [
                'label' => 'label.customer_id',
                'constraints' => [
                    new NotNull(),
                    new Length([ 'min' => 3 ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email',
                'required' => false,
                'constraints' => [
                    new Length([ 'max' => 80 ]),
                ],
            ])
            ->add('telefone', TextType::class, [
                'label' => 'label.phone',
                'required' => false,
                'constraints' => [
                    new Length([ 'max' => 25 ]),
                ],
            ])
            ->add('genero', ChoiceType::class, [
                'label' => 'label.gender',
                'required' => false,
                'placeholder' => '',
                'choices' => [
                    'label.gender.male' => 'M',
                    'label.gender.female' => 'F',
                    'label.gender.unknown' => 'O',
                ],
            ])
            ->add('observacao', TextareaType::class, [
                'label' => 'label.notes',
                'required' => false,
                'attr' => [
                    'rows' => 6,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => ClienteInterface::class,
        ));
    }
}
