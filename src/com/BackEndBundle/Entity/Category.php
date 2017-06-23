<?php

namespace com\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="com\BackEndBundle\Entity\CategoryRepository")
 */
class Category {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="category_es", type="string", length=100, nullable=true)
     */
    private $categoryEs;

    /**
     * @ORM\OneToMany(targetEntity="SubCategory", mappedBy="category")
     */
    protected $subcategories;

    public function __construct() {
        $this->subcategories = new ArrayCollection();
    }
    
    function showCategory($leng='en') {
       if($leng=='en')
           return $this->category;
       return $this->categoryEs;
    }
    
    function stringListSubCategory($leng='en') {
        $r = "";
        foreach ($this->subcategories as $value) {
            if ($r != "") {
                $r.=", ";
            }
            if ($leng == "en") {
                $r.=$value->getSubCategory();
            } else {
                $r.=$value->getSubCategoryEs();
            }
        }
        return $r;
    }

    function getId() {
        return $this->id;
    }

    function getCategory() {
        return $this->category;
    }

    function getCategoryEs() {
        return $this->categoryEs;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCategory($category) {
        $this->category = $category;
    }

    function setCategoryEs($categoryEs) {
        $this->categoryEs = $categoryEs;
    }
    function getSubcategories() {
        return $this->subcategories;
    }

    function setSubcategories($subcategories) {
        $this->subcategories = $subcategories;
    }



}
