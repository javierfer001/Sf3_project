<?php

namespace com\MetronicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use com\MetronicBundle\Entity\Question;

/**
 * Question controller.
 *
 */
class QuestionController extends Controller {

    /**
     * Lists all Question entities.
     *
     */
    public function indexAction(Request $request, $id_clasification) {
        $em = $this->getDoctrine()->getManager();

        $SAVE = 'FALSE';
        if ($request->getSession()->has('save')) {
            $SAVE = $request->getSession()->get('save');
        }

        $clasification = $em->getRepository('MetronicBundle:Clasification')->find($id_clasification);
        $request->getSession()->set('save', 'FALSE');

        return $this->render('MetronicBundle:question:index.html.twig', array(
                    'SAVE' => $SAVE,
                    'clasification' => $clasification,
                    'id_clasification' => $id_clasification,
        ));
    }

    public function paginationAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageSize = $request->get('length');
        $start = $request->get('start');
        $order = $request->get('order');
        $search = $request->get('search');
        $id = $request->get('id_clasification');

        $sort = array('c.question', 'c.questionEs');

        $dql = "SELECT c,cla FROM MetronicBundle:Question c LEFT JOIN c.clasification cla ";
        $dql.=" WHERE cla.id=" . $id;
        if ($search['value'] != "") {
            $dql.=" AND (c.question LIKE '%" . $search['value'] . "%' OR c.questionEs LIKE '%" . $search['value'] . "%') ";
        }
        $dql .= "  ORDER BY  " . ($sort[$order[0]['column']]) . " " . ($order[0]['dir'] == 'desc' ? "DESC" : "ASC");
        $query = $em->createQuery($dql)
                ->setFirstResult($start)
                ->setMaxResults($pageSize);

        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);

        $object = array();
        $p = new Question();
        foreach ($paginator as $p) {
            $temp = array();
            $temp[] = $p->getOrder();
            $temp[] = $p->getQuestion();
            $temp[] = $p->getQuestionEs();
            $temp[] = $p->getResponse();
            $temp[] = $p->getResponseEs();
            $url = $this->generateUrl('question_new_metronic', array('id_clasification' => $id, 'id' => $p->getId()));
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
     * Creates a new Question entity.
     *
     */
    public function newAction(Request $request, $id_clasification, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = new Question();
        if ($id > 0) {
            $entity = $em->getRepository('MetronicBundle:Question')->find($id);
        }
        $clasification = $em->getRepository('MetronicBundle:Clasification')->find($id_clasification);
        $question = $em->getRepository('MetronicBundle:Question')->getQuestionOrder();
        if ($request->getMethod() == 'POST') {
            $entity->setOrder($request->get('order'));
            $entity->setClasification($clasification);
            $entity->setQuestion($request->get('clasification'));
            $entity->setQuestionEs($request->get('clasification_es'));
            $entity->setResponse($request->get('response'));
            $entity->setResponseEs($request->get('response_es'));
            $em->persist($entity);
            $em->flush();
            $request->getSession()->set('save', 'TRUE');
            return $this->redirect($this->generateUrl("question_index_metronic", array('id_clasification' => $id_clasification)));
        }

        return $this->render('MetronicBundle:question:new.html.twig', array(
                    'entity' => $entity,
                    'id_clasification' => $id_clasification,
                    'clasification' => $clasification,
                    'question' => $question,
        ));
    }

    /**
     * Deletes a Question entity.
     *
     */
    public function deleteAction(Request $request) {
        if ($request->getMethod() == "POST") {
            $id = $request->get('id');
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MetronicBundle:Question')->find($id);

            $em->remove($entity);
            $em->flush();

            return $this->json("OK");
        }
    }

}
