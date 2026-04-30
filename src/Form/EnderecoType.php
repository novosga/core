<?php

declare(strict_types=1);

/*
 * This file is part of the NovoSGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Form;

use Novosga\Entity\EnderecoInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EnderecoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pais', CountryType::class, [
                'placeholder' => '',
                'label' => 'label.endereco.pais',
                'constraints' => [
                    new Length([ 'max' => 2 ]),
                ],
                'preferred_choices' => [
                    'BR',
                    'AR',
                    'PY',
                    'US',
                ],
            ])
            ->add('cep', TextType::class, [
                'label' => 'label.endereco.cep',
                'required' => false,
                'constraints' => [
                    new Length([ 'max' => 25 ]),
                ],
            ])
            ->add('estado', TextType::class, [
                'label' => 'label.endereco.estado',
                'required' => false,
                'constraints' => [
                    new Length([ 'max' => 3 ]),
                ],
            ])
            ->add('cidade', TextType::class, [
                'label' => 'label.endereco.cidade',
                'required' => false,
                'constraints' => [
                    new Length(max: 30),
                ],
            ])
            ->add('logradouro', TextType::class, [
                'label' => 'label.endereco.logradouro',
                'required' => false,
                'constraints' => [
                    new Length(max: 60),
                ],
            ])
            ->add('numero', TextType::class, [
                'label' => 'label.endereco.numero',
                'required' => false,
                'constraints' => [
                    new Length(max: 10),
                ],
            ])
            ->add('complemento', TextType::class, [
                'label' => 'label.endereco.complemento',
                'required' => false,
                'constraints' => [
                    new Length(max: 15),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EnderecoInterface::class,
        ]);
    }
}
