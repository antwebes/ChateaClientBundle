<?php

namespace Ant\Bundle\ChateaClientBundle\Api\Model;


class Photo
{
    /**
     * Manager class name
     */
    const MANAGER_CLASS_NAME = 'Ant\\Bundle\\ChateaClientBundle\\Manager\\UserManager';

    private static $manager;

    private $id;

    private $title;

    private $publicatedAt;

    private $numberVotes = 0;

    private $score = 0;

    private $path;

    public static function  setManager($manager)
    {
        self::$manager = $manager;
    }

    public static function  getManager()
    {
        return self::$manager;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getPublicatedAt()
    {
        return $this->publicatedAt;
    }

    public function setPublicatedAt($publicatedAt)
    {
        $this->publicatedAt = $publicatedAt;
    }

    public function getNumberVotes()
    {
        return $this->numberVotes;
    }

    public function setNumberVotes($numberVotes)
    {
        $this->numberVotes = $numberVotes;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function setScore($score)
    {
        $this->score = $score;
    }

    public function getPathLarge()
    {
        return $this->_getPath('large');
    }

    public function getPathMedium()
    {
        return $this->_getPath('medium');
    }

    public function getPathSmall()
    {
        return $this->_getPath('small');
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    private function _getPath($size)
    {
        if($this->path == null){
            return $this->null;
        }

        //el penultimo elemento es donde añadimos el tamaño
        $parts = explode('.', $this->path);
        $penultimateIndex = count($parts) - 2;
        $parts[$penultimateIndex] = sprintf("%s_%s", $parts[$penultimateIndex], $size);

        //lo juntamos todo de nuevo
        return implode('.', $parts);
    }
}