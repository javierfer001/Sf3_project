<?php

namespace com\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question", indexes={@ORM\Index(name="FKquestion325048", columns={"clasification"}), @ORM\Index(name="FKquestion73359", columns={"clasification"}), @ORM\Index(name="clasification_question_link", columns={"clasification"})})
 * @ORM\Entity(repositoryClass="com\BackEndBundle\Entity\QuestionRespository")
 */
class Question {

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
     * @ORM\Column(name="question", type="text", length=65535, nullable=true)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="question_es", type="text", length=65535, nullable=false)
     */
    private $questionEs;

    /**
     * @var string
     *
     * @ORM\Column(name="response", type="text", length=65535, nullable=true)
     */
    private $response;

    /**
     * @var string
     *
     * @ORM\Column(name="response_es", type="text", length=65535, nullable=true)
     */
    private $responseEs;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer")
     */
    private $order;

    /**
     * @var \Clasification
     *
     * @ORM\ManyToOne(targetEntity="Clasification",inversedBy="questions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="clasification", referencedColumnName="id")
     * })
     */
    private $clasification;

    function getId() {
        return $this->id;
    }

    function getQuestion() {
        return $this->question;
    }

    function getQuestionEs() {
        return $this->questionEs;
    }

    function getResponse() {
        return $this->response;
    }

    function getResponseEs() {
        return $this->responseEs;
    }

    function getOrder() {
        return $this->order;
    }

    function getClasification() {
        return $this->clasification;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setQuestion($question) {
        $this->question = $question;
    }

    function setQuestionEs($questionEs) {
        $this->questionEs = $questionEs;
    }

    function setResponse($response) {
        $this->response = $response;
    }

    function setResponseEs($responseEs) {
        $this->responseEs = $responseEs;
    }

    function setOrder($order) {
        $this->order = $order;
    }

    function setClasification(Clasification $clasification) {
        $this->clasification = $clasification;
    }

    function __showQuestion($lenguaje = "en") {
        if ($lenguaje == 'en') {
            return $this->question;
        }
        return $this->questionEs;
    }

    function __showRespose($lenguaje = "en") {
        if ($lenguaje == 'en') {
            return $this->response;
        }
        return $this->responseEs;
    }

}
