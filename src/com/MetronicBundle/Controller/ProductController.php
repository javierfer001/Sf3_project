<?php

namespace com\MetronicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use com\MetronicBundle\Entity\Product;
use com\MetronicBundle\Entity\Images;
use com\MetronicBundle\Entity\ProductCategory;
use com\MetronicBundle\Entity\ProductSubCategory;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductController extends Controller {

    public function indexAction(Request $request) {
        $SAVE = 'FALSE';
        if ($request->getSession()->has('save')) {
            $SAVE = $request->getSession()->get('save');
        }

        $request->getSession()->set('save', 'FALSE');
        return $this->render('MetronicBundle:Product:index.html.twig', array('SAVE' => $SAVE));
    }

    public function paginationAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageSize = $request->get('length');
        $start = $request->get('start');
        $order = $request->get('order');
        $search = $request->get('search');
        $sort = array('c.orderReview','c.code', 'c.name', 'c.name', 'cat.category', 'sub.subCategory');
        $dql = "SELECT c,categories,cat,subs,sub FROM MetronicBundle:Product c LEFT JOIN c.categories categories LEFT JOIN categories.category cat LEFT JOIN c.subcategories subs LEFT JOIN subs.subCategory sub ";

        if ($search['value'] != "") {
            $dql.=" WHERE c.code LIKE '%" . $search['value'] . "%' OR c.name LIKE '%" . $search['value'] . "%'  OR cat.category LIKE '%" . $search['value'] . "%' OR sub.subCategory ='%" . $search['value'] . "%'";
        }
        $dql .= "  ORDER BY  " . ($sort[$order[0]['column']]) . " " . ($order[0]['dir'] == 'desc' ? "DESC" : "ASC");
        //return $this->json($dql);
        $query = $em->createQuery($dql)
                ->setFirstResult($start)
                ->setMaxResults($pageSize);

        $paginator = new Paginator($query);

        $object = array();
        $p = new Product();
        foreach ($paginator as $p) {
            $temp = array();
            $temp[] = $p->getOrderReview();
            $temp[] = $p->getCode();
            $temp[] = $p->getName();
            $img = $p->getImagenes();
            $temp[] = '<img style="width: 60px" src="/web/producto/' . (count($img) > 0 ? $img[0]->getUrl() : "") . '">';
            $temp[] = $p->showCategory();
            $temp[] = $p->showSubCategory();
            $url = $this->generateUrl("product_new_metronic", array('id' => $p->getId()));
            $temp[] = "<a class='btn btn-warning' href='" . $url . "'>Edit</a><a class='btn btn-danger' onclick='deleteAction(" . $p->getId() . ")'>Delete</a>";
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
        $entity = new Product();
        if ($id > 0) {
            $entity = $em->getRepository('MetronicBundle:Product')->getProduct($id);
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $category = $em->getRepository('MetronicBundle:Category')->getCategoryMenu();
        $SAVE = 'FALSE';
        if ($request->getSession()->has('save')) {
            $SAVE = $request->getSession()->get('save');
        }

        $request->getSession()->set('save', 'FALSE');

        return $this->render('MetronicBundle:Product:new.html.twig', array(
                    'id' => 0,
                    'SAVE' => $SAVE,
                    'entity' => $entity,
                    'categories' => $category,
                    'idsCategory' => array(),
                    'idsSubCategory' => array(),
        ));
    }

    public function saveAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == "POST") {
            $orderReview = $request->get("orderReview");
            $code = $request->get("code");
            $name = $request->get("name");
            $nameEs = $request->get("name_es");
            $descrip = $request->get("description");
            $descripEs = $request->get("description_es");
            $shortDescrip = $request->get("short_desc");
            $shortdescripEs = $request->get("short_desc_es");

            $product = new Product();

            if ($id > 0) {
                $product = $em->getRepository('MetronicBundle:Product')->find($id);
            }

            $product->setOrderReview($orderReview);
            $product->setCode($code);
            $product->setDescrip($descrip);
            $product->setDescripEs($descripEs);
            $product->setShortDescrip($shortDescrip);
            $product->setShortDescripEs($shortdescripEs);
            $product->setName($name);
            $product->setNameEs($nameEs);
            $product->setActive(true);
            $em->persist($product);
            //  $em->flush();

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
            $categories = $request->get("categories");

            
            $listCategorySelect = $em->getRepository('MetronicBundle:Category')->getCategoryForIDSubCategory($categories);
            $listSubCategorySelect = $em->getRepository('MetronicBundle:SubCategory')->getSubCategoryForID($categories);
            
            //ELIMINAR LAS CATEGORIAS Y SUBCATEGORIAS QUE NO SE ENCUENTREN
            if ($id > 0) {
                $listCategory = $em->getRepository('MetronicBundle:ProductCategory')->findBy(array('product' => $product->getId()));
                foreach ($listCategory as $cat) {
                    $bool = true;
                    foreach ($listCategorySelect as $selectCat) {
                        if ($cat->getCategory()->getId() == $selectCat->getId()) {
                            $bool = false;
                        }
                    }
                    if ($bool) {
                        $em->remove($cat);
                    }
                }
                
                $listSubCategory = $em->getRepository('MetronicBundle:ProductSubCategory')->findBy(array('product' => $product->getId()));
                foreach ($listSubCategory as $sub) {
                    $bool = true;
                    foreach ($listSubCategorySelect as $selectSub) {
                        if ($sub->getSubCategory()->getId() == $selectSub->getId()) {
                            $bool = false;
                        }
                    }
                    if ($bool) {
                        $em->remove($sub);
                    }
                }
            }

            /*
             * INSERTAR LAS CATEGORIAS SELECCIONADAS
             */
            foreach ($listCategorySelect as $catSelect) {
                $listCategory = $em->getRepository('MetronicBundle:ProductCategory')->findBy(array('product' => $product->getId(), 'category' => $catSelect));
                if (count($listCategory) == 0) {
                    $ProductCategory = new ProductCategory();
                    $ProductCategory->setCategory($catSelect);
                    $ProductCategory->setProduct($product);
                    $em->persist($ProductCategory);
                }
            }

            foreach ($listSubCategorySelect as $subCatSelect) {
                $listSubCategory = $em->getRepository('MetronicBundle:ProductSubCategory')->findBy(array('product' => $product->getId(), 'subCategory' => $subCatSelect));
                if (count($listSubCategory) == 0) {
                    $ProductSubCategory = new ProductSubCategory();
                    $ProductSubCategory->setSubCategory($subCatSelect);
                    $ProductSubCategory->setProduct($product);
                    $em->persist($ProductSubCategory);
                }
            }

            //GUARDAR
            $em->flush();
        }
        $em->flush();

        $request->getSession()->set('save', 'TRUE');

        if ($id == 0) {
            return $this->redirect($this->generateUrl('product_new_metronic', array('id' => 0)));
        }

        return $this->redirect($this->generateUrl('product_metronic'));
    }

    /**
     * Deletes a Product entity.
     *
     */
    public function deleteAction(Request $request) {
        if ($request->getMethod() == "POST") {
            $em = $this->getDoctrine()->getManager();
            $id = $request->get('id');
            $entity = $em->getRepository('MetronicBundle:Product')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Product entity.');
            }

            $em->remove($entity);
            $em->flush();

            return $this->json("OK");
        }
    }

    public function deleteImagenAction(Request $request) {
        if ($request->getMethod() == "POST") {
            $id = $request->get('id');
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MetronicBundle:Images')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Product entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        return $this->json("OK");
    }

}
