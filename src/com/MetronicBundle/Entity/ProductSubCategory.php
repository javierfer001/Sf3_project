<?php

namespace com\MetronicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductSubCategory
 *
 * @ORM\Table(name="product_sub_category", indexes={@ORM\Index(name="FKproduct_su967214", columns={"product"}), @ORM\Index(name="FKproduct_su150901", columns={"sub_category"})})
 * @ORM\Entity
 */
class ProductSubCategory
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
     * @var \SubCategory
     *
     * @ORM\ManyToOne(targetEntity="SubCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sub_category", referencedColumnName="id")
     * })
     */
    private $subCategory;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product",inversedBy="subcategories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product", referencedColumnName="id")
     * })
     */
    private $product;


    function getId() {
        return $this->id;
    }

    function getProduct() {
        return $this->product;
    }

    function getSubCategory() {
        return $this->subCategory;
    }

    function getProductid() {
        return $this->productid;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setProduct($product) {
        $this->product = $product;
    }

    function setSubCategory(SubCategory $subCategory) {
        $this->subCategory = $subCategory;
    }

    function setProductid(Product $productid) {
        $this->productid = $productid;
    }


}
