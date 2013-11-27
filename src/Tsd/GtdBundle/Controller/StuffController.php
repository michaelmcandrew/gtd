<?php
namespace Tsd\GtdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Tsd\GtdBundle\Entity\Stuff;
use Tsd\GtdBundle\Form\Type\StuffType;

    /**
     * @Route("/stuff")
     */
class StuffController extends Controller
{
    /**
     * @Route("/process")
     * @Template()
     */
    public function processAction()
    {
        return array();
    }

    /**
     * @Route("/add")
     * @Template()
     */
    public function addAction(Request $request){
        $stuff = new Stuff();
        $form = $this->createForm(new StuffType, $stuff);

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($stuff);
            $em->flush();
            return $this->redirect($this->generateUrl('tsd_gtd_stuff_index'));
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/Edit")
     * @Template()
     */
    public function EditAction()
    {
    }

    /**
     * @Route("")
     * @Template()
     */
    public function indexAction()
    {
        $stuffs = $this->getDoctrine()->getRepository('TsdGtdBundle:Stuff')->findAll();
        return array('stuffs' => $stuffs);
    }

}
