<?php
namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Ant\Bundle\ChateaClientBundle\Api\Persistence\ApiRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
class ChannelType implements BaseModel
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
    const REPOSITORY_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Repositories\\ChannelTypeRepository';

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