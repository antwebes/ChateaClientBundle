<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Ant\Common\Collections\ArrayCollection;
use Ant\Common\Collections\Collection;
use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiRepository;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Class Channel
 *
 * @package Ant\Bundle\ChateaClientBundle\Model
 */
class Channel implements BaseModel
{

    static $repository;

    public static function  setRepository(ApiRepository $repository)
    {
        self::$repository = $repository;
    }
    public static function  getRepository()
    {
        return self::$repository;
    }

    /**
     * Repository class name
     */
    const REPOSITORY_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Repositories\\ChannelRepository';

    /**
     * The value for the id field.
     * @var        int
     *
     */
    protected $id = 0;

    /**
     * The value for the name field.
     *
     * @NotBlank
     * @Length(min=4)
     *
     * @var        string
     */
    protected $name = '';

    /**
     * The value for the url field.
     * @var        string
     */
    protected $url = '';

    /**
     * The value for the title field.
     * @var        string
     * @Length(min=4)
     */
    protected $title = '';

    /**
     * The value for the description field.
     * @var        string
     * @Length(min=4)
     */
    protected $description = '';

    /**
     * The value for the user_id field.
     * @var        int
     */
    protected $creator_id = null;

    /**
     * The value for the parent_id field.
     * @var        int
     */
    protected $parent_id = null;


    /**
     * @var ChannelType
     */
    protected $oChannelType = null;

    /**
     * @var        Channel
     */
    protected $oParent = null;

    /**
     * @var        User
     * FetchType(fetch=FetchType.EAGER)
     */
    protected $oCreator = null;


    function __construct(
        $id = 0,
        $name = '',
        $url = '',
        $channel_type = '',
        $title = '',
        $description = '',
        $creator_id = null,
        $parent_id = null
    ) {

        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->oChannelType = new ChannelType($channel_type);
        $this->oCreator = null;
        $this->oParent = null;
        $this->collFansChannels = new ArrayCollection();
        $this->creator_id = $creator_id;
        $this->parent_id = $parent_id;

    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Channel The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }
        $this->id = $v;

        return $this;
    } // setId()

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return Channel The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_string($v)) {
            $v = (string) $v;
        }
        $this->name = $v;

        return $this;
    } // setName()

    /**
     * Get the [url] column value.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the value of [url] column.
     *
     * @param string $v new value
     * @return Channel The current object (for fluent API support)
     */
    public function setUrl($v)
    {
        if ($v !== null && is_string($v)) {
            $v = (string) $v;
        }

        $this->url = $v;

        return $this;
    } // setUrl()


    /**
     * Get the [channel_type] column value.
     *
     * @return int
     */
    public function getChannelType()
    {
        return $this->oChannelType;
    }

    /**
     * Set the value of [channel_type] column.
     *
     * @param ChannelType $v new value
     * @return Channel The current object (for fluent API support)
     */
    public function setChannelType(ChannelType $v)
    {

        $this->oChannelType = $v;

        return $this;
    } // setChannelType()

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return Channel The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        $this->title = $v;

        return $this;
    } // setTitle()

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return Channel The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        $this->description = $v;

        return $this;
    } // setDescription()

    /**
     * Get the [creator_id] column value.
     *
     * @return int
     */
    public function getCreatorId()
    {
        return $this->creator_id;
    }

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return Channel The current object (for fluent API support)
     */
    public function setCreatorId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }
        $this->creator_id = $v;

        if ($this->creator_id !== null) {

            $this->oCreator = self::getRepository()->findUser($this->creator_id);
        }

        return $this;
    } // setUserId()

    /**
     * Get the [parent_id] column value.
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Set the value of [parent_id] column.
     *
     * @param int $v new value
     * @return Channel The current object (for fluent API support)
     */
    public function setParentId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        $this->parent_id = $v;

        if ($this->parent_id !== null) {

            $this->oParent = self::getRepository()->findById($this->parent_id);
        }

        return $this;
    } // setParentId()

    /**
     * @return User|null
     */
    public function getCreator()
    {

        if ($this->oCreator === null && ($this->creator_id !== null && $this->creator_id)) {
            $this->setCreator(self::getRepository()->findUser($this->creator_id));
        }
        return $this->oCreator;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param  User $v
     * @return Channel The current object (for fluent API support)
     */
    public function setCreator(User $v = null)
    {
        if ($v === null) {
            $this->setCreatorId(NULL);
        } else {
            $this->setCreatorId($v->getId());
        }

        $this->oCreator = $v;

        return $this;
    }

    public function  getParent()
    {


        if ($this->oParent === null && ($this->parent_id !== null && $this->parent_id)) {
            $this->setParent(self::getRepository()->findById($this->parent_id));
        }

        return $this->oParent;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param  Channel $v
     * @return Channel The current object (for fluent API support)
     */
    public function setParent(Channel $v = null)
    {
        if ($v === null) {
            $this->setParentId(NULL);
        } else {
            $this->setParentId($v->getId());
        }

        $this->oParent = $v;

        return $this;
    }


    /**
     * FetchType(fetch=FetchType.LAZY)
     *
     * @return Collection|Users[] Collection to store aggregation of User objects.
     */
    public function getFans()
    {
        return self::getRepository()->findFans($this->id);
    }

    public function __toString()
    {
        return $this->name;
    }
}