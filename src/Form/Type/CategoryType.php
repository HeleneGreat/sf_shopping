<?php 

namespace App\Form\Type;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;



class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom du type d'animal",
            ])
            ->add('image', textType::class, [
                'label' => "Lien vers l'image",
            ])
            ->add('Enregistrer', SubmitType::class)
        ;
    }
}