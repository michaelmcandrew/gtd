<?php
namespace Tsd\GtdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Tsd\GtdBundle\Entity\ProjectTag;
use Tsd\GtdBundle\Form\Type\ProjectTagType;

    /**
     * @Route("/projectTags")
     */
class ProjectTagController extends Controller
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
        $projectTag = new ProjectTag();
        $form = $this->createForm(new ProjectTagType, $projectTag);

        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectTag);
            $em->flush();
            return $this->redirect($this->generateUrl('tsd_gtd_project_index'));
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
        $projectTags = $this->getDoctrine()->getRepository('TsdGtdBundle:ProjectTag')->findAll();
        return array('projectTags' => $projectTags);
    }

}
