<?php

namespace com\MetronicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="com\MetronicBundle\Entity\ProductRespository")
 */
class Product {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="order_review", type="integer", nullable=false)
     */
    private $orderReview;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="active", type="integer", nullable=false)
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=100, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="name_es", type="string", length=255, nullable=true)
     */
    private $nameEs;
    
    /**
     * @var string
     *
     * @ORM\Column(name="descrip", type="string", length=255, nullable=true)
     */
    private $descrip;

    /**
     * @var string
     *
     * @ORM\Column(name="descrip_es", type="string", length=255, nullable=true)
     */
    private $descripEs;
    /**
     * @var string
     *
     * @ORM\Column(name="shortdescrip", type="string", length=255, nullable=true)
     */
    private $shortDescrip;

    /**
     * @var string
     *
     * @ORM\Column(name="shortdescrip_es", type="string", length=255, nullable=true)
     */
    private $shortDescripEs;

    /**
     * @ORM\OneToMany(targetEntity="Images", mappedBy="product")
     */
    protected $imagenes;

    /**
     * @ORM\OneToMany(targetEntity="ProductCategory", mappedBy="product")
     */
    protected $categories;
    
    /**
     * @ORM\OneToMany(targetEntity="ProductSubCategory", mappedBy="product")
     */
    protected $subcategories;
    
    /**
     * @ORM\OneToMany(targetEntity="ProductPopularProduct", mappedBy="product")
     */
    protected $populars;
    

    public function __construct() {
        $this->imagenes = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->subcategories = new ArrayCollection();
        $this->populars = new ArrayCollection();
    }

    function imagenesShowName($type) {
        //return $type.count($this->categories);
        foreach ($this->imagenes as $img) {
            if ($img->getName() == $type) {
                return $img->getUrl();
            }
        }
        return "default.jpg";
    }
    
     function imagenesShowNameEdit($type) {
        //return $type.count($this->categories);
        foreach ($this->imagenes as $img) {
            if ($img->getName() == $type) {
                return $img;
            }
        }
        return false;
    }

    function showCategory($leng= 'en') {
        $return = "";
        foreach ($this->categories as $value) {
            if ($return != "")
                $return.=", ";
            if ($leng == 'en') {
                $return.=$value->getCategory()->getCategory();
            } else {
                $return.=$value->getCategory()->getCategoryEs();
            }
        }
        return $return;
    }
    
    
    function showSubCategory($leng= 'en') {
        $return = "";
        foreach ($this->subcategories as $value) {
            if ($return != "")
                $return.=", ";
            if ($leng == 'en') {
                $return.=$value->getSubCategory()->getSubCategory();
            } else {
                $return.=$value->getSubCategory()->getSubCategoryEs();
            }
        }
        return $return;
    }
    
    function getCategoriaIds() {
        $return = array();
        foreach ($this->categories as $value) {
                $return[]=$value->getCategory()->getId();
        }
        return $return;
    }
    
     function getSubCategoriaIds() {
        $return = array();
        foreach ($this->subcategories as $value) {
                $return[]=$value->getSubCategory()->getId();
        }
        return $return;
    }

    function getId() {
        return $this->id;
    }

    function getCode() {
        return $this->code;
    }

    function getName() {
        return $this->name;
    }

    function getDescrip() {
        return $this->descrip;
    }

    function getNameEs() {
        return $this->nameEs;
    }

    function getDescripEs() {
        return $this->descripEs;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCode($code) {
        $this->code = $code;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescrip($descrip) {
        $this->descrip = $descrip;
    }

    function setNameEs($nameEs) {
        $this->nameEs = $nameEs;
    }

    function setDescripEs($descripEs) {
        $this->descripEs = $descripEs;
    }
    
    function getImagenes() {
        return $this->imagenes;
    }

    function getCategories() {
        return $this->categories;
    }

    function getSubcategories() {
        return $this->subcategories;
    }

    function setImagenes($imagenes) {
        $this->imagenes = $imagenes;
    }

    function setCategories($categories) {
        $this->categories = $categories;
    }

    function setSubcategories($subcategories) {
        $this->subcategories = $subcategories;
    }

    function getShortDescrip() {
        return $this->shortDescrip;
    }

    function getShortDescripEs() {
        return $this->shortDescripEs;
    }

    function setShortDescrip($shortDescrip) {
        $this->shortDescrip = $shortDescrip;
    }

    function setShortDescripEs($shortDescripEs) {
        $this->shortDescripEs = $shortDescripEs;
    }

    function getActive() {
        return $this->active;
    }

    function setActive($active) {
        $this->active = $active;
    }

    function getPopulars() {
        return $this->populars;
    }

    function setPopulars($populars) {
        $this->populars = $populars;
    }

    function getOrderReview() {
        return $this->orderReview;
    }

    function setOrderReview($orderReview) {
        $this->orderReview = $orderReview;
    }



}
