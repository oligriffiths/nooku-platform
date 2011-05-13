<?php
/**
 * @version    	$Id$
 * @category	Nooku
 * @package    	Nooku_Server
 * @subpackage 	Modules
 * @copyright  	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license    	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link       	http://www.nooku.org
 */

/**
 * Modules Model Class
 *
 * @author		Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Modules   
 */

class ComModulesModelModules extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
	
		$this->_state
		 	->insert('application', 'cmd', 'site')
		 	->insert('sort'  	  , 'cmd', array('position', 'ordering'))
		 	->insert('enabled'	  , 'int')
		 	->insert('position'   , 'cmd')
		 	->insert('module' 	  , 'cmd')
		 	->insert('assigned'   , 'cmd')
		 	->insert('new'        , 'boolean', false, true);
	}

	protected function _buildQueryJoin(KDatabaseQuery $query)
	{
		$query
			->join('left', 'users AS user', 'user.id = tbl.checked_out')
			->join('left', 'groups AS group', 'group.id = tbl.access')
			->join('left', 'modules_menu AS module_menu', 'module_menu.moduleid = tbl.id');

		parent::_buildQueryJoin($query);
	}

	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->_state;

		if($state->search) {
			$query->where('tbl.title', 'LIKE', '%'.$state->search.'%');
		}

		if($state->assigned)
		{
			$query
				->join('left', 'templates_menu AS template_menu', 'template_menu = module_menu.menuid')
				->where('template_menu.template', '=', $state->assigned);
		}
		
		if($state->position) {
			$query->where('tbl.position', '=', $state->position);
		}
		
		if($state->module) {
			$query->where('tbl.module', '=', $state->module);
		}

		if($state->enabled !== '' && $state->enabled !== null) {
			$query->where('tbl.published', '=', $state->enabled);
		}

		$query->where('tbl.client_id', '=', (int)($state->application == 'admin'));

		parent::_buildQueryWhere($query);
	}

	/**
	 * Get the list of items based on the distinct column values
	 *
	 * We are specializing it because of the admin/site state filter
	 *
	 * @param string	The column name
	 * @return KDatabaseRowset
	 */
	public function getColumn($column)
	{	
		if (!isset($this->_column[$column])) 
		{	
			if($table = $this->getTable()) 
			{
				$query = $table->getDatabase()->getQuery()
					->distinct()
					->group('tbl.'.$table->mapColumns($column))
					->where('tbl.client_id', '=', (int)($this->_state->application == 'admin'));

				$this->_buildQueryOrder($query);

				$this->_column[$column] = $table->select($query);
			}
		}
			
		return $this->_column[$column];
	}

	/**
	 * Method to get a item object which represents a table row
	 *
	 * If the model state is unique a row is fetched from the database based on the state.
	 * If not, an empty row is be returned instead.
	 *
	 * This method is customized in order to set the default module type on new rows.
	 *
	 * @return KDatabaseRow
	 */
	public function getItem()
	{
		if (!isset($this->_item))
		{
			$this->_item = parent::getItem();

			if($this->_item->isNew() && $this->_state->module) {
				$this->_item->module = $this->_state->module;
			}
		}

		return $this->_item;
	}

    /**
     * Get a list of items
     *
     * @return KDatabaseRowsetInterface
     */
    public function getList()
    { 
        if(!isset($this->_list))
        {
            if($this->_state->new)
            {
                $this->_list = array();
            	$lang        = KFactory::get('lib.joomla.language');
            	$root        = $this->_state->application == 'admin' ? JPATH_ADMINISTRATOR : JPATH_ROOT;
            	$path        = $root.'/modules';
            
            	jimport('joomla.filesystem.folder');
            	foreach(JFolder::folders($path) as $folder)
            	{
            		if(strpos($folder, 'mod_') === 0)
            		{
            			$files 				= JFolder::files( $path.'/'.$folder, '^([_A-Za-z0-9]*)\.xml$' );
            			if(!$files) continue;
            
            			$module				= new stdClass;
            			$module->file 		= $files[0];
            			$module->module 	= str_replace('.xml', '', $files[0]);
            			$module->path 		= $path.'/'.$folder;
            			
            			$data = JApplicationHelper::parseXMLInstallFile( $module->path.'/'.$module->file);
            			if($data['type'] == 'module')
            			{
            				$module->name			= $data['name'];
            				$module->description	= $data['description'];
            			}
            
            			$this->_list[]	= $module;
            
            			$lang->load($module->module, $root);
            		}
            	}
            
            	// sort array of objects alphabetically by name
            	JArrayHelper::sortObjects($this->_list, 'name' );
            }
            else {
                $this->_list = parent::getList();
            }
        }

        return $this->_list;
    }
}