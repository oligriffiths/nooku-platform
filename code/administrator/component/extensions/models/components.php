<?php
/**
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Components Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Extensions    
 */
class ComExtensionsModelComponents extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
	
		$this->getState()
		 	->insert('enabled', 'boolean')
		 	->insert('name', 'cmd')
            ->insert('sort', 'tbl.name');
	}
	
	protected function _buildQueryWhere(KDatabaseQuerySelect $query)
	{
	    parent::_buildQueryWhere($query);
		$state = $this->getState();
	
		if($state->search) {
			$query->where('tbl.name LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
		}
		
		if($state->name) {
			$query->where('tbl.name = :name')->bind(array('name' => $state->name));
		}

		if(is_bool($state->enabled)) {
			$query->where('tbl.enabled = :enabled')->bind(array('enabled' => (int) $state->enabled));
		}
	}
}