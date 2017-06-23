<?php

namespace com\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Review
 *
 * @ORM\Table(name="review", indexes={@ORM\Index(name="FKreview801826", columns={"product_base"})})
 * @ORM\Entity
 */
class Review
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="start", type="integer", nullable=true)
     */
    private $start;

    /**
     * @var string
     *
     * @ORM\Column(name="coment", type="string", length=255, nullable=true)
     */
    private $coment;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="public", type="integer", nullable=true)
     */
    private $public;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_base", referencedColumnName="id")
     * })
     */
    private $productBase;

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getDate() {
        return $this->date;
    }

    function getStart() {
        return $this->start;
    }

    function getComent() {
        return $this->coment;
    }

    function getEmail() {
        return $this->email;
    }

    function getPublic() {
        return $this->public;
    }

    function getProductBase() {
        return $this->productBase;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDate(\DateTime $date) {
        $this->date = $date;
    }

    function setStart($start) {
        $this->start = $start;
    }

    function setComent($coment) {
        $this->coment = $coment;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPublic($public) {
        $this->public = $public;
    }

    function setProductBase(\Product $productBase) {
        $this->productBase = $productBase;
    }


}
