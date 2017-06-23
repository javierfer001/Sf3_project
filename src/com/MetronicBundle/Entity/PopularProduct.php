<?php

namespace com\MetronicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PopularProduct
 *
 * @ORM\Table(name="popular_product")
 * @ORM\Entity
 */
class PopularProduct {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="date", nullable=true)
     */
    private $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_end", type="date", nullable=true)
     */
    private $dateEnd;

    /**
     * @var integer
     *
     * @ORM\Column(name="active", type="integer", nullable=false)
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="ProductPopularProduct", mappedBy="popular")
     */
    protected $products;

    public function __construct() {
        $this->products = new ArrayCollection();
    }
    
    function getProductsIds() {
        $return = array();
        foreach ($this->products as $value) {
                $return[]=$value->getProduct()->getId();
        }
        return $return;
    }
    
   
    
    function getProducts() {
        return $this->products;
    }

    function setProducts($products) {
        $this->products = $products;
    }

        
    function getId() {
        return $this->id;
    }

    function getDateStart() {
        return $this->dateStart;
    }

    function getDateEnd() {
        return $this->dateEnd;
    }

    function getActive() {
        return $this->active;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDateStart(\DateTime $dateStart) {
        $this->dateStart = $dateStart;
    }

    function setDateEnd(\DateTime $dateEnd) {
        $this->dateEnd = $dateEnd;
    }

    function setActive($active) {
        $this->active = $active;
    }

}
