<?php

namespace com\MetronicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use com\MetronicBundle\Entity\PopularProduct;
use com\MetronicBundle\Entity\ProductPopularProduct;

/**
 * PopularProduct controller.
 *
 */
class PopularProductController extends Controller {

    public function indexAction(Request $request) {
        
        $SAVE = 'FALSE';
        if ($request->getSession()->has('save')) {
            $SAVE = $request->getSession()->get('save');
        }

        $request->getSession()->set('save', 'FALSE');
        
        return $this->render('MetronicBundle:PopularProduct:index.html.twig', array('SAVE' => $SAVE));
    }

    public function paginationAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $pageSize = $request->get('length');
        $start = $request->get('start');
        $order = $request->get('order');
        $search = $request->get('search');
        $sort = array('c.dateStart', 'c.dateEnd', 'c.active');

        $dql = "SELECT c FROM MetronicBundle:PopularProduct c ";
        if ($search['value'] != "") {
            $dql.=" WHERE c.dateStart LIKE '%" . $search['value'] . "%' OR c.dateEnd LIKE '%" . $search['value'] . "%' ";
        }
        $dql .= "  ORDER BY  " . ($sort[$order[0]['column']]) . " " . ($order[0]['dir'] == 'desc' ? "DESC" : "ASC");
        $query = $em->createQuery($dql)
                ->setFirstResult($start)
                ->setMaxResults($pageSize);

        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);

        $object = array();
        $p = new PopularProduct();
        foreach ($paginator as $p) {
            $temp = array();
            $temp[] = $p->getDateStart()->format('Y-m-d');
            $temp[] = $p->getDateEnd()->format('Y-m-d');
            $temp[] = ($p->getActive() == 1 ? "Yes" : "No");
            $urlEdit = $this->generateUrl("product_popular_new_metronic", array('id' => $p->getId()));
            $temp[] = "<a class='btn btn-warning' href='" . $urlEdit . "'>Edit</a><a class='btn btn-danger' onclick='deleteAction(" . $p->getId() . ")'>Delete</a>";
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

        $popularProduct = new PopularProduct();
        $popularProduct->setDateStart(new \DateTime('today'));
        $popularProduct->setDateEnd(new \DateTime('today + 1 month'));
        if ($id > 0) {
            $popularProduct = $em->getRepository('MetronicBundle:PopularProduct')->find($id);
        }
        $SAVE = 'FALSE';
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


            $products = $request->get("product");

            if ($id > 0) {
                $list = $em->getRepository('MetronicBundle:ProductPopularProduct')->findBy(array('popular' => $id));
                foreach ($list as $pp) {
                    $bool = true;
                    foreach ($products as $pSelect) {
                        if ($pSelect == $pp->getProduct()->getId()) {
                            $bool = false;
                        }
                    }
                    if ($bool) {
                        $em->remove($pp);
                    }
                }
            }

            foreach ($products as $prod) {
                $list = $em->getRepository('MetronicBundle:ProductPopularProduct')->findBy(array('product' => $prod, 'popular' => $popularProduct->getId()));
                if (count($list) == 0) {
                    $prod = $em->getRepository('MetronicBundle:Product')->find($prod);
                    if ($prod != null) {
                        $pp = new ProductPopularProduct();
                        $pp->setPopular($popularProduct);
                        $pp->setProduct($prod);
                        $em->persist($pp);
                    }
                }
            }
            $em->flush();
            $SAVE = 'TRUE';
            $request->getSession()->set('save', 'TRUE');
            
            return $this->redirectToRoute('product_popular_metronic');
        }
        

        $products = $em->getRepository('MetronicBundle:Product')->findAll();

        return $this->render('MetronicBundle:PopularProduct:new.html.twig', array(
                    'entity' => $popularProduct,
                    'idsProduct' => $popularProduct->getProductsIds(),
                    'products' => $products,
                    'SAVE' => $SAVE,
        ));
    }

    public function deleteAction(Request $request) {
        if ($request->getMethod() == "POST") {
            $id = $request->get('id');
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MetronicBundle:PopularProduct')->find($id);

            $em->remove($entity);
            $em->flush();

            return $this->json("OK");
        }
    }

}
