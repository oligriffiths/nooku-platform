<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Model Controller
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerModel extends KControllerModel
{
	/**
	 * The limit information
	 *
	 * @var	array
	 */
	protected $_limit;

	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_limit = $config->limit;
	}

	/**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'toolbars' => array('menubar', $this->getIdentifier()->name),
            'limit'    => array('max' => 100, 'default' => $this->getService('application')->getCfg('list_limit'))
        ));

        parent::_initialize($config);
    }

 	/**
     * Read action
     *
     * This functions implements an extra check to hide the main menu is the view name
     * is singular (item views)
     *
     *  @return KDatabaseRow    A row object containing the selected row
     */
    protected function _actionRead(KCommandContext $context)
    {
        //Perform the read action
        $row = parent::_actionRead($context);

        //Add the notice if the row is locked
        if(isset($row))
        {
            if($row->isLockable() && $row->locked()) {
                $this->getService('application')->enqueueMessage($row->lockMessage(), 'notice');
            }
        }

        return $row;
    }

	/**
     * Browse action
     *
     * Use the application default limit if no limit exists in the model and limit the limit to a maximum.
     *
     * @param   KCommandContext A command context object
     * @return  KDatabaseRow(set)   A row(set) object containing the data to display
     */
    protected function _actionBrowse(KCommandContext $context)
    {
        if($this->isDispatched())
        {
            $limit = $this->getModel()->get('limit');

            //If limit is empty use default
            if(empty($limit)) {
                $limit = $this->_limit->default;
            }

            //Force the maximum limit
            if($limit > $this->_limit->max) {
                $limit = $this->_limit->max;
            }

            $this->getModel()->set('limit', $limit);
        }

        return parent::_actionBrowse($context);
    }

    /**
     * Render action
     *
     * @param   KCommandContext A command context object
     * @return  KDatabaseRow(set)   A row(set) object containing the data to display
     */
    protected function _actionRender(KCommandContext $context)
    {
        JFactory::getLanguage()->load($this->getIdentifier()->package);
        return parent::_actionRender($context);
    }
}