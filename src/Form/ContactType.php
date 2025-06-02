<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\ContactMessage;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use App\Entity\User;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user']; 

        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Name is required']),
                    new Assert\Length(['min' => 2, 'minMessage' => 'Name must be at least {{ limit }} characters long']),
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'data' => $user ? $user->getEmail() : '',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Email is required']),
                    new Assert\Email(['message' => 'Invalid email address']),
                ],
            ])

            ->add('message', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\Length([
                        'min' => 6,
                        'max' => 200,
                        'minMessage' => 'Your message must be at least {{ limit }} characters long',
                        'maxMessage' => 'Your message cannot be longer than {{ limit }} characters',
                    ]),
                    new Assert\NotBlank(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactMessage::class,
            'user' => null,
        ]);
    }
}