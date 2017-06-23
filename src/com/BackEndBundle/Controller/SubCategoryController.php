<?php

namespace com\BackEndBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use com\BackEndBundle\Entity\SubCategory;

/**
 * SubCategory controller.
 *
 */
class SubCategoryController extends Controller {

    /**
     * Lists all SubCategory entities.
     *
     */
    public function indexAction($idCategory) {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackEndBundle:SubCategory')->findBy(array('category'=>$idCategory));

        return $this->render('BackEndBundle:SubCategory:index.html.twig', array(
                    'entities' => $entities,
                    'idCategory' => $idCategory,
        ));
    }

    /**
     * Displays a form to create a new SubCategory entity.
     *
     */
    public function newAction(Request $request,$id,$name,$idCategory) {
        
         $em = $this->getDoctrine()->getManager();
        $saveOK = false;
        $entity = new SubCategory();
        if ($id > 0) {
            $entity = $em->getRepository('BackEndBundle:SubCategory')->find($id);
        }

        if ($request->getMethod() == "POST") {
            $category = $em->getRepository('BackEndBundle:Category')->find($idCategory);
            $entity->setCategory($category);
            $entity->setSubCategory($request->get('category_en'));
            $entity->setSubCategoryEs($request->get('category_es'));
            $em->persist($entity);
            $em->flush();
            $saveOK = true;
            if($id>0){
                return $this->redirect($this->generateUrl('subcategory',array('idCategory'=>$idCategory)));
            }
        }

        return $this->render('BackEndBundle:SubCategory:new.html.twig', array(
                    'entity' => $entity,
                    'idCategory' => $idCategory,
                    'saveOK' => $saveOK,
        ));
    }

    /**
     * Deletes a SubCategory entity.
     *
     */
    public function deleteAction(Request $request, $id,$idCategory) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BackEndBundle:SubCategory')->find($id);
        
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('subcategory',array('idCategory'=>$idCategory)));
    }

}
