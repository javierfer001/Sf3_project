<?php

namespace com\BackEndBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use com\BackEndBundle\Entity\Category;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller {

    /**
     * Lists all Category entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackEndBundle:Category')->findAll();

        return $this->render('BackEndBundle:Category:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Displays a form to create a new Category entity.
     *
     */
    public function newAction(Request $request, $id, $name) {
        $em = $this->getDoctrine()->getManager();
        $saveOK = false;
        $entity = new Category();
        if ($id > 0) {

            $entity = $em->getRepository('BackEndBundle:Category')->find($id);
        }

        if ($request->getMethod() == "POST") {

            $entity->setCategory($request->get('category_en'));
            $entity->setCategoryEs($request->get('category_es'));
            $em->persist($entity);
            $em->flush();
            $saveOK = true;
            if($id>0){
                return $this->redirect($this->generateUrl("category"));
            }
        }


        return $this->render('BackEndBundle:Category:new.html.twig', array(
                    'entity' => $entity,
                    'saveOK' => $saveOK
        ));
    }

    /**
     * Deletes a Category entity.
     *
     */
    public function deleteAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BackEndBundle:Category')->find($id);

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('category'));
    }

}
