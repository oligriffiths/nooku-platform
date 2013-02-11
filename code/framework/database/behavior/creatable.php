<?php
/**
 * @package     Koowa_Database
 * @subpackage  Behavior
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Database Creatable Behavior
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Behavior
 */
class KDatabaseBehaviorCreatable extends KDatabaseBehaviorAbstract
{
    /**
     * Get the methods that are available for mixin based
     *
     * This function conditionaly mixes the behavior. Only if the mixer
     * has a 'created_by' or 'created_on' property the behavior will be
     * mixed in.
     *
     * @param object The mixer requesting the mixable methods.
     * @return array An array of methods
     */
    public function getMixableMethods(KObject $mixer = null)
    {
        $methods = array();

        if($mixer instanceof KDatabaseRowInterface && ($mixer->has('created_by') || $mixer->has('created_on')))  {
            $methods = parent::getMixableMethods($mixer);
        }

        return $methods;
    }

    /**
     * Set created information
     *
     * Requires an 'created_on' and 'created_by' column
     *
     * @return void
     */
    protected function _beforeTableInsert(KCommandContext $context)
    {
        if($this->has('created_by') && empty($this->created_by)) {
            $this->created_by  = (int) $this->getService('user')->getId();
        }

        if($this->has('created_on') && (empty($this->created_on) || $this->created_on == $this->getTable()->getDefault('created_on'))) {
            $this->created_on  = gmdate('Y-m-d H:i:s');
        }
    }
}