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
            ->leftJoin('p.actions', 'a', 'WITH', 'a.completed IS NULL')
            ->andwhere('p.timeframe = :timeframe')
            ->andwhere('p.completed IS NULL') ;

        // add context filters
        $session = New Session;
        $filter = $session->get('project')['filter'];

        if($filter['method']=='OR'){
            $orX = $qb->expr()->orX();
            $qb->join('p.projectTags', 'c');
            foreach($filter['tags'] as $tag => $on){
                if($on){
                    $orX->add( $qb->expr()->orX($qb->expr()->eq('c.id', $tag)));
                }
            }
            $qb->andWhere($orX);
        }elseif($filter['method']=='AND'){
            foreach($filter['tags'] as $tag => $on){
                if($on){
                    $qb->join('p.projectTags', 'c'.$tag, 'WITH', $qb->expr()->eq('c'.$tag, $tag));
                }
            }
        }

        if($timeframe = $em->getRepository('TsdGtdBundle:Timeframe')->findOneByName($request->get('timeframe'))){
            $qb->setParameter('timeframe', $timeframe->getId());
        }else{
            $qb->setParameter('timeframe', $em->getRepository('TsdGtdBundle:Timeframe')->findOneByName('Current')->getId());
        }
        echo $qb->getDql();
        $projects = $qb->getQuery()->getResult();
        $tags = $em->getRepository('TsdGtdBundle:ProjectTag')->findAll();
        return array('projects' => $projects, 'tags' => $tags);
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
            $link = $this->generateUrl('tsd_gtd_project_view', array('id' => $project->getId()));
            $this->get('session')->getFlashBag()->add( 'notice', "'<a href='{$link}'>{$project->getName()}</a>' added.");
            if ($form->get('save and new')->isClicked()) {
                return $this->redirect($this->generateUrl('tsd_gtd_project_add'));
            }
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
        $this->get('session')->getFlashBag()->add( 'notice', "'<a href='{$link}'>{$project->getName()}</a>' marked as done.");
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
