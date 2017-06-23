<?php

namespace com\MetronicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Images
 *
 * @ORM\Table(name="images", indexes={@ORM\Index(name="FKimages197275", columns={"product"})})
 * @ORM\Entity
 */
class Images
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
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255, nullable=true)
     */
    private $alt;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product",inversedBy="imagenes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product", referencedColumnName="id")
     * })
     */
    private $product;

    function getId() {
        return $this->id;
    }

    function getUrl() {
        return $this->url;
    }

    function getProduct() {
        return $this->product;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setUrl($url) {
        $this->url = $url;
    }

    function setProduct(Product $product) {
        $this->product = $product;
    }
    
    function getName() {
        return $this->name;
    }

    function setName($name) {
        $this->name = $name;
    }
    
    function getDescription() {
        return $this->description;
    }

    function getAlt() {
        return $this->alt;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setAlt($alt) {
        $this->alt = $alt;
    }





}
