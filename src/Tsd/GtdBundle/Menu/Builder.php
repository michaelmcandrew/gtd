<?php
namespace Tsd\GtdBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(array('class' => 'nav navbar-nav'));

        $menu->addChild('Actions', array('route' => 'tsd_gtd_action_index'));
        // $menu['Actions']->addChild('Add action', array( 'route' => 'tsd_gtd_action_add'));
        $menu->addChild('Projects', array('route' => 'tsd_gtd_project_index'));
        $menu->addChild('Someday', array('route' => 'tsd_gtd_project_index', 'routeParameters' => array('timeframe' => 'someday')));
        // $menu['Stuff']->addChild('Add stuff', array('route' => 'tsd_gtd_stuff_add'));
        // $menu['Stuff']->addChild('Process stuff', array('route' => 'tsd_gtd_stuff_process'));
        // $admin = $menu->addChild('Admin', array('route' => 'tsd_gtd_context_add'));
        // $admin->addChild('Add context', array('route' => 'tsd_gtd_context_add'));
        // $admin->addChild('Add project tag', array('route' => 'tsd_gtd_projecttag_add'));

        $menu->addChild('Add context', array('route' => 'tsd_gtd_context_add'));
        $menu->addChild('Add project tag', array('route' => 'tsd_gtd_projecttag_add'));
        // $menu['Projects']->addChild('Add project', array( 'route' => 'tsd_gtd_project_add'));
        // $menu['Projects']->addChild('Show all', array( 'route' => 'tsd_gtd_project_index', 'routeParameters' => array('status' => 'all')));
        $menu->addChild('Stuff', array('route' => 'tsd_gtd_stuff_index'));
        
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        return $menu;
    }
}
