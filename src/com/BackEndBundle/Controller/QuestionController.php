<?php

namespace com\BackEndBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use com\BackEndBundle\Entity\Question;

/**
 * Question controller.
 *
 */
class QuestionController extends Controller {

    /**
     * Lists all Question entities.
     *
     */
    public function indexAction($id_clasification) {
        $em = $this->getDoctrine()->getManager();

        $questions = $em->getRepository('BackEndBundle:Question')->findBy(array('clasification' => $id_clasification));
        $clasification = $em->getRepository('BackEndBundle:Clasification')->find($id_clasification);

        return $this->render('BackEndBundle:question:index.html.twig', array(
                    'questions' => $questions,
                    'id_clasification' => $id_clasification,
                    'clasification' => $clasification,
        ));
    }

    /**
     * Creates a new Question entity.
     *
     */
    public function newAction(Request $request, $id_clasification, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = new Question();
        if ($id > 0) {
            $entity = $em->getRepository('BackEndBundle:Question')->find($id);
        }
        $clasification = $em->getRepository('BackEndBundle:Clasification')->find($id_clasification);
        $question = $em->getRepository('BackEndBundle:Question')->getQuestionOrder();
        if ($request->getMethod() == 'POST') {
            $entity->setOrder($request->get('order'));
            $entity->setClasification($clasification);
            $entity->setQuestion($request->get('clasification'));
            $entity->setQuestionEs($request->get('clasification_es'));
            $entity->setResponse($request->get('response'));
            $entity->setResponseEs($request->get('response_es'));
             $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl("question_index",array('id_clasification'=>$id_clasification)));
        }

        return $this->render('BackEndBundle:question:new.html.twig', array(
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
    public function deleteAction(Request $request, $id_clasification) {
        $form = $this->createDeleteForm($question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($question);
            $em->flush();
        }

        return $this->redirectToRoute('question_index', array('id_clasification' => $id_clasification));
    }

}
