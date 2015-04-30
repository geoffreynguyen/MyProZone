<?php

namespace EX\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Skill
 *
 * @ORM\Table(name="ex_skill")
 * @ORM\Entity(repositoryClass="SkillRepository")
 */
class Skill
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Skill
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->advertSkills = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add advertSkills
     *
     * @param \EX\PlatformBundle\Entity\AdvertSkill $advertSkills
     * @return Skill
     */
    public function addAdvertSkill(\EX\PlatformBundle\Entity\AdvertSkill $advertSkills)
    {
        $this->advertSkills[] = $advertSkills;

        return $this;
    }

    /**
     * Remove advertSkills
     *
     * @param \EX\PlatformBundle\Entity\AdvertSkill $advertSkills
     */
    public function removeAdvertSkill(\EX\PlatformBundle\Entity\AdvertSkill $advertSkills)
    {
        $this->advertSkills->removeElement($advertSkills);
    }

    /**
     * Get advertSkills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdvertSkills()
    {
        return $this->advertSkills;
    }
}
