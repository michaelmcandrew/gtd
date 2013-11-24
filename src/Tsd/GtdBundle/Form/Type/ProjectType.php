<?php
namespace Tsd\GtdBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('timeframe')
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'project';
    }
}
