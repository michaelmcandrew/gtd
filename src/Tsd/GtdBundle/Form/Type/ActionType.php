<?php
namespace Tsd\GtdBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class ActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description', 'textarea', array(
                'attr' => array('autofocus'=>true)))
            ->add( 'project', 'entity', array(
                'class' => 'TsdGtdBundle:Project',
                'required' => false,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')->where('p.completed IS NULL');
                }))
            ->add('contexts', 'entity', array(
                'class' => 'TsdGtdBundle:Context',
                'required' => true,
                'multiple' => true,
                'expanded' => true))
            ->add('save', 'submit')
            ->add('save and new', 'submit');
    }

    public function getName()
    {
        return 'action';
    }
}
