<?php

namespace com\MetronicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use com\MetronicBundle\Entity\Category;

class CategoryController extends Controller {

    public function indexAction(Request $request) {

        $SAVE = 'FALSE';
        if ($request->getSession()->has('save')) {
            $SAVE = $request->getSession()->get('save');
        }

        $request->getSession()->set('save', 'FALSE');

        return $this->render('MetronicBundle:Category:index.html.twig', array('SAVE' => $SAVE));
    }

    public function paginationAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageSize = $request->get('length');
        $start = $request->get('start');
        $order = $request->get('order');
        $search = $request->get('search');
        $sort = array('c.category', 'c.categoryEs', 'c.category', 'sub.subCategory');

        $dql = "SELECT c,sub FROM MetronicBundle:Category c LEFT JOIN c.subcategories sub ";
        if ($search['value'] != "") {
            $dql.=" WHERE c.category LIKE '%" . $search['value'] . "%' OR c.categoryEs LIKE '%" . $search['value'] . "%' ";
        }
        $dql .= "  ORDER BY  " . ($sort[$order[0]['column']]) . " " . ($order[0]['dir'] == 'desc' ? "DESC" : "ASC");
        $query = $em->createQuery($dql)
                ->setFirstResult($start)
                ->setMaxResults($pageSize);

        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);

        $object = array();
        $p = new Category();
        foreach ($paginator as $p) {
            $temp = array();
            $temp[] = $p->getCategory();
            $temp[] = $p->getCategoryEs();
            $temp[] = $p->stringListSubCategory();
            $url = $this->generateUrl('category_new_metronic', array('id' => $p->getId()));
            $subCat = $this->generateUrl('category_sub_metronic', array('idCategory' => $p->getId()));
            $temp[] = '<a class="btn btn-warning" href="'.$subCat.'">Sub Category</a><a class="btn btn-success" href="' . $url . '">edit</a><a class="btn btn-danger" onclick="deleteAction(' . $p->getId() . ')">Delete</a>';
            $object[] = $temp;
        }

        $data = array();
        $data["draw"] = $request->get('draw');
        $data["recordsTotal"] = $paginator->count();
        $data["recordsFiltered"] = $paginator->count();
        $data["data"] = $object;

        return $this->json($data);
    }

    public function newAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $saveOK = 'FALSE';
        $entity = new Category();
        if ($id > 0) {

            $entity = $em->getRepository('MetronicBundle:Category')->find($id);
        }

        if ($request->getMethod() == "POST") {

            $entity->setCategory($request->get('category_en'));
            $entity->setCategoryEs($request->get('category_es'));
            $em->persist($entity);
            $em->flush();
            $saveOK = 'TRUE';
            if ($id > 0) {
                $request->getSession()->set('save', 'TRUE');
                return $this->redirect($this->generateUrl("category_metronic"));
            }
            return $this->redirect($this->generateUrl("category_metronic"));
        }


        return $this->render('MetronicBundle:Category:new.html.twig', array(
                    'entity' => $entity,
                    'SAVE' => $saveOK
        ));
    }

    public function deleteAction(Request $request) {
        if ($request->getMethod() == "POST") {
            $id = $request->get('id');
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MetronicBundle:Category')->find($id);

            $em->remove($entity);
            $em->flush();

            return $this->json("OK");
        }
    }

}
