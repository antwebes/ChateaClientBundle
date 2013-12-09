<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;

use Ant\Bundle\ChateaClientBundle\Manager\ChannelManager;

use Ant\Common\Collections\ArrayCollection;
use Ant\Common\Collections\Collection;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

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
     * @var        string
     */
    protected $name;

    /**
     * The value for the slug field.
     * @var        string
     */
    protected $slug;

    /**
     * @var
     * Regex(pattern="/([#&]w+)/", message = "Irc_Channel is not valid.");
     */
    //TODO REGEX this
    protected $irc_channel;
    /**
     * The value for the title field.
     * @var        string
     * @Length(min=4)
     */
    protected $title = '';

    /**
     * The value for the description field.
     * @var        string
     * Length(min=4)
     */
    protected $description = '';
    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var        User
     * FetchType(fetch=FetchType.EAGER)
     */
    protected $oOwner = null;

    /**
     * @var Channel
     */
    private  $oParent;
    /**
     * @var array
     */
    protected $oFans = null;

    /**
     * @var ChannelType
     */
    protected $oChannelType = null;

    /**
     * @var array
     */
    protected $oModerators = null;


    protected $oCity = null;

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
        if ($v !== null && is_string($v)) {
            $v = (string) $v;
        }

        $this->title = $v;

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
        if ($v !== null && is_string($v)) {
            $v = (string) $v;
        }

        $this->description = $v;

        return $this;
    } // setDescription()

    /**
     * @return User|null
     */
    public function getOwner()
    {
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
        $this->oOwner = $v;
    }

    /**
     * @return Channel
     */
    public function getParent()
    {
        return $this->oParent;
    }

    /**
     * @param Channel $v
     */
    public function setParent(Channel $v = null)
    {
        $this->oParent = $v;
    }
    /**
     * FetchType(fetch=FetchType.LAZY)
     *
     * @return Collection|Users[] Collection to store aggregation of User objects.
     */
    public function getFans()
    {
        if($this->oFans == null){
            $this->setFans(self::getManager()->findFans($this->id));
        }

        return $this->oFans;
    }

    public function setFans($oFans)
    {
        $this->oFans = $oFans;
    }

    /**
     * FetchType(fetch=FetchType.LAZY)
     *
     * @return Collection|Users[] Collection to store aggregation of User objects.
     */
    public function getModerators()
    {
        return $this->oModerators;
    }

    public function setModerators($oModerators)
    {
        $this->oModerators = $oModerators;
    }

    /**
     * @param mixed $irc_channel
     */
    public function setIrcChannel($irc_channel)
    {
        $this->irc_channel = $irc_channel;
    }

    /**
     * @return mixed
     */
    public function getIrcChannel()
    {
        return $this->irc_channel;
    }
    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    public function getCity()
    {
        return $this->oCity;
    }
    public function setCity(City $v)
    {
        $this->oCity = $v;
    }
    public function __toString()
    {
        return $this->name;
    }
}