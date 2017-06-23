<?php

namespace com\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubCategory
 *
 * @ORM\Table(name="sub_category", indexes={@ORM\Index(name="FKsub_catego299841", columns={"category"})})
 * @ORM\Entity(repositoryClass="com\BackEndBundle\Entity\CategoryRepository")
  */
class SubCategory
{
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
     * @ORM\Column(name="sub_category", type="string", length=100, nullable=true)
     */
    private $subCategory;

    /**
     * @var string
     *
     * @ORM\Column(name="sub_category_es", type="string", length=100, nullable=true)
     */
    private $subCategoryEs;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category",inversedBy="subcategories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category", referencedColumnName="id")
     * })
     */
    private $category;
    
    public function showCategory($leng='en') {
       if($leng=='en')
           return $this->subCategory;
       return $this->subCategoryEs;
    }
    
    function getId() {
        return $this->id;
    }

    function getSubCategory() {
        return $this->subCategory;
    }

    function getSubCategoryEs() {
        return $this->subCategoryEs;
    }

    function getCategory() {
        return $this->category;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setSubCategory($subCategory) {
        $this->subCategory = $subCategory;
    }

    function setSubCategoryEs($subCategoryEs) {
        $this->subCategoryEs = $subCategoryEs;
    }

    function setCategory(Category $category) {
        $this->category = $category;
    }



}
