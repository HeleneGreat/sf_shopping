<?php 

namespace App\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;



class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('content', TextareaType::class, [
                'required' => false,
                'label' => 'Ajouter un commentaire',
            ])
            ->add('Publier', SubmitType::class)
        ;
    }
}