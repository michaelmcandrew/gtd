<?php

namespace Tsd\GtdBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Tsd\GtdBundle\Entity\Project;
use Tsd\GtdBundle\Form\Type\ProjectType;
/**
 * @Route("/projects")
 */

class ProjectController extends Controller{

    /**
     * @Route("")
     * @Template()
     */
    public function indexAction(Request $request){

        $em = $this->getdoctrine()->getmanager();
        $qb = $em->createQueryBuilder();
        $qb->select('p', 'a')
            ->from('TsdGtdBundle:Project', 'p')
            ->leftJoin('p.actions', 'a')
            ->andwhere('p.timeframe = :timeframe')
            ->andwhere('p.completed IS NULL');

        //Filter by timeframe TODO: Try and make this more concise?
        if($timeframe = $em->getRepository('TsdGtdBundle:Timeframe')->findOneByName($request->get('timeframe'))){
            $qb->setParameter('timeframe', $timeframe->getId());
        }else{
            $qb->setParameter('timeframe', $em->getRepository('TsdGtdBundle:Timeframe')->findOneByName('Current')->getId());
        }

        // TODO Once we know how we want to filter / view completed projects, then add this filter.  Might be as simple as status = incomplete,complete,all for now.
        // if($request->get('completed')){//work out a neat way of doing this. We probably want to be able to display incomplete by default (current behaviour) but maybe want to show all as well
        // }else{
        //     $where['completed'] = null;
        // }
        // $projects = $em->getRepository('TsdGtdBundle:Project')->findBy($where);
        $projects = $qb->getQuery()->getResult();
        return array('projects' => $projects);
    }

    /**
     * @Route("/add")
     * @Template()
     */
    public function addAction(Request $request){
        $project = new Project();
        $form = $this->createForm(new ProjectType, $project);

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();
            return $this->redirect($this->generateUrl('tsd_gtd_project_index'));
        }
        return array('form' => $form->createView());
    }
    /**
     * @Route("/view/{id}")
     * @Template()
     */
    public function viewAction(Request $request, $id){
        $em = $this->getdoctrine()->getmanager();
        $qb = $em->createQueryBuilder();
        $qb->select('p', 'a')
            ->from('TsdGtdBundle:Project', 'p')
            ->leftJoin('p.actions', 'a')
            ->andwhere('p.id = :id')
            ->andwhere('a.completed IS NULL')
            ->setParameter('id', $id);
        $project = $qb->getQuery()->getSingleResult();
        return array('project' => $project);
    }
    /**
     * @route("/edit/{id}")
     * @template()
     */
    public function editAction(request $request, $id){
        $project = $this->getdoctrine()->getmanager()->find('TsdGtdBundle:Project', $id);
        $form = $this->createform(new projecttype, $project);
        $form->handlerequest($request);

        if($form->isvalid()){
            $em = $this->getdoctrine()->getmanager();
            $em->persist($project);
            $em->flush();
            return $this->redirect($this->generateurl('tsd_gtd_project_index'));
        }
        return array('form' => $form->createView());
    }
    /**
     * @Route("/markDone/{id}")
     */
    public function markDoneAction(Request $request, $id){
        $project = $this->getdoctrine()->getrepository('TsdGtdBundle:Project')->find($id);
        $project->setCompleted(New \DateTime);
        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();
        $link = $this->generateUrl('tsd_gtd_project_view', array('id' => $project->getId()));
        $this->get('session')->getFlashBag()->add( 'notice', "'<a href='{$link}'>{$project->getName()}</a>' marked as done");
        return $this->redirect($this->generateUrl('tsd_gtd_project_index'));
    }
    /**
     * @Route("/markNotDone/{id}")
     */
    public function markNotDoneAction(Request $request, $id){
        $project = $this->getDoctrine()->getRepository('TsdGtdBundle:Project')->find($id);
        $project->setCompleted(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();
        $link = $this->generateUrl('tsd_gtd_project_view', array('id' => $project->getId()));
        $this->get('session')->getFlashBag()->add( 'notice', "'<a href='{$link}'>{$project->getName()}</a>' marked as not done");
        return $this->redirect($this->generateUrl('tsd_gtd_project_index'));
    }
}
