<?php

namespace MPK\APIBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;

/**
 * @Mongo\Document(repositoryClass="MPK\APIBundle\Document\StationRepository")
 */
class Station
{
    /**
     * @Mongo\Id
     */
    protected $id;
    
    /**
     * @Mongo\String
     */
    protected $name;
    
    /**
     * @Mongo\EmbedMany(
     *      targetDocument="Line",
     *      strategy="set"
     * )
     */
    protected $lines;
    public function __construct()
    {
        $this->lines = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add line
     *
     * @param MPK\APIBundle\Document\Line $line
     */
    public function addLine(\MPK\APIBundle\Document\Line $line)
    {
        $this->lines[] = $line;
    }

    /**
     * Remove line
     *
     * @param MPK\APIBundle\Document\Line $line
     */
    public function removeLine(\MPK\APIBundle\Document\Line $line)
    {
        $this->lines->removeElement($line);
    }

    /**
     * Get lines
     *
     * @return Doctrine\Common\Collections\Collection $lines
     */
    public function getLines()
    {
        return $this->lines;
    }
}
