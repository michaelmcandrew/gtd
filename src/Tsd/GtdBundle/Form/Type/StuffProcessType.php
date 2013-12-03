<?php
namespace Tsd\GtdBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tsd\GtdBundle\Entity\Stuff;

class StuffProcessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', null, array('attr' => array( 'placeholder' => 'Add more stuff to process here')))
            ->add('Add as action', 'submit')
            ->add('Add as project', 'submit')
            ->add('Add as someday', 'submit')
            ->add('Already done', 'submit');
    }

    public function getName()
    {
        return 'stuff';
    }
}
