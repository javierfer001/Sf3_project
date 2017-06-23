<?php

namespace com\MetronicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use com\MetronicBundle\Entity\Clasification;

/**
 * Clasification controller.
 *
 */
class ClasificationController extends Controller {

    /**
     * Lists all Clasification entities.
     *
     */
    public function indexAction(Request $request) {

        $SAVE = 'FALSE';
        if ($request->getSession()->has('save')) {
            $SAVE = $request->getSession()->get('save');
        }

        $request->getSession()->set('save', 'FALSE');

        return $this->render('MetronicBundle:clasification:index.html.twig', array('SAVE' => $SAVE));
    }

    public function paginationAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageSize = $request->get('length');
        $start = $request->get('start');
        $order = $request->get('order');
        $search = $request->get('search');
        $sort = array('c.order', 'c.clasification', 'c.clasificationEs');

        $dql = "SELECT c FROM MetronicBundle:Clasification c ";
        if ($search['value'] != "") {
            $dql.=" WHERE c.clasficacion LIKE '%" . $search['value'] . "%' OR c.clasificacionEs LIKE '%" . $search['value'] . "%' ";
        }
        $dql .= "  ORDER BY  " . ($sort[$order[0]['column']]) . " " . ($order[0]['dir'] == 'desc' ? "DESC" : "ASC");
        $query = $em->createQuery($dql)
                ->setFirstResult($start)
                ->setMaxResults($pageSize);

        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);

        $object = array();
        $p = new Clasification();
        foreach ($paginator as $p) {
            $temp = array();
            $temp[] = $p->getOrder();
            $temp[] = $p->getClasification();
            $temp[] = $p->getClasificationEs();
            $url=  $this->generateUrl('clasification_new_metronic',array('id'=>$p->getId()));
            $urlQ=  $this->generateUrl('question_index_metronic',array('id_clasification'=>$p->getId()));
            $temp[] =   '<ul>
                            <a class="btn btn-warning" href="'.$urlQ.'">Show Question</a>
                            <a class="btn btn-success" href="'.$url.'">edit</a>
                            <a class="btn btn-danger" onclick="deleteAction('.$p->getId().')">Delete</a>
                        </ul>';
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

        $entity = new Clasification();
        if ($id > 0) {
            $entity = $em->getRepository('MetronicBundle:Clasification')->find($id);
        }
        $clasification = $em->getRepository('MetronicBundle:Clasification')->getClasificationOrder();
        $saveOK = 'FALSE';
        if ($request->getMethod() == "POST") {

            $entity->setOrder($request->get('order'));
            $entity->setClasification($request->get('clasification'));
            $entity->setClasificationEs($request->get('clasification_es'));
            $em->persist($entity);
            $em->flush();

            $saveOK = 'TRUE';
            $request->getSession()->set('save', 'TRUE');
            return $this->redirect($this->generateUrl("clasification_index_metronic"));
        }

        return $this->render('MetronicBundle:clasification:new.html.twig', array(
                    'entity' => $entity,
                    'SAVE' => $saveOK,
                    'clasification' => $clasification,
        ));
    }
    
    public function deleteAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $entity = $em->getRepository('BackEndBundle:Clasification')->find($id);
        if($entity !=null){
            $em->remove($entity);
            $em->flush();
        }
        return $this->json("OK");
    }

}
