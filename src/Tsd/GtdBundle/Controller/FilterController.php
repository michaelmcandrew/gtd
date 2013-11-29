<?php
namespace Tsd\GtdBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Tsd\GtdBundle\Entity\Project;
use Tsd\GtdBundle\Form\Type\ProjectType;

/**
 * @Route("/filter")
 */

class FilterController extends Controller{

    /**
     * @Route("/update/{entity}/{name}/{value}")
     * @Template()
     */
    public function updateAction(Request $request, $entity, $name, $value){
        $session = New Session;
        if(!$sessionEntity = $session->get($entity)){
            $sessionEntity = array();
        }
        if(!count($sessionEntity)){
            $sessionEntity = $this->initialiseFilter();
        }
        if(strpos($name, '.')){
            list($name, $key) = explode('.', $name);
            $sessionEntity['filter'][$name][$key] = $value;
        }else{
        $sessionEntity['filter'][$name] = $value;
        }
        $session->set($entity, $sessionEntity);
        // print_r($entity);
        // print_r($session->get($entity));exit;
        $entity = $session->set($entity, $sessionEntity);
        return $this->redirect($request->headers->get('referer'));
    }

    private function initialiseFilter()
    {
        return array('filter' => array('method' => array(), 'tags' => array()));
    } 
}
