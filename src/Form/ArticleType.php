<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null,['label'=>false,'attr'=>['placeholder'=>'Titre']])
            ->add('article_desc', TextareaType::class,['label'=>false,'attr'=>['placeholder'=>'Description']])
           /* ->add('imageFile',VichImageType::class,[
                'allow_delete' => true,
                'download_uri' => false,
                'label'=>false,
                'attr'=>
                    ['placeholder'=>'Choisir une image',
                        'button_label'=>'Importer']

            ])*/
           ->add('fileFile', VichFileType::class, [
               'required' => false,
               'label' => 'Choisir un article (PDF, image ou vidéo)',
           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
