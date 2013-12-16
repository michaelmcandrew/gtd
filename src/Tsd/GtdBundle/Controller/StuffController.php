<?php
namespace Tsd\GtdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Tsd\GtdBundle\Entity\Stuff;
use Tsd\GtdBundle\Form\Type\StuffType;
use Tsd\GtdBundle\Form\Type\StuffProcessType;

/**
 * @Route("/stuff")
 */
class StuffController extends Controller
{
    /**
     * @Route("/process")
     * @Template()
     */
    public function processAction(Request $request)
    {
        if(!$stuff = $this->getDoctrine()->getRepository('TsdGtdBundle:Stuff')->findOneByProcessed(null, array('created' => 'ASC'))){
            $stuff = new stuff;
        }

        // if there is unprocessed stuff in the queue, create a form which asks what kind of thing you want to turn this into
        $form = $this->createForm(new StuffProcessType, $stuff);
        $form->handleRequest($request);
        if($form->isValid()){
            if(!$stuff->getId()){
                $em = $this->getDoctrine()->getManager();
                $stuff->setProcessed(new \DateTime);
                $em->persist($stuff);
                $em->flush();
            }
            if($form->get('Already done')->isClicked()){
                $em = $this->getDoctrine()->getManager();
                $stuff->setProcessed(new \DateTime);
                $em->persist($stuff);
                $em->flush();
                $this->get('session')->getFlashBag()->add( 'info', "'{$stuff->getDescription()}' processed (already done).");
                return $this->redirect($this->generateUrl('tsd_gtd_stuff_process'));
            }elseif($form->get('Add as action')->isClicked()){
                return $this->redirect($this->generateUrl('tsd_gtd_action_add_stuff', array('id' => $stuff->getId())));
            }elseif($form->get('Add as project')->isClicked()){
                return $this->redirect($this->generateUrl('tsd_gtd_project_add_stuff', array('id' => $stuff->getId())));
            }elseif($form->get('Add as someday')->isClicked()){
                return $this->redirect($this->generateUrl('tsd_gtd_project_add_stuff', array('id' => $stuff->getId(), 'timeframe' => 'someday')));
            }
        }
        $templateVariables['form'] = $form->createView();
        return $templateVariables;

}

/**
 * @Route("/add")
 * @Template()
 */
public function addAction(Request $request){
    $stuffs='';
    $stuff = new Stuff();
    $form = $this->createForm(new StuffType, $stuff);

    $form->handleRequest($request);

    if($form->isValid()){
        $em = $this->getDoctrine()->getManager();
        $em->persist($stuff);
        $em->flush();
        if($form->get('save and new')->isClicked()){
            return $this->redirect($this->generateUrl('tsd_gtd_stuff_add'));
        }
        return $this->redirect($this->generateUrl('tsd_gtd_stuff_index'));
    }
    return array('stuffs' => $stuffs, 'form' => $form->createView());
}

/**
 * @Route("")
 * @Template()
 */
public function indexAction(Request $request)
{
    // Get all stuff
    $stuffs = $this->getDoctrine()->getRepository('TsdGtdBundle:Stuff')->findByProcessed(null);

    //Get a form to add new stuff
    $stuff = new Stuff();
    $form = $this->createForm(new StuffType, $stuff);

    $form->handleRequest($request);

    if($form->isValid()){
        $em = $this->getDoctrine()->getManager();
        $em->persist($stuff);
        $em->flush();
        return $this->redirect($this->generateUrl('tsd_gtd_stuff_index'));
    }
    return array('stuffs' => $stuffs, 'form' => $form->createView());
}

}
