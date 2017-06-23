<?php

namespace com\MetronicBundle\Entity;

use Doctrine\ORM\EntityRepository;

class QuestionRespository extends EntityRepository {

    public function getClasification() {
        $em = $this->getEntityManager();
        //Consulta DQL
        $dql = "SELECT c,q FROM MetronicBundle:Clasification c LEFT JOIN c.questions q ";
        $dql .= "  ORDER BY c.order, q.order ASC";
        $array = $em->createQuery($dql)->getResult();
                //->setFirstResult($pageSize * ($currentPage - 1))
                //->setMaxResults($pageSize);

        //$paginator = new Paginator($query);

        return $array;
    }
    
      public function getClasificationOrder() {
        $em = $this->getEntityManager();
        $dql = "SELECT c FROM MetronicBundle:Clasification c ";
        $dql .= "  ORDER BY c.order DESC";
        $array = $em->createQuery($dql)->getResult();
        return $array;
    }
    
    public function getQuestionOrder() {
        $em = $this->getEntityManager();
        $dql = "SELECT q FROM MetronicBundle:Question q ";
        $dql .= "  ORDER BY q.order DESC";
        $array = $em->createQuery($dql)->getResult();
        return $array;
    }

}
