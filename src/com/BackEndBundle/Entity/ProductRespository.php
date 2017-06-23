<?php

namespace com\BackEndBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductRespository extends EntityRepository {

    public function getPaginateProduct($pageSize = 3, $currentPage, $id, $type) {
        $em = $this->getEntityManager();

        //Consulta DQL
        $dql = "SELECT p, pc, c, ps, sb, img FROM BackEndBundle:Product p LEFT JOIN p.categories pc LEFT JOIN pc.category c LEFT JOIN p.subcategories ps LEFT JOIN ps.subCategory sb LEFT JOIN p.imagenes img";
        if ($type == 'cat') {
            $dql .= " WHERE c.id=" . $id;
        } else {
            $dql .= " WHERE sb.id=" . $id;
        }
        $dql .= "  ORDER BY p.id DESC";
        $query = $em->createQuery($dql)
                ->setFirstResult($pageSize * ($currentPage - 1))
                ->setMaxResults($pageSize);

        $paginator = new Paginator($query);

        return $paginator;
    }

    public function getProduct($id) {
        //Consulta DQL
        $dql = "SELECT p, pc, c, ps, sb, img FROM BackEndBundle:Product p LEFT JOIN p.categories pc LEFT JOIN pc.category c LEFT JOIN p.subcategories ps LEFT JOIN ps.subCategory sb LEFT JOIN p.imagenes img";
        $dql .= " WHERE p.id=" . $id;
        $dql .= "  ORDER BY p.id DESC, img.name ASC";
        return $this->getEntityManager()->createQuery($dql)->getOneOrNullResult();
    }

    public function getProductCategories(array $idCategory) {
        //Consulta DQL
        if (count($idCategory) == 0) {
            return array();
        }
        $dql = "SELECT p, pc, c, ps, sb, img FROM BackEndBundle:Product p LEFT JOIN p.categories pc LEFT JOIN pc.category c LEFT JOIN p.subcategories ps LEFT JOIN ps.subCategory sb LEFT JOIN p.imagenes img";
        $dqlT = " WHERE ";
        foreach ($idCategory as $value) {
            if($dqlT != " WHERE ")
                $dqlT .= " OR ";
            $dqlT .= " sb.id=" . $value;
        }
        $dql .= $dqlT."  ORDER BY p.id DESC";
        
        $arreglo=$this->getEntityManager()->createQuery($dql)
                        ->setFirstResult(0)->setMaxResults(15)
                        ->getResult();
        return $arreglo;
    }

    public function productPopulares() {
        //Consulta DQL
        $dql = "SELECT p, pc, c, ps, sb, img, po, popular FROM BackEndBundle:Product p LEFT JOIN p.categories pc LEFT JOIN pc.category c LEFT JOIN p.subcategories ps LEFT JOIN ps.subCategory sb LEFT JOIN p.imagenes img";
        $dql .= "  JOIN p.populars po  JOIN po.popular popular WHERE popular.dateStart <=:date";
        $dql .= "  ORDER BY popular.dateStart DESC, p.id DESC";
        $query=$this->getEntityManager()->createQuery($dql)->setParameter('date', new \DateTime('now'))
                ->setFirstResult(0)
                ->setMaxResults(10);
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        return $paginator;
    }

}
