<?php
namespace Tsd\GtdBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class ActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description')
            ->add( 'project', 'entity', array(
                'class' => 'TsdGtdBundle:Project',
                'required' => false,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->where('p.completed IS NULL');
                }
            ))
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'action';
    }
}
