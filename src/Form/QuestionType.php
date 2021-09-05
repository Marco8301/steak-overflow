<?php

namespace App\Form;

use App\Entity\Question;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'label_attr' => ['class' => 'my-0'],
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu de la question',
                'label_attr' => ['class' => 'my-0'],
                'help' => 'Soyez précis et exhaustif, plus vous donnerez d\'informations, plus les réponses seront pertinentes',
                'help_attr' => ['class' => 'my-0']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
