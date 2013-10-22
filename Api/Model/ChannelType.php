<?php
namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiManager;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChannelType implements BaseModel
{
    static $manager;

    public static function  setManager($manager)
    {
        self::$manager = $manager;
    }
    public static function  getManager()
    {
        return self::$manager;
    }

    /**
     * Manager class name
     */
    const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\ChannelTypeManager';

    /**
     * @var string
     * @NotBlank()
     */
    private $name;
    private $id;

    function __construct($name = '', $id = 0)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->getName();
    }
}