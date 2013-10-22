<?php

namespace Ant\Bundle\ChateaClientBundle\Manager;

interface ManagerInterface
{
	static public function hydrate(array $item = null);
	
	public function findById($id);

    public function findAll($page = 1, array $filters = null, $limit= null);
	
	public function save(&$object);
	
	public function update(&$object);
	
	public function delete($object_id);
	
}
