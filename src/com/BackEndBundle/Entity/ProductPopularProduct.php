<?php

namespace com\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductPopularProduct
 *
 * @ORM\Table(name="product_popular_product", indexes={@ORM\Index(name="FKproduct_po605456", columns={"popular"}), @ORM\Index(name="FKproduct_po328660", columns={"product"})})
 * @ORM\Entity
 */
class ProductPopularProduct
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
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product",inversedBy="populars")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var \PopularProduct
     *
     * @ORM\ManyToOne(targetEntity="PopularProduct",inversedBy="products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="popular", referencedColumnName="id")
     * })
     */
    private $popular;

    function getId() {
        return $this->id;
    }

    function getProduct() {
        return $this->product;
    }

    function getPopular() {
        return $this->popular;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setProduct(Product $product) {
        $this->product = $product;
    }

    function setPopular(PopularProduct $popular) {
        $this->popular = $popular;
    }


}
