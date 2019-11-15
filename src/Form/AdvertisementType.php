<?php


namespace App\Form;


use App\Entity\Advertisements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AdvertisementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Name
        $builder->add('name');
        //xPosition
        $builder->add('xPosition');
        //yPosition
        $builder->add('yPosition');
        //zPosition
        $builder->add('zPosition');
        //width
        $builder->add('width');
        //height
        $builder->add('height');
        //weight
        $builder->add('weight');
        //externalImage
        $builder->add('externalImage');
        //mediaType
        $builder->add('mediaType');
        //text
        $builder->add('text');
        //isActive
        $builder->add('isActive');
        //state
        $builder->add('state');
        //file
        $builder->add('file', FileType::class, [
            'label' => 'label.file',
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver -> setDefaults([
           'data_class' => Advertisements::class,
           'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}