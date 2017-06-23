<?php

namespace com\BackEndBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use com\BackEndBundle\Entity\Product;
use com\BackEndBundle\Entity\Images;

/**
 * Product controller.
 *
 */
class ProductController extends Controller {

    /**
     * Lists all Product entities.
     *
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BackEndBundle:Product')->findAll();
        
        $SAVE='FALSE';
        IF($request->getSession()->has('save_product'))
            $SAVE = $request->getSession()->get('save_product');
        $request->getSession()->get('save_product','none');
        
        return $this->render('BackEndBundle:Product:index.html.twig', array(
                    'entities' => $entities,
                    'SAVE' => $SAVE,
        ));
    }

    /**
     * Displays a form to create a new Product entity.
     *
     */
    public function newAction(Request $request) {

        $em = $this->getDoctrine()->getManager();


        $category = $em->getRepository('BackEndBundle:Category')->findAll();
        $Subcategory = $em->getRepository('BackEndBundle:SubCategory')->getAllSubcategory();

        $SAVE=FALSE;
        if($request->getSession()->has('save_product')){
            $SAVE = $request->getSession()->get('save_product');
        }
        
        $request->getSession()->get('save_product','FALSE');
        
        return $this->render('BackEndBundle:Product:new.html.twig', array(
                    'id' => 0,
                    'SAVE' => $SAVE,
                    'entity' => new Product(),
                    'category' => $category,
                    'Subcategory' => $Subcategory,
                    'idsCategory' => array(),
                    'idsSubCategory' => array(),
        ));
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     */
    public function editAction(Request $request,$id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BackEndBundle:Product')->getProduct($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $category = $em->getRepository('BackEndBundle:Category')->findAll();
        $Subcategory = $em->getRepository('BackEndBundle:SubCategory')->getAllSubcategory();
        
        $SAVE=FALSE;
        if($request->getSession()->has('save_product')){
            $SAVE = $request->getSession()->get('save_product');
        }
        $request->getSession()->get('save_product','none');
        return $this->render('BackEndBundle:Product:new.html.twig', array(
                    'id' => $id,
                    'SAVE' => $SAVE,
                    'entity' => $entity,
                    'category' => $category,
                    'Subcategory' => $Subcategory,
                    'idsCategory' => $entity->getCategoriaIds(),
                    'idsSubCategory' => $entity->getSubCategoriaIds(),
        ));
    }

    public function saveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == "POST") {
            $code = $request->get("code");
            $name = $request->get("name");
            $nameEs = $request->get("name_es");
            $descrip = $request->get("description");
            $descripEs = $request->get("description_es");
            $shortDescrip = $request->get("shortDescrip");
            $shortdescripEs = $request->get("shortdescription_es");

            $product = new Product();

            if ($id > 0) {
                $product = $em->getRepository('BackEndBundle:Product')->find($id);
            }

            $product->setCode($code);
            $product->setDescrip($descrip);
            $product->setDescripEs($descripEs);
            $product->setShortDescrip($shortDescrip);
            $product->setShortDescripEs($shortdescripEs);
            $product->setName($name);
            $product->setNameEs($nameEs);
            $product->setActive(true);
            $em->persist($product);

            for ($i = 1; $i <= 5; $i++) {
                if ($_FILES['file_' . $i]['name'] != "") {
                    $filename = time() . basename($_FILES['file_' . $i]['name']);
                    $dir_subida = __DIR__ . '/../../../../web/producto/';
                    $fichero_subido = $dir_subida . $filename;

                    if (move_uploaded_file($_FILES['file_' . $i]['tmp_name'], $fichero_subido)) {
                        $img_1 = new Images();
                        $img_1->setName('file_' . $i);
                        $img_1->setProduct($product);
                        $img_1->setUrl($filename);
                        $em->persist($img_1);
                    }
                }
            }

            //IMAGEN
            $categoryfrom = $request->get("categoryfrom");
            //$category
            $listCategory = $em->getRepository('BackEndBundle:ProductCategory')->findBy(array('product' => $product->getId()));
            foreach ($listCategory as $cat) {
                foreach ($categoryfrom as $catSelect) {
                    if ($cat->getCategory()->getId() == $catSelect) {
                        $em->remove($cat);
                    }
                }
            }
            
            //ELIMINAR LAS CATEGORIAS Y SUBCATEGORIAS QUE NO SE ENCUENTREN
            // SELECCIONADAS
            if ($id > 0) {
                $categoryfrom = $request->get("categoryfrom");
                //$category
                if (count($categoryfrom) > 0) {
                    $listCategory = $em->getRepository('BackEndBundle:ProductCategory')->findBy(array('product' => $product->getId()));
                    foreach ($listCategory as $cat) {
                        foreach ($categoryfrom as $catSelect) {
                            if ($cat->getCategory()->getId() == $catSelect) {
                                $em->remove($cat);
                            }
                        }
                    }
                }

                $categoryfrom = $request->get("SubCategoryfrom");
                //$category
                if (count($categoryfrom) > 0) {
                    $listCategory = $em->getRepository('BackEndBundle:ProductSubCategory')->findBy(array('product' => $product->getId()));
                    foreach ($listCategory as $cat) {
                        foreach ($categoryfrom as $catSelect) {
                            if ($cat->getSubCategory()->getId() == $catSelect) {
                                $em->remove($cat);
                            }
                        }
                    }
                }
            }

            /*
             * INSERTAR LAS CATEGORIAS SELECCIONADAS
             */

            $categorySelect = $request->get("categorySelect");
            if (count($categorySelect) > 0) {
                foreach ($categorySelect as $catSelect) {
                    $listCategory = $em->getRepository('BackEndBundle:ProductCategory')->findBy(array('product' => $product->getId(), 'category' => $catSelect));
                    if (count($listCategory) == 0) {
                        $cat = $em->getRepository('BackEndBundle:Category')->find($catSelect);
                        if ($cat) {
                            $ProductCategory = new \com\BackEndBundle\Entity\ProductCategory();
                            $ProductCategory->setCategory($cat);
                            $ProductCategory->setProduct($product);
                            $em->persist($ProductCategory);
                        }
                    }
                }
            }

            $categorySelect = $request->get("SubCategoryto");
            if (count($categorySelect) > 0) {
                foreach ($categorySelect as $catSelect) {
                    $listCategory = $em->getRepository('BackEndBundle:ProductSubCategory')->findBy(array('product' => $product->getId(), 'subCategory' => $catSelect));
                    if (count($listCategory) == 0) {
                        $cat = $em->getRepository('BackEndBundle:SubCategory')->find($catSelect);
                        if ($cat) {
                            $ProductSubCategory = new \com\BackEndBundle\Entity\ProductSubCategory();
                            $ProductSubCategory->setSubCategory($cat);
                            $ProductSubCategory->setProduct($product);
                            $em->persist($ProductSubCategory);
                        }
                    }
                }
            }
            //GUARDAR
            $em->flush();
        }
        $request->getSession()->set('save_product','guardo');
        if ($id == 0) {
            return $this->redirect($this->generateUrl('product_new'));
        }
        return $this->redirect($this->generateUrl('product_index'));
    }

    /**
     * Deletes a Product entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BackEndBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('product_index'));
    }

    /**
     * Deletes a Product entity.
     *
     */
    public function deleteImagenAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BackEndBundle:Images')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $em->remove($entity);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\Response("ok");
    }

}
