<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Activities
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Activities Model Class
 *
 * @author      Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Activities
 */

class ComActivitiesModelActivities extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->getState()
			->insert('application' , 'cmd')
			->insert('type'        , 'cmd')
			->insert('package'     , 'cmd')
			->insert('name'        , 'cmd')
			->insert('action'      , 'cmd')
			->insert('user'        , 'cmd')
			->insert('distinct'    , 'boolean', false)
			->insert('column'      , 'cmd')
			->insert('start_date'  , 'date')
			->insert('days_back'   , 'int', 14)
			->insert('ip'		   , 'ip');

		$this->getState()->remove('direction')->insert('direction', 'word', 'desc');

		// Force ordering by created_on
		$this->getState()->sort = 'created_on';
	}

	protected function _buildQueryColumns(KDatabaseQuerySelect $query)
	{
	    $state = $this->getState();
	    
		if($state->distinct && !empty($state->column))
		{
			$query->distinct()
				->columns($state->column)
				->columns(array('activities_activity_id' => $state->column));
		}
		else
		{
			parent::_buildQueryColumns($query);
			$query->columns(array('created_by_name' => 'users.name'));
		}
	}

	protected function _buildQueryJoins(KDatabaseQuerySelect $query)
	{
		$query->join(array('users' => 'users'), 'users.users_user_id = tbl.created_by');
	}

	protected function _buildQueryWhere(KDatabaseQuerySelect $query)
	{
		parent::_buildQueryWhere($query);
		$state = $this->getState();

		if ($state->application) {
			$query->where('tbl.application = :application')->bind(array('application' => $state->application));
		}

		if ($state->type) {
			$query->where('tbl.type = :type')->bind(array('type' => $state->type));
		}

		if ($state->package && !($state->distinct && !empty($state->column))) {
			$query->where('tbl.package = :package')->bind(array('package' => $state->package));
		}

		if ($state->name) {
			$query->where('tbl.name = :name')->bind(array('name' => $state->name));
		}

		if ($state->action) {
			$query->where('tbl.action '.(is_array($state->action) ? 'IN' : '=').' :action')->bind(array('action' => $state->action));
		}

		if ($state->start_date && $state->start_date != '0000-00-00')
		{
		    // TODO: Sync this code with KDate and KDatabaseQuery changes.
			$start_date = $this->getService('koowa:date', array('date' => $this->_state->start_date));
			$days_back  = clone $start_date;
			$start      = $start_date->addDays(1)->addSeconds(-1)->getDate();

			$query->where('tbl.created_on', '<', $start);
			$query->where('tbl.created_on', '>', $days_back->addDays(-(int)$this->_state->days_back)->getDate());
		}

		if ($state->user) {
			$query->where('tbl.created_by = :created_by')->bind(array('created_by' => $state->user));
		}
		
		if ($state->ip) {
			$query->where('tbl.ip '.(in_array($state->ip) ? 'IN' : '=').' :ip')->bind(array('ip' => $state->ip)); 
		}
	}

	protected function _buildQueryOrder(KDatabaseQuerySelect $query)
	{
		if($this->getState()->distinct && !empty($this->getState()->column)) {
			$query->order('package', 'asc');
		} else {
		    parent::_buildQueryOrder($query);
		}
	}
}