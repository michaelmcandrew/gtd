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
            ->add('projectTags', 'entity', array(
                'class' => 'TsdGtdBundle:ProjectTag',
                'required' => true,
                'multiple' => true,
                'expanded' => true))
            ->add('timeframe')
            ->add('save', 'submit')
            ->add('save and new', 'submit');
    }

    public function getName()
    {
        return 'project';
    }
}
