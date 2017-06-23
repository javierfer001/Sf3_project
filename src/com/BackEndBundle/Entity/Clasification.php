<?php

namespace com\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clasification
 *
 * @ORM\Table(name="clasification")
 * @ORM\Entity(repositoryClass="com\BackEndBundle\Entity\QuestionRespository")
 */
class Clasification {

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
     * @ORM\Column(name="clasification", type="string", length=255, nullable=true)
     */
    private $clasification;

    /**
     * @var string
     *
     * @ORM\Column(name="clasification_es", type="string", length=255, nullable=true)
     */
    private $clasificationEs;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer")
     */
    private $order;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="clasification")
     */
    protected $questions;

    public function __construct() {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    function getId() {
        return $this->id;
    }

    function getClasification() {
        return $this->clasification;
    }

    function getClasificationEs() {
        return $this->clasificationEs;
    }

    function getOrder() {
        return $this->order;
    }

    function getQuestions() {
        return $this->questions;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setClasification($clasification) {
        $this->clasification = $clasification;
    }

    function setClasificationEs($clasificationEs) {
        $this->clasificationEs = $clasificationEs;
    }

    function setOrder($order) {
        $this->order = $order;
    }

    function setQuestions($questions) {
        $this->questions = $questions;
    }

    function __showClasification($lenguaje = 'en') {
        if ($lenguaje == 'en') {
            return $this->clasification;
        }
        return $this->clasificationEs;
    }

}
