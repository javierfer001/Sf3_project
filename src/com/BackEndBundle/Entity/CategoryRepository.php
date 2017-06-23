<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace com\BackEndBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository {

    public function getCategoryMenu($lenguage = 'en') {
        //Consulta DQL
        $dql = "SELECT c,sb FROM BackEndBundle:Category c LEFT JOIN c.subcategories sb ";
        if ($lenguage == 'es')
            $dql .= "  ORDER BY c.categoryEs ASC, sb.subCategoryEs ASC";
        else
            $dql .= "  ORDER BY c.category ASC, sb.subCategory ASC";
        
        return $this->getEntityManager()->createQuery($dql)->getResult();
    }
    
     public function getAllSubcategory() {
        //Consulta DQL
        $dql = "SELECT c,sb FROM BackEndBundle:SubCategory c LEFT JOIN c.category sb ";
        
        return $this->getEntityManager()->createQuery($dql)->getResult();
    }

}
