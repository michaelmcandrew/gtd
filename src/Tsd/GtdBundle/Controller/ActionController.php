<?php

namespace Tsd\GtdBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Tsd\GtdBundle\Entity\Action;
use Tsd\GtdBundle\Form\Type\ActionType;
/**
 * @Route("/actions")
 */

class ActionController extends Controller{

    /**
     * @Route("")
     * @Template()
     */
    public function indexAction(Request $request){
        $em = $this->getdoctrine()->getmanager();
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select('a')
            ->from('TsdGtdBundle:Action', 'a')
            ->join('a.project', 'p')
            ->andwhere('p.timeframe = :timeframe')
            ->andwhere('a.completed IS NULL')
            ->setParameter('timeframe', $em->getRepository('TsdGtdBundle:Timeframe')->findOneByName('Current')->getId());

        // TODO Once we know how we want to filter / view completed projects, then add this filter.  Might be as simple as status = incomplete,complete,all for now.
        // if($request->get('completed')){//work out a neat way of doing this. We probably want to be able to display incomplete by default (current behaviour) but maybe want to show all as well
        // }else{
        //     $where['completed'] = null;
        // }
        // $projects = $em->getRepository('TsdGtdBundle:Project')->findBy($where);
        $actions = $qb->getQuery()->getResult();
        return array('actions' => $actions);
    }

    /**
     * @Route("/add")
     * @Template()
     */
    public function addAction(Request $request){
        $action = new Action();
        if($request->get('project')){
            $project = $this->getDoctrine()->getRepository('TsdGtdBundle:Project')->find($request->get('project'));
            $action->setProject($project);
        }
        $form = $this->createForm(new ActionType, $action);

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();
            return $this->redirect($this->generateUrl('tsd_gtd_action_index'));
        }
        return array('form' => $form->createView());
    }
    /**
     * @Route("/view/{id}")
     * @Template()
     */
    public function viewAction(Request $request, $id){
        $action = $this->getDoctrine()->getRepository('TsdGtdBundle:Action')->find($id);
        return array('action' => $action);
    }
    /**
     * @Route("/edit/{id}")
     * @Template()
     */
    public function editAction(Request $request, $id){
        $action = $this->getDoctrine()->getRepository('TsdGtdBundle:Action')->find($id);
        $form = $this->createForm(new ActionType, $action);
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();
            return $this->redirect($this->generateUrl('tsd_gtd_action_index'));
        }
        return array('form' => $form->createView());
    }
    /**
     * @Route("/markDone/{id}")
     */
    public function markDoneAction(Request $request, $id){
        $action = $this->getDoctrine()->getRepository('TsdGtdBundle:Action')->find($id);
        $action->setCompleted(New \DateTime);
        $em = $this->getDoctrine()->getManager();
        $em->persist($action);
        $em->flush();
        $link = $this->generateUrl('tsd_gtd_action_view', array('id' => $action->getId()));
        $this->get('session')->getFlashBag()->add( 'notice', "'<a href='{$link}'>{$action->getDescription()}</a>' marked as done");
        return $this->redirect($this->generateUrl('tsd_gtd_action_index'));
    }
    /**
     * @Route("/markNotDone/{id}")
     */
    public function markNotDoneAction(Request $request, $id){
        $action = $this->getDoctrine()->getRepository('TsdGtdBundle:Action')->find($id);
        $action->setCompleted(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($action);
        $em->flush();
        $link = $this->generateUrl('tsd_gtd_action_view', array('id' => $action->getId()));
        $this->get('session')->getFlashBag()->add( 'notice', "'<a href='{$link}'>{$action->getDescription()}</a>' marked as not done");
        return $this->redirect($this->generateUrl('tsd_gtd_action_index'));
    }
}

