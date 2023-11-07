<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\User;
use App\Entity\Wish;
use DateTime;
use PhpParser\Node\Scalar\String_;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\Clock\now;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $now = new DateTime('now');
        $builder
            ->add('title')
            ->add('description')
/*            ->add('author', HiddenType::class)*/
            ->add('isPublished', HiddenType::class, options: [
                'required' => false
            ])
            ->add('dateCreated', HiddenType::class, options: [
                'required' => false
            ])
            ->add('refCategory', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name'
    ])
            ->add('submit', SubmitType::class);
        $builder->get('dateCreated')
        ->addModelTransformer(new DateTimeToStringTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
