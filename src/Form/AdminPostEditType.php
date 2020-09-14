<?php


namespace App\Form;


use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminPostEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tittle', TextType::class, [
                'required' => true,
                'label' => 'Заголовок'
            ])
            ->add('overview', TextareaType::class, [
                'required' => true,
                'label' => 'Краткое содержание'
            ])
            ->add('image', TextType::class, [
                'required' => false,
                'label' => 'Картинка'
            ])
            ->add('content', CollectionType::class, [
                'required' => true,
                'label' => 'Статья',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('data_class', Post::class);
        $resolver->setDefault('empty_data', new Post());
    }
}