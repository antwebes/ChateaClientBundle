<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;

use Ant\Bundle\ChateaClientBundle\Manager\ChannelManager;

use Ant\Common\Collections\ArrayCollection;
use Ant\Common\Collections\Collection;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Class Channel
 *
 * @package Ant\Bundle\ChateaClientBundle\Model
 */
class Channel implements BaseModel
{
	/**
	 * Manager class name
	 */
	const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\ChannelManager';
	
    static $manager;

    public static function  setManager($manager)
    {
    	if ($manager instanceof ChannelManager){
    		self::$manager = $manager;
    	}else throw new \Exception("Channel need a manager instanceof ChannelManager");
    }
    
    public static function  getManager()
    {
        return self::$manager;
    }

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
     * The value for the slug field.
     * @var        string
     */
    protected $slug = '';

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
     * The value for the owner_id field.
     * @var        int
     */
    protected $owner_id= null;
    protected $owner_name = null;

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
    protected $oOwner = null;


    function __construct(
        $id = 0,
        $name = '',
        $slug = '',
        $channel_type = '',
        $title = '',
        $description = '',
        $owner_id = null,
        $owner_name = '',
        $parent_id = null
    ) {

        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->title = $title;
        $this->description = $description;
        $this->oChannelType = new ChannelType($channel_type);
        $this->oOwner = null;
        $this->oParent = null;
        $this->collFansChannels = new ArrayCollection();
        $this->owner_id = $owner_id;
        $this->owner_name = $owner_name;
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
     * Get the [slug] column value.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of [slug] column.
     *
     * @param string $v new value
     * @return Channel The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null && is_string($v)) {
            $v = (string) $v;
        }

        $this->slug = $v;

        return $this;
    } // setSlug()


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
     * Get the [owner_id] column value.
     *
     * @return int
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }
    public function getOwnerName()
    {
        return $this->owner_name;
    }

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return Channel The current object (for fluent API support)
     */
    public function setOwnerId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }
        $this->owner_id = $v;

        /*if ($this->owner_id !== null) {

            $this->oOwner = self::getManager()->findUser($this->owner_id);
            $this->owner_name = $this->oOwner->getUserName();
        }*/

        return $this;
    } // setUserId()
    public function setOwnerName($v)
    {
        $this->owner_name = $v;

    }
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

            $this->oParent = self::getManager()->findById($this->parent_id);
        }

        return $this;
    } // setParentId()

    /**
     * @return User|null
     */
    public function getOwner()
    {

        if ($this->oOwner === null && ($this->owner_id !== null && $this->owner_id)) {
            $this->setOwner(self::getManager()->findUser($this->owner_id));
        }

        return $this->oOwner;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param  User $v
     * @return Channel The current object (for fluent API support)
     */
    public function setOwner(User $v = null)
    {
        if ($v === null) {
            $this->setOwnerId(NULL);
        } else {
            $this->setOwnerId($v->getId());
        }

        $this->oOwner = $v;
        $this->owner_name = $this->oOwner->getUserName();
        return $this;
    }

    public function  getParent()
    {


        if ($this->oParent === null && ($this->parent_id !== null && $this->parent_id)) {
            $this->setParent(self::getManager()->findById($this->parent_id));
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
        return self::getManager()->findFans($this->id);
    }

    public function __toString()
    {
        return $this->name;
    }
}