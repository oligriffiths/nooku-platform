<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Loggable Controller Behavior
 *
 * @author  Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class ControllerBehaviorLoggable extends Library\ControllerBehaviorAbstract
{
    /**
     * List of actions to log
     *
     * @var array
     */
    protected $_actions;

    /**
     * The name of the column to use as the title column in the log entry
     *
     * @var string
     */
    protected $_title_column;

    /**
     * Activity controller identifier.
     *
     * @var string|Library\ObjectIdentifierInterface
     */
    protected $_activity_controller;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_actions             = Library\ObjectConfig::unbox($config->actions);
        $this->_title_column        = Library\ObjectConfig::unbox($config->title_column);
        $this->_activity_controller = $config->activity_controller;

    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority'     => Library\Command::PRIORITY_LOWEST,
            'actions'      => array('after.edit', 'after.add', 'after.delete'),
            'title_column' => array('title', 'name'),
            'activity_controller' => array(
                'identifier' => 'com:activities.controller.activity',
                'config'     => array())
        ));

        parent::_initialize($config);
    }

    public function execute($name, Library\CommandContext $context)
    {
        if (in_array($name, $this->_actions)) {

            $parts = explode('.', $name);

            // Properly fetch data for the event.
            if ($parts[0] == 'before') {
                $data = $this->getMixer()->getModel()->getData();
            } else {
                $data = $context->result;
            }

            if ($data instanceof Library\DatabaseRowInterface || $data instanceof Library\DatabaseRowsetInterface) {
                $rowset = array();

                if ($data instanceof Library\DatabaseRowInterface) {
                    $rowset[] = $data;
                } else {
                    $rowset = $data;
                }

                foreach ($rowset as $row) {
                    //Only log if the row status is valid.
                    $status = $this->_getStatus($row, $name);

                    if (!empty($status) && $status !== Library\Database::STATUS_FAILED) {
                        $this->getObject($this->_activity_controller->identifier,
                            Library\ObjectConfig::unbox($this->_activity_controller->config))->add($this->_getActivityData($row,
                                $status, $context));
                    }
                }
            }
        }
    }

    /**
     * Activity data getter.
     *
     * @param Library\DatabaseRowAbstract $row     The data row.
     * @param                             string   The row status.
     * @param Library\CommandContext      $context The command context.
     *
     * @return array Activity data.
     */
    protected function _getActivityData(Library\DatabaseRowInterface $row, $status, Library\CommandContext $context)
    {

        $identifier = $this->getActivityIdentifier($context);

        $activity = array(
            'action'      => $context->action,
            'application' => $identifier->application,
            'package'     => $identifier->package,
            'name'        => $identifier->name,
            'status'      => $status
        );

        if (is_array($this->_title_column)) {
            foreach ($this->_title_column as $title) {
                if ($row->{$title}) {
                    $activity['title'] = $row->{$title};
                    break;
                }
            }
        } elseif ($row->{$this->_title_column}) {
            $activity['title'] = $row->{$this->_title_column};
        }

        if (!isset($activity['title'])) {
            $activity['title'] = '#' . $row->id;
        }

        $activity['row'] = $row->id;

        return $activity;
    }

    /**
     * Status getter.
     *
     * @param Library\DatabaseRowInterface $row       The row object.
     * @param string                       $action    The command action being executed.
     */
    protected function _getStatus(Library\DatabaseRowInterface $row, $action)
    {
        $status = $row->getStatus();

        // Commands may change the original status of an action.
        if ($action == 'after.add' && $status == Library\Database::STATUS_UPDATED) {
            $status = Library\Database::STATUS_CREATED;
        }

        return $status;
    }

    /**
     * Activity identifier getter.
     *
     * @param Library\CommandContext $context The command context object.
     *
     * @return Library\ObjectIdentifier The activity identifier.
     */
    public function getActivityIdentifier(Library\CommandContext $context)
    {
        return $context->getSubject()->getIdentifier();
    }

    public function getHandle()
    {
        return Library\ObjectMixinAbstract::getHandle();
    }
}