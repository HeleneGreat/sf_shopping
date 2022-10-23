<?php 

namespace App\Form\Type;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;



class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de l'animal",
            ])
            ->add('categoryId', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => "Type d'animal",
                ])
            ->add('price', MoneyType::class, [
                'label' => "Prix",
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description",
            ])
            ->add('shipping', MoneyType::class, [
                'label' => "Frais de livraison",
            ])
            ->add('image', textType::class, [
                'label' => "Lien vers l'image",
            ])
            ->add('Enregistrer', SubmitType::class)
        ;
    }
}