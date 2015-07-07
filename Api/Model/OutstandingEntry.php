<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

class OutstandingEntry implements BaseModel
{
    /**
     * Manager class name
     */
    const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\OutstandingEntryManager';

    static $manager;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $from;

    /**
     * @var \DateTime
     */
    protected $until;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var User
     */
    protected $outstander;

    /**
     * @var User
     */
    protected $creator;

    protected $resource;

    /**
     * @var boolean
     */
    protected $invalidated;

    public static function  setManager($manager)
    {
        self::$manager = $manager;
    }

    public static function  getManager()
    {
        return self::$manager;
    }

    public function __toString()
    {
        return '';
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param \DateTime $from
     */
    public function setFrom(\DateTime $from)
    {
        $this->from = $from;
    }

    /**
     * @return \DateTime
     */
    public function getUntil()
    {
        return $this->until;
    }

    /**
     * @param \DateTime $until
     */
    public function setUntil(\DateTime $until)
    {
        $this->until = $until;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return User
     */
    public function getOutstander()
    {
        return $this->outstander;
    }

    /**
     * @param User $outstander
     */
    public function setOutstander($outstander)
    {
        $this->outstander = $outstander;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return boolean
     */
    public function isInvalidated()
    {
        return $this->invalidated;
    }

    /**
     * @param boolean $invalidated
     */
    public function setInvalidated($invalidated)
    {
        $this->invalidated = $invalidated;
    }

    /**
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }
}