<?php

namespace MPK\APIBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as Mongo;

/**
 * @Mongo\EmbeddedDocument
 */
class Departure
{    
    /**
     * @Mongo\String
     */
    protected $day;
    
    /**
     * @Mongo\Date
     */
    protected $date;

    /**
     * Set day
     *
     * @param string $day
     * @return self
     */
    public function setDay($day)
    {
        $this->day = $day;
        return $this;
    }

    /**
     * Get day
     *
     * @return string $day
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set date
     *
     * @param date $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }
    
    /**
     * 
     * @param string $day
     * @param date $date
     */
    public function __construct($day, $date)
    {
        $this->setDay($day);
        $this->setDate($date);
    }
}
