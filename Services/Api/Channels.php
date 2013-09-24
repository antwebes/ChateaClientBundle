<?php
namespace Ant\Bundle\ChateaClientBundle\Services\Api;

use Ant\ChateaClient\Client\IApi;
class Channels
{
    private $apiService;
    
	public function __construct(IApi $apiService)
	{
	   $this->apiService = $apiService;	
	}
	
	/**
	 * @return IApi api 
	 */
	protected function getService()
	{
		return $this->apiService;
	}
	
	public function show()
	{
	    return $this->getService()->showChannels();
	}	
}