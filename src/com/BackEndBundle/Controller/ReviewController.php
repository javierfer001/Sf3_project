<?php

namespace com\BackEndBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use com\BackEndBundle\Entity\Review;
/**
 * Review controller.
 *
 */
class ReviewController extends Controller
{
    /**
     * Lists all Review entities.
     *
     */
    public function indexAction($id_prod)
    {
        $em = $this->getDoctrine()->getManager();

        $reviews = $em->getRepository('BackEndBundle:Review')->findAll();

        return $this->render('BackEndBundle:review:index.html.twig', array(
            'id_prod' => $id_prod,
            'reviews' => $reviews,
        ));
    }

    /**
     * Creates a new Review entity.
     *
     */
    public function newAction(Request $request)
    {
        $review = new Review();
        return $this->render('BackEndBundle:review:new.html.twig', array(
            'entity' => $review,
        ));
    }


    /**
     * Deletes a Review entity.
     *
     */
    public function deleteAction(Request $request, Review $review)
    {
        $form = $this->createDeleteForm($review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($review);
            $em->flush();
        }

        return $this->redirectToRoute('review_index');
    }
}
