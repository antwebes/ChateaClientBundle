<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

interface ManagerInterface
{
	static public function hydrate(array $item = null);
	
	public function findById($id);
	
	public function findAll($limit=1 , $offset=0, array $filters = null);
	
	public function save(&$object);
	
	public function update(&$object);
	
	public function delete($object_id);
	
}
