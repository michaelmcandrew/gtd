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
        $repository = $this->getDoctrine()->getRepository('TsdGtdBundle:Project');
        if($request->get('show')=='all'){
            $repository->findAll();
        }else{
            $projects = $repository->findByCompleted(null);
        }

        $em = $this->getDoctrine()->getManager();
        foreach($projects as $project){
            $project->incompleteActions = $em
                ->getRepository('TsdGtdBundle:Action')
                ->findIncompleteActions($project);
        }
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
        $project = $em->find('TsdGtdBundle:Project', $id);
        $actions = $em->getRepository('TsdGtdBundle:Action')->findIncompleteActions($project);
        return array('project' => $project, 'actions' => $actions);
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
