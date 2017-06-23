<?php

namespace com\BackEndBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use com\BackEndBundle\Entity\Images;

/**
 * Images controller.
 *
 */
class ImagesController extends Controller {

    /**
     * Lists all Images entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('BackEndBundle:Images')->findAll();

        return $this->render('BackEndBundle:images:index.html.twig', array(
                    'images' => $images,
        ));
    }

    /**
     * Creates a new Images entity.
     *
     */
    public function newAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $image = new Images();
        if ($id > 0) {
            $image = $em->getRepository('BackEndBundle:Images')->find($id);
        }
      
        if ($request->getMethod() == 'POST') {
            if ($_FILES['file']['name'] != "") {
                $filename = time() . basename($_FILES['file']['name']);
                $dir_subida = __DIR__ . '/../../../../web/producto/';
                $fichero_subido = $dir_subida . $filename;
                $urlOldImg = $image->getUrl();
                if (move_uploaded_file($_FILES['file']['tmp_name'], $fichero_subido)) {
                    $image->setUrl($filename);


                    unlink($dir_subida . $urlOldImg);
                }
            }
            $image->setDescription($request->get('description'));
            $image->setAlt($request->get('alt'));
            $em->persist($image);
            $em->flush();
          //  return $this->redirectToRoute('images_index');
        }
        return $this->render('BackEndBundle:Images:new.html.twig', array(
                    'entity' => $image,
        ));
    }

    /**
     * Deletes a Images entity.
     *
     */
    public function deleteAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('BackEndBundle:Images')->find($id);
        $em->remove($image);
        $em->flush();

        return $this->redirectToRoute('images_index');
    }

}
