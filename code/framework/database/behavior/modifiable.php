<?php
/**
 * @package		Koowa_Database
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Database Modifiable Behavior
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
class KDatabaseBehaviorModifiable extends KDatabaseBehaviorAbstract
{
	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @return void
     */
	protected function _initialize(KConfig $config)
    {
    	$config->append(array(
			'priority'   => KCommand::PRIORITY_LOW,
	  	));

    	parent::_initialize($config);
   	}

	/**
	 * Get the methods that are available for mixin based
	 *
	 * This function conditionaly mixes the behavior. Only if the mixer
	 * has a 'modified_by' or 'modified_by' property the behavior will
	 * be mixed in.
	 *
	 * @param object The mixer requesting the mixable methods.
	 * @return array An array of methods
	 */
	public function getMixableMethods(KObject $mixer = null)
	{
		$methods = array();

		if($mixer instanceof KDatabaseRowInterface && ($mixer->has('modified_by') || $mixer->has('modified_on'))) {
			$methods = parent::getMixableMethods($mixer);
		}

		return $methods;
	}

	/**
	 * Set modified information
	 *
	 * Requires a 'modified_on' and 'modified_by' column
	 *
	 * @return void
	 */
	protected function _beforeTableUpdate(KCommandContext $context)
	{
		//Get the modified columns
		$modified = $this->getTable()->filter($this->getModified());

		if(!empty($modified))
		{
			if($this->has('modified_by')) {
				$this->modified_by = (int) $this->getService('user')->getId();
			}

			if($this->has('modified_on')) {
				$this->modified_on = gmdate('Y-m-d H:i:s');
			}
		}
	}
}