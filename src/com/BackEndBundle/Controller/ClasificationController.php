<?php

namespace com\BackEndBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use com\BackEndBundle\Entity\Clasification;

/**
 * Clasification controller.
 *
 */
class ClasificationController extends Controller {

    /**
     * Lists all Clasification entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $clasifications = $em->getRepository('BackEndBundle:Clasification')->findAll();

        return $this->render('BackEndBundle:clasification:index.html.twig', array(
                    'clasifications' => $clasifications,
        ));
    }

    /**
     * Creates a new Clasification entity.
     *
     */
    public function newAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = new Clasification();
        if ($id > 0) {
            $entity = $em->getRepository('BackEndBundle:Clasification')->find($id);
        }
        $clasification = $em->getRepository('BackEndBundle:Clasification')->getClasificationOrder();

        if ($request->getMethod() == "POST") {

            $entity->setOrder($request->get('order'));
            $entity->setClasification($request->get('clasification'));
            $entity->setClasificationEs($request->get('clasification_es'));
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl("clasification_index"));
        }
        return $this->render('BackEndBundle:clasification:new.html.twig', array(
                    'entity' => $entity,
                    'clasification' => $clasification,
        ));
    }

    /**
     * Deletes a Clasification entity.
     *
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackEndBundle:Clasification')->find($id);
        if($entity !=null){
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirectToRoute('clasification_index');
    }

}
