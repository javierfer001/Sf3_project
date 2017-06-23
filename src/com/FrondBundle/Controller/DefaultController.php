<?php

namespace com\FrondBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('MetronicBundle:Category')->getCategoryMenu($request->get('_locale'));
        return $this->render('FrondBundle:Default:index.html.twig', array('category' => $category));
    }

    public function aboutAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('MetronicBundle:Category')->getCategoryMenu($request->get('_locale'));
        return $this->render('FrondBundle:Default:about.html.twig', array('category' => $category));
    }

    public function faqAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('MetronicBundle:Category')->getCategoryMenu($request->get('_locale'));
        $clasifications = $em->getRepository('MetronicBundle:Clasification')->getClasification();
        return $this->render('FrondBundle:Default:faq.html.twig', array(
                    'category' => $category,
                    'clasifications' => $clasifications
        ));
    }

    public function contactAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('MetronicBundle:Category')->getCategoryMenu($request->get('_locale'));
        $productPopular = $em->getRepository("MetronicBundle:Product")->productPopulares();
        return $this->render('FrondBundle:Default:contact.html.twig', array(
                    'category' => $category,
                    'productPopular' => $productPopular
        ));
    }

    public function termAndConditionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('MetronicBundle:Category')->getCategoryMenu($request->get('_locale'));
        return $this->render('FrondBundle:Default:termAndCondition.html.twig', array('category' => $category));
    }

    public function privacyPolicyAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('MetronicBundle:Category')->getCategoryMenu($request->get('_locale'));
        return $this->render('FrondBundle:Default:privacyPolicy.html.twig', array('category' => $category));
    }

    public function cangeLocalAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('MetronicBundle:Category')->getCategoryMenu($request->get('_locale'));
        return $this->redirect($this->generateUrl('front_homepage', array('category' => $category, '__localee' => $request->get('_locale'))));
    }

    public function productAction(Request $request, $category, $id, $type, $page, $perpage) {
        $em = $this->getDoctrine()->getManager();
        //Repositorios de entidades a utilizar
        $productRepository = $em->getRepository("MetronicBundle:Product");
        //Conseguir todos los posts paginados
        $pageSize = $perpage;
        $paginator = $productRepository->getPaginateProduct($pageSize, $page, $id, $type);
        $totalItems = count($paginator);
        $pagesCount = ceil($totalItems / $pageSize);

        $categoryList = $em->getRepository('MetronicBundle:Category')->getCategoryMenu($request->get('_locale'));

        $category = null;
        if ($type == 'sub') {
            $category = $em->getRepository('MetronicBundle:SubCategory')->find($id);
        } else {
            $category = $em->getRepository('MetronicBundle:Category')->find($id);
        }
        $productPopular = $em->getRepository("MetronicBundle:Product")->productPopulares();
        //Renderizamos la vista
        return $this->render('FrondBundle:product:index.html.twig', array(
                    'category' => $categoryList,
                    'productPopular' => $productPopular,
                    "product" => $paginator,
                    "totalItems" => $totalItems,
                    "page" => $page,
                    "pagesCount" => $pagesCount,
                    'cat' => $category,
                    'type' => $type,
                    'id' => $id
        ));

//        $productos = $em->getRepository('MetronicBundle:Product')->getCategoryMenu($request->get('_locale'));
//        $categoryList = $em->getRepository('MetronicBundle:Category')->getCategoryMenu($request->get('_locale'));
//        return $this->render('FrondBundle:product:index.html.twig', array('product' => $productos, 'category' => $categoryList, '__localee' => $request->get('_locale')));
    }

    public function showProductAction(Request $request, $id, $name) {
        $em = $this->getDoctrine()->getManager();
        //Repositorios de entidades a utilizar
        $product = $em->getRepository("MetronicBundle:Product")->getProduct($id);
        $categoryList = $em->getRepository('MetronicBundle:Category')->getCategoryMenu($request->get('_locale'));
        $idsSubCat = array();
        $catProductList = $product->getSubcategories();
        foreach ($catProductList as $prodCategory) {
            $idsSubCat[] = $prodCategory->getSubCategory()->getId();
        }
        
        $productsReferent = $em->getRepository("MetronicBundle:Product")->getProductCategories($idsSubCat);
        
        $productPopular = $em->getRepository("MetronicBundle:Product")->productPopulares();
        
        //Renderizamos la vista
        return $this->render('FrondBundle:product:show.html.twig', array(
                    'id' => $id,
                    'category' => $categoryList,
                    "product" => $product,
                    "productsReferent" => $productsReferent,
                    "productPopular" => $productPopular,
        ));
    }

}
