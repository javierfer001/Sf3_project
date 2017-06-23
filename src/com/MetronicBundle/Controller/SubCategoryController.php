<?php

namespace com\MetronicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use com\MetronicBundle\Entity\SubCategory;

/**
 * SubCategory controller.
 *
 */
class SubCategoryController extends Controller {

    /**
     * Lists all SubCategory entities.
     *
     */
    public function indexAction(Request $request, $idCategory) {
        $em = $this->getDoctrine()->getManager();

        $SAVE = 'FALSE';
        if ($request->getSession()->has('save')) {
            $SAVE = $request->getSession()->get('save');
        }

        $category = $em->getRepository('MetronicBundle:Category')->find($idCategory);
        $request->getSession()->set('save', 'FALSE');

        return $this->render('MetronicBundle:SubCategory:index.html.twig', array(
                    'SAVE' => $SAVE,
                    'category' => $category,
                    'idCategory' => $idCategory,
        ));
    }

    public function paginationAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageSize = $request->get('length');
        $start = $request->get('start');
        $order = $request->get('order');
        $search = $request->get('search');
        $id = $request->get('idCategory');

        $sort = array('c.subCategory', 'c.subCategoryEs');

        $dql = "SELECT c FROM MetronicBundle:SubCategory c LEFT JOIN c.category cat ";
        $dql.=" WHERE cat.id=" . $id;
        if ($search['value'] != "") {
            $dql.=" AND (c.subCategory LIKE '%" . $search['value'] . "%' OR c.subCategoryEs LIKE '%" . $search['value'] . "%') ";
        }
        $dql .= "  ORDER BY  " . ($sort[$order[0]['column']]) . " " . ($order[0]['dir'] == 'desc' ? "DESC" : "ASC");
        $query = $em->createQuery($dql)
                ->setFirstResult($start)
                ->setMaxResults($pageSize);

        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);

        $object = array();
        $p = new SubCategory();
        foreach ($paginator as $p) {
            $temp = array();
            $temp[] = $p->getSubCategory();
            $temp[] = $p->getSubCategoryEs();
            $url = $this->generateUrl('category_sub_new_metronic', array('idCategory' => $id, 'id' => $p->getId()));
            $temp[] = '<a class="btn btn-success" href="' . $url . '">Edit</a><a class="btn btn-danger" onclick="deleteAction(' . $p->getId() . ')">Delete</a>';
            $object[] = $temp;
        }

        $data = array();
        $data["draw"] = $request->get('draw');
        $data["recordsTotal"] = $paginator->count();
        $data["recordsFiltered"] = $paginator->count();
        $data["data"] = $object;

        return $this->json($data);
    }

    /**
     * Displays a form to create a new SubCategory entity.
     *
     */
    public function newAction(Request $request, $id, $idCategory) {

        $em = $this->getDoctrine()->getManager();
        $SAVE = false;
        $entity = new SubCategory();
        if ($id > 0) {
            $entity = $em->getRepository('MetronicBundle:SubCategory')->find($id);
        }
        $category = $em->getRepository('MetronicBundle:Category')->find($idCategory);
        if ($request->getMethod() == "POST") {

            $entity->setCategory($category);
            $entity->setSubCategory($request->get('category_en'));
            $entity->setSubCategoryEs($request->get('category_es'));
            $em->persist($entity);
            $em->flush();
            $SAVE = true;
            if ($id > 0) {
                $request->getSession()->set('save', 'TRUE');
                return $this->redirect($this->generateUrl('category_sub_metronic', array('idCategory' => $idCategory)));
            }
            $entity = new SubCategory();
        }



        return $this->render('MetronicBundle:SubCategory:new.html.twig', array(
                    'entity' => $entity,
                    'SAVE' => $SAVE,
                    'category' => $category,
                    'idCategory' => $idCategory,
        ));
    }

    /**
     * Deletes a SubCategory entity.
     *
     */
    public function deleteAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $id=$request->get('id');
        
        $entity = $em->getRepository('MetronicBundle:SubCategory')->find($id);

        $em->remove($entity);
        $em->flush();

        return $this->json("OK");
    }

}
