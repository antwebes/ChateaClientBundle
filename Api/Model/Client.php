<?php
/**
 * Created by Javier Fernández Rodríguez for Ant-Webs S.L.
 * User: Pablo
 * Date: 13/01/15 - 13:30
 */

namespace Ant\Bundle\ChateaClientBundle\Api\Model;

use Ant\Bundle\ChateaClientBundle\Api\Model\BaseModel;
use Ant\Bundle\ChateaClientBundle\Manager\ClientManager;


class Client implements BaseModel
{

    /**
     * Manager class name
     */
    const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\ClientManager';

    static $manager;


    public static function  setManager($manager)
    {
        if ($manager instanceof ClientManager){
            self::$manager = $manager;
        }else throw new \Exception("Client need a manager instanceof ClientManager");
    }

    static function  getManager()
    {
        return static::$manager;
    }

    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $randomId;
    /**
     * @var string
     */
    private $secret;
    /**
     * @var string
     */
    private $name;
    /**
     * @var Affiliate
     */
    private $affiliate;
    /**
     * @var array
     */
    private $redirectUris;
    /**
     * @var array
     */
    private $allowedGrantTypes;
    /**
     * @var string
     */
    private $confirmedUri;

    /**
     * @var string
     */
    private $resettingUrl;
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
     * @return mixed
     */
    public function getRandomId()
    {
        return $this->randomId;
    }

    /**
     * @param mixed $randomId
     */
    public function setRandomId($randomId)
    {
        $this->randomId = $randomId;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param mixed $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Affiliate
     */
    public function getAffiliate()
    {
        return $this->affiliate;
    }

    /**
     * @param Affiliate $affiliate
     */
    public function setAffiliate($affiliate)
    {
        $this->affiliate = $affiliate;
    }

    /**
     * @return array
     */
    public function getRedirectUris()
    {
        return $this->redirectUris;
    }

    /**
     * @param array $redirectUris
     */
    public function setRedirectUris($redirectUris)
    {
        $this->redirectUris = $redirectUris;
    }

    /**
     * @return array
     */
    public function getAllowedGrantTypes()
    {
        return $this->allowedGrantTypes;
    }

    /**
     * @param array $allowedGrantTypes
     */
    public function setAllowedGrantTypes($allowedGrantTypes)
    {
        $this->allowedGrantTypes = $allowedGrantTypes;
    }

    /**
     * @return string
     */
    public function getConfirmedUri()
    {
        return $this->confirmedUri;
    }

    /**
     * @param string $confirmedUri
     */
    public function setConfirmedUri($confirmedUri)
    {
        $this->confirmedUri = $confirmedUri;
    }

    /**
     * @return string
     */
    public function getResettingUrl()
    {
        return $this->resettingUrl;
    }

    /**
     * @param string $resettingUrl
     */
    public function setResettingUrl($resettingUrl)
    {
        $this->resettingUrl = $resettingUrl;
    }

   public function __toString()
    {
        return $this->name;
    }

}
