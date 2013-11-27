<?php
namespace Tsd\GtdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Tsd\GtdBundle\Entity\Context;
use Tsd\GtdBundle\Form\Type\ContextType;

    /**
     * @Route("/contexts")
     */
class ContextController extends Controller
{

    /**
     * @Route("/add")
     * @Template()
     */
    public function addAction(Request $request){
        $context = new Context();
        $form = $this->createForm(new ContextType, $context);

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($context);
            $em->flush();
            return $this->redirect($this->generateUrl('tsd_gtd_action_index'));
        }
        return array('form' => $form->createView());
    }


    /**
     * @Route("")
     * @Template()
     */
    public function indexAction()
    {
        $contexts = $this->getDoctrine()->getRepository('TsdGtdBundle:Context')->findAll();
        return array('contexts' => $contexts);
    }

}
