<?php
namespace Tsd\GtdBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            'textarea',
            array(
                'attr' => array(
                    'autofocus'=>true
                )
            )
        )->add(
            'notes',
            'textarea', array(
                'attr' => array(
                    'rows'=>18
                ),    
                'required' => false
            )
        )->add(
            'projectTags',
            'entity',
            array(
                'class' => 'TsdGtdBundle:ProjectTag',
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.name', 'ASC');
                }
            )
        )->add(
            'timeframe'
        )->add(
            'save',
            'submit'
        )->add(
            'save and new',
            'submit'
        );
    }

    public function getName()
    {
        return 'project';
    }
}
