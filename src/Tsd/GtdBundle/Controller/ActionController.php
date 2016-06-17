<?php
namespace Tsd\GtdBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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
            ->leftjoin('a.project', 'p')
            ->andwhere('p.timeframe = :timeframe OR p.id IS NULL')
            ->andwhere('a.completed IS NULL')
            ->orderBy('a.created')
            ->setParameter('timeframe', $em->getRepository('TsdGtdBundle:Timeframe')->findOneByName('Current')->getId());

        // add context filters
        $session = New Session;
        $filter = $session->get('action')['filter'];

        if($filter['method']=='OR'){
            $orX = $qb->expr()->orX();
            $qb->leftjoin('a.contexts', 'c');
            foreach($filter['tags'] as $tag => $on){
                if($on){
                    $orX->add( $qb->expr()->orX($qb->expr()->eq('c.id', $tag)));
                }
            }
            $qb->andWhere($orX);
        }elseif($filter['method']=='AND'){
            foreach($filter['tags'] as $tag => $on){
                if($on){
                    $qb->join('a.contexts', 'c'.$tag, 'WITH', $qb->expr()->eq('c'.$tag, $tag));
                }
            }

        }
        $actions = $qb->getQuery()->getResult();
        $contexts = $em->getRepository('TsdGtdBundle:Context')->findAll();
        return array('actions' => $actions, 'contexts' => $contexts);
    }

    /**
     * @Route("/add")
     * @Route("/add/stuff/{id}", name="tsd_gtd_action_add_stuff")
     * @Template()
     */
    public function addAction(Request $request, $id = null){

        $action = new Action();

        if($request->get('project_id')){
            $templateVariables['project'] = $project = $this->getDoctrine()->getRepository('TsdGtdBundle:Project')->find($request->get('project_id'));
            $action->setProject($project);
        }

        if($id){
            $stuff = $this->getDoctrine()->getRepository('TsdGtdBundle:Stuff')->find($id);
            $action->setDescription($stuff->getDescription());
        }

        $form = $this->createForm(new ActionType, $action);
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();
            $link = $this->generateUrl('tsd_gtd_action_view', array('id' => $action->getId()));
            $this->get('session')->getFlashBag()->add( 'info', "'<a href='{$link}'>{$action->getDescription()}</a>' added.");
            if($id) {
                $stuff->setProcessed(new \DateTime);
                $em->persist($stuff);
                $em->flush();
                return $this->redirect($this->generateUrl('tsd_gtd_stuff_process'));
            }elseif($form->get('save and new')->isClicked()){
                return $this->redirect($this->generateUrl('tsd_gtd_action_add'));
            }
            return $this->redirect($this->generateUrl('tsd_gtd_action_index'));
        }
        
        $templateVariables['form'] = $form->createView();
        return $templateVariables;
        
    }
    /**
     * @Route("/star/{id}")
     */
    public function starAction(Request $request, $id){
        $action = $this->getdoctrine()->getrepository('TsdGtdBundle:Action')->find($id);
        $action->setStarred(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($action);
        $em->flush();
        $link = $this->generateUrl('tsd_gtd_action_view', array('id' => $action->getId()));
        $this->get('session')->getFlashBag()->add( 'info', "'<a href='{$link}'>{$action->getDescription()}</a>' starred.");
        return $this->redirect($this->generateUrl('tsd_gtd_action_index'));
    }
    /**
     * @Route("/unstar/{id}")
     */
    public function unstarAction(Request $request, $id){
        $action = $this->getDoctrine()->getRepository('TsdGtdBundle:Action')->find($id);
        $action->setStarred(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($action);
        $em->flush();
        $link = $this->generateUrl('tsd_gtd_action_view', array('id' => $action->getId()));
        $this->get('session')->getFlashBag()->add( 'info', "'<a href='{$link}'>{$action->getDescription()}</a>' unstarred.");
        return $this->redirect($this->generateUrl('tsd_gtd_action_index'));
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
        $this->get('session')->getFlashBag()->add( 'info', "'<a href='{$link}'>{$action->getDescription()}</a>' marked as done.");
        return $this->redirect($request->headers->get('referer'));
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
        $this->get('session')->getFlashBag()->add( 'info', "'<a href='{$link}'>{$action->getDescription()}</a>' marked as not done");
        return $this->redirect($request->headers->get('referer'));
    }
}
