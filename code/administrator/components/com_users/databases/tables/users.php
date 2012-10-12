<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Users Database Table Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class ComUsersDatabaseTableUsers extends KDatabaseTableDefault
{
    protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'name'				=> 'users',
			'base' 				=> 'users',
			'behaviors' => array('modifiable', 'lockable', 'identifiable', 'authenticatable')
		));
		
		parent::_initialize($config);
	}
}