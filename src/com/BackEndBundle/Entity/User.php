<?php

namespace com\BackEndBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=255, nullable=true)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="pass", type="string", length=255, nullable=true)
     */
    private $pass;

    /**
     * @var string
     *
     * @ORM\Column(name="rol", type="string", length=255, nullable=true)
     */
    private $rol;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    public function eraseCredentials() {
        
    }

    public function getPassword() {
        $this->pass;
    }

    public function getRoles() {
        return array($this->rol);
    }

    public function getSalt() {
        
    }

    public function getUsername() {
        return $this->user;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }
    
     public function equals(UserInterface $user)
    {
       return $this->id === $user->getId();
    }
    
    function getId() {
        return $this->id;
    }

    function getUser() {
        return $this->user;
    }

    function getPass() {
        return $this->pass;
    }

    function getRol() {
        return $this->rol;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function setPass($pass) {
        $this->pass = $pass;
    }

    function setRol($rol) {
        $this->rol = $rol;
    }


}
