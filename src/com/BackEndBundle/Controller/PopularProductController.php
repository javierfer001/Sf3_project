<?php

namespace com\BackEndBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use com\BackEndBundle\Entity\PopularProduct;

/**
 * PopularProduct controller.
 *
 */
class PopularProductController extends Controller {

    /**
     * Lists all PopularProduct entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $popularProducts = $em->getRepository('BackEndBundle:PopularProduct')->findAll();

        return $this->render('BackEndBundle:popularproduct:index.html.twig', array(
                    'popularProducts' => $popularProducts,
        ));
    }

    /**
     * Displays a form to edit an existing PopularProduct entity.
     *
     */
    public function newAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $popularProduct = new PopularProduct();

        if ($id > 0) {
            $popularProduct = $em->getRepository('BackEndBundle:PopularProduct')->find($id);
        }

        if ($request->getMethod() == "POST") {

            $dateStart = $request->get("dateStart");
            $dateEnd = $request->get("dateEnd");
            $date = new \DateTime($dateStart);
            $popularProduct->setDateStart($date);
            $date = new \DateTime($dateEnd);
            $popularProduct->setDateEnd($date);
            $popularProduct->setActive($request->get("active") == "on" ? 1 : 0);
            $em->persist($popularProduct);
            $em->flush();

            // SELECCIONADAS
            if ($id > 0) {
                $categoryfrom = $request->get("productfrom");
                //$category
                if (count($categoryfrom) > 0) {
                    $listCategory = $em->getRepository('BackEndBundle:ProductPopularProduct')->findBy(array('popular' => $popularProduct->getId()));
                    foreach ($listCategory as $cat) {
                        foreach ($categoryfrom as $catSelect) {
                            if ($cat->getProduct()->getId() == $catSelect) {
                                $em->remove($cat);
                            }
                        }
                    }
                }
            }
            /*
             * INSERTAR LAS CATEGORIAS SELECCIONADAS
             */

            $categorySelect = $request->get("productSelect");
            if (count($categorySelect) > 0) {
                foreach ($categorySelect as $catSelect) {
                    $listCategory = $em->getRepository('BackEndBundle:ProductPopularProduct')->findBy(array('popular' => $popularProduct->getId(), 'product' => $catSelect));
                    if (count($listCategory) == 0) {
                        $cat = $em->getRepository('BackEndBundle:Product')->find($catSelect);
                        if ($cat) {
                            $productPopularProd = new \com\BackEndBundle\Entity\ProductPopularProduct();
                            $productPopularProd->setProduct($cat);
                            $productPopularProd->setPopular($popularProduct);
                            $em->persist($productPopularProd);
                        }
                    }
                }
            }
        }
        $em->flush();
        $products = $em->getRepository('BackEndBundle:Product')->findAll();

        return $this->render('BackEndBundle:popularproduct:new.html.twig', array(
                    'entity' => $popularProduct,
                    'idsProduct' => $popularProduct->getProductsIds(),
                    'products' => $products,
        ));
    }

    /**
     * Deletes a PopularProduct entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $popularProduct = $em->getRepository('BackEndBundle:PopularProduct')->find($id);
        if ($popularProduct) {
            $em->remove($popularProduct);
            $em->flush();
        }

        return $this->redirectToRoute('popularproduct_index');
    }

}
