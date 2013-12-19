<?php
namespace Tsd\GtdBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tsd\GtdBundle\Entity\Stuff;

class StuffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', 'textarea', array(
                'attr' => array('autofocus'=>true)))
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'stuff';
    }
}
