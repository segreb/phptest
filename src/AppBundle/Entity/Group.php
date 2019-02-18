<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections as DCC;
use AppBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="`group`")
 */
class Group
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
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     */
    public $users;
  

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
   
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
   

    public function __construct() {
        $this->users = new DCC\ArrayCollection();
    }

}
