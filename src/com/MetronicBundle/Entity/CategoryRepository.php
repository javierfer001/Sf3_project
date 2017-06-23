<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace com\MetronicBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository {

    public function getCategoryMenu($lenguage = 'en') {
        //Consulta DQL
        $dql = "SELECT c,sb FROM MetronicBundle:Category c LEFT JOIN c.subcategories sb ";
        if ($lenguage == 'es')
            $dql .= "  ORDER BY c.categoryEs ASC, sb.subCategoryEs ASC";
        else
            $dql .= "  ORDER BY c.category ASC, sb.subCategory ASC";

        return $this->getEntityManager()->createQuery($dql)->getResult();
    }

    public function getAllSubcategory() {
        //Consulta DQL
        $dql = "SELECT c,sb FROM MetronicBundle:SubCategory c LEFT JOIN c.category sb ";

        return $this->getEntityManager()->createQuery($dql)->getResult();
    }
    
    public function getCategoryProduct() {
        //Consulta DQL
        $dql = "SELECT c,p FROM MetronicBundle:Category c LEFT JOIN c.products p ";

        return $this->getEntityManager()->createQuery($dql)->getResult();
    }

    public function getSubCategoryForID(Array $ids) {
        //Consulta DQL
        $dql = "SELECT sb FROM MetronicBundle:SubCategory sb LEFT JOIN sb.category c ";
        $WHERE=" WHERE ";
        foreach ($ids as $id) {
            if($WHERE != " WHERE "){
                $WHERE.=" OR ";
            }
            $WHERE.=" sb.id=".$id;
        }
        
        return $this->getEntityManager()->createQuery($dql.$WHERE)->getResult();
    }
    public function getCategoryForIDSubCategory(Array $ids) {
        //Consulta DQL
        $dql = "SELECT c FROM MetronicBundle:Category c LEFT JOIN c.subcategories sb ";
        $WHERE=" WHERE ";
        foreach ($ids as $id) {
            if($WHERE != " WHERE "){
                $WHERE.=" OR ";
            }
            $WHERE.=" sb.id=".$id;
        }
        
        return $this->getEntityManager()->createQuery($dql.$WHERE)->getResult();
    }

}
