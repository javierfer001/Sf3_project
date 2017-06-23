<?php

namespace com\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductCategory
 *
 * @ORM\Table(name="product_category", indexes={@ORM\Index(name="FKProduct_ca797168", columns={"product"}), @ORM\Index(name="FKProduct_ca768222", columns={"category"})})
 * @ORM\Entity
 */
class ProductCategory
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
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="categories" )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product", referencedColumnName="id")
     * })
     */
    private $product;

    function getId() {
        return $this->id;
    }

    function getCategory() {
        return $this->category;
    }

    function getProduct() {
        return $this->product;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCategory(Category $category) {
        $this->category = $category;
    }

    function setProduct(Product $product) {
        $this->product = $product;
    }



}
