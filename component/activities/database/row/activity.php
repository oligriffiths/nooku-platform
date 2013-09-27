<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activities Database Row
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class DatabaseRowActivity extends Library\DatabaseRowTable implements DatabaseRowActivityInterface
{
    /**
     * @var array A list of required columns.
     */
    protected $_required = array('package', 'name', 'action', 'title', 'status');

    public function save()
    {
        if (!in_array($this->application, array('admin', 'site')))
        {
            $this->setStatus(Library\Database::STATUS_FAILED);
            $this->setStatusMessage('Invalid application value');
            return false;
        }

        if (!in_array($this->type, array('com')))
        {
            $this->setStatus(Library\Database::STATUS_FAILED);
            $this->setStatusMessage('Invalid type value');
            return false;
        }

        if (!$this->status)
        {
            // Attempt to provide a default status.
            switch ($this->action)
            {
                case 'add':
                    $status = Library\Database::STATUS_CREATED;
                    break;
                case 'edit':
                    $status = Library\Database::STATUS_UPDATED;
                    break;
                case 'delete':
                    $status = Library\Database::STATUS_DELETED;
                    break;
                default:
                    $status = null;
            }

            if ($status)
            {
                $this->status = $status;
            }
        }

        foreach ($this->_required as $column)
        {
            if (empty($this->$column))
            {
                $this->setStatus(Library\Database::STATUS_FAILED);
                $this->setStatusMessage('Missing required data');
                return false;
            }
        }

        if ($this->isModified('metadata') && !is_null($this->metadata))
        {
            // Encode meta data.
            $metadata = json_encode($this->metadata);

            if ($metadata === false)
            {
                $this->setStatus(Library\Database::STATUS_FAILED);
                $this->setStatusMessage('Unable to encode meta data');
                return false;
            }

            $this->metadata = $metadata;
        }

        return parent::save();
    }

    public function __get($key)
    {
        $value = parent::__get($key);

        if ($key == 'metadata' && is_string($value))
        {
            // Try to decode it.
            $metadata = json_decode($value);
            if ($metadata !== null)
            {
                $value = $metadata;
            }
        }

        return $value;
    }

    /**
     * Strategy getter.
     *
     * @return DatabaseRowActivityStrategyInterface|null The row strategy, null if the
     * current row object is new or modified.
     */
    public function getStrategy()
    {
        $strategy = null;

        if (!$this->isNew() && !$this->getModified())
        {
            $identifier       = clone $this->getIdentifier();
            $identifier->path = array('database', 'row', 'activity', 'strategy');
            $identifier->name = $this->package;

            $strategy = $this->getObject($identifier, array('row' => $this));
        }

        return $strategy;
    }

    /**
     * @see DatabaseRowActivityInterface::toString()
     */
    public function toString($html = true)
    {
        $string = '';

        if ($strategy = $this->getStrategy())
        {
            // Delegate task to strategy.
            $string = $strategy->toString($html);
        }

        return $string;
    }

    /**
     * @see DatabaseRowActivityInterface::getStreamData()
     */
    public function getStreamData()
    {
        $data = array();

        if ($strategy = $this->getStrategy())
        {
            // Delegate task to strategy.
            $data = $strategy->getStreamData();
        }

        return $data;
    }
}
