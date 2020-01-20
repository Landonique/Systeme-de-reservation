<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
            	'label' => 'Nom',
				'required' => false
			])
			->add('firstname', TextType::class, [
				'label' => 'Prénoms',
				'required' => false
			])
			->add('email', EmailType::class, [
				'label' => 'Adresse email'
			])
            ->add('chauffeur', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
				'attr' => [
					'class' => 'custom-control-input'
				],
				'label_attr' => [
					'class' => 'custom-control-label'
				],
            ])
            ->add('agreeTerms', CheckboxType::class, [
            	'label' => "J'accepte les conditions.",
                'mapped' => false,
                'attr' => [
                	'class' => 'custom-control-input'
				],
                'label_attr' => [
                	'class' => 'custom-control-label'
				],
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
				'label' => 'Mot de passe',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
