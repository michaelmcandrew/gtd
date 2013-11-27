<?php
namespace Tsd\GtdBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Actions', array('route' => 'tsd_gtd_action_index'));
        // $menu['Actions']->addChild('Add action', array( 'route' => 'tsd_gtd_action_add'));
        $menu->addChild('Projects', array('route' => 'tsd_gtd_project_index'));
        $menu->addChild('Someday', array('route' => 'tsd_gtd_project_index', 'routeParameters' => array('timeframe' => 'someday')));
        $menu->addChild('Process stuff', array('route' => 'tsd_gtd_stuff_process'));
        $menu->addChild('Stuff', array('route' => 'tsd_gtd_stuff_index'));
        $menu->addChild('Add stuff', array('route' => 'tsd_gtd_stuff_add'));
        // $menu['Projects']->addChild('Add project', array( 'route' => 'tsd_gtd_project_add'));
        // $menu['Projects']->addChild('Show all', array( 'route' => 'tsd_gtd_project_index', 'routeParameters' => array('status' => 'all')));
        
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        return $menu;
    }
}
