<?php
namespace Ant\Bundle\ChateaClientBundle\Model;

class Channel implements ChannelInterface
{
    protected $id; 
    protected $name;
    protected $url;
    
    public function __construct($id='', $name, $url)
    {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;	
    }

	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function setUrl($url) {
		$this->url = $url;
		return $this;
	}
	
}