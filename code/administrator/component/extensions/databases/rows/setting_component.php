<?php
/**
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Database Row Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Extensions
 */
class ComExtensionsDatabaseRowSetting_Component extends ComExtensionsDatabaseRowSetting
{
    /**
     * The component
     *
     * @var string
     */
    protected $_id;

    /**
     * The component table
     *
     * @var string
     */
    protected $_table;

    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_id    = $config->id;
        $this->_table = $config->table;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'id'     => '',
            'table'  => 'com://admin/extensions.database.table.components'
        ));

        parent::_initialize($config);
    }

    /**
     * Saves the settings to the database.
     *
     * @return boolean	If successfull return TRUE, otherwise FALSE
     */
    public function save()
    {
        if(!empty($this->_modified))
        {
            $row = $this->getService($this->_table)->select($this->_id, KDatabase::FETCH_ROW);
            $row->params = $this->_data;

            return (bool) $row->save();
        }

        return true;
    }

    /**
     * The setting type
     *
     * @return string 	The setting type
     */
    public function getType()
    {
        return 'component';
    }
}