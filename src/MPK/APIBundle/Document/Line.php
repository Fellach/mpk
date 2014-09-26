<?php

namespace MPK\APIBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;

/**
 * @Mongo\EmbeddedDocument
 */
class Line
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
     *      targetDocument="Departure",
     *      strategy="set"
     * )
     */
    protected $departures;
    
    /**
     * @Mongo\String
     */
    protected $direction;
    
    public function __construct()
    {
        $this->departures = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set direction
     *
     * @param string $direction
     * @return self
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }

    /**
     * Get direction
     *
     * @return string $direction
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Add departure
     *
     * @param MPK\APIBundle\Document\Departure $departure
     */
    public function addDeparture(\MPK\APIBundle\Document\Departure $departure)
    {
        $this->departures[] = $departure;
    }

    /**
     * Remove departure
     *
     * @param MPK\APIBundle\Document\Departure $departure
     */
    public function removeDeparture(\MPK\APIBundle\Document\Departure $departure)
    {
        $this->departures->removeElement($departure);
    }

    /**
     * Get departures
     *
     * @return Doctrine\Common\Collections\Collection $departures
     */
    public function getDepartures()
    {
        return $this->departures;
    }
}
