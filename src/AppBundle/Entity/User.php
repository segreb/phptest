<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections as DCC;
use AppBundle\Entity\Group;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User
{
    /** 
     * @ORM\Id
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     *@ORM\Column(type="string")
     */
    private $name;

    /**
     *@ORM\Column(type="string")
     */
    private $email;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     * @ORM\JoinTable(name="user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=true)}
     * )
     */
    public $groups;


    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
   
    public function getEmail()
    {
        return $this->email;
    }
   
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
   
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function __construct() {
        $this->groups = new DCC\ArrayCollection();
    }

}
