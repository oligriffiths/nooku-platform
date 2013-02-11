<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Cache Group Model
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 */
class ComCacheModelItems extends KModelAbstract
{	
    public function __construct(KConfig $config)
	{
	    parent::__construct($config);
		
		$this->_state
		    ->insert('name'  , 'cmd')
		    ->insert('hash'  , 'cmd')
		    ->insert('group' , 'url')
		    ->insert('site'  , 'cmd')
		 	->insert('limit' , 'int')
            ->insert('offset', 'int')
            ->insert('sort'     , 'cmd')
            ->insert('direction', 'word', 'asc')
            ->insert('search'   , 'string');
	}
	
    public function getRowset()
    { 
        if(!isset($this->_rowset))
        {
            //Get the keys
            $data = $this->_getData();
          
            //Apply state information
		    if($this->_state->hash) {    
		        $data = array_intersect_key($data, array_flip((array)$this->_state->hash));
		    } 
		    
		    foreach($data as $key => $value)
	        {    
	            if($this->_state->group) 
		        {
		            if($value->group != $this->_state->group) {
		               unset($data[$key]);
		            }
		        }
		        
	            if($this->_state->site) 
		        {
		            if($value->site != $this->_state->site) {
		               unset($data[$key]);
		            }
		        }
	            
	            if($this->_state->search)
	            {
	                 if($value->name != $this->_state->search) {
		               unset($data[$key]);
		            }
	            }
            } 
		    
		    //Set the total
            $this->_total = count($data);
		    
            //Apply limit and offset
            if($this->_state->limit) {
		        $data = array_slice($data, $this->_state->offset, $this->_state->limit);
            }
		    
		    $this->_rowset = $this->getService('com://admin/cache.database.rowset.items', array('data' => $data));
        }
        
        return $this->_rowset;
    }
    
    public function getTotal()
    {
        if(!isset($this->_total)) {
            $this->getRowset();
        }
        
        return $this->_total;
    }
    
    protected function _getData()
    {  
        return JFactory::getCache()->keys();
    }
}