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
 * Abstract Activity Database Row Strategy
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
abstract class DatabaseRowActivityStrategyAbstract extends Library\Object implements DatabaseRowActivityStrategyInterface
{
    /**
     * @var mixed The translator parameter identifier to instantiate.
     */
    protected $_parameter;

    /**
     * @var mixed The activity translator.
     */
    protected $_translator;

    /**
     * @var DatabaseRowActivity The activity row object.
     */
    protected $_row;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        if ($config->row )
        {
            $this->setRow($config->row);
        }

        $this->_parameter  = $config->parameter;
        $this->_translator = $config->translator;
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'parameter'  => 'com:activities.translator.parameter.default',
            'translator' => 'com:activities.translator.activity',
        ));
        parent::_initialize($config);
    }

    /**
     * Activity icon getter.
     *
     * @return string The activity icon class value.
     */
    abstract protected function _getIcon();

    /**
     * Activity string getter.
     *
     * An activity string is a compact representation of the activity text which also provides information
     * about the variables it may contain. These are used in the same way Joomla! translation keys are
     * used for translating text to other languages.
     *
     * @return string The activity string.
     */
    abstract protected function _getString();

    /**
     * URL getter.
     *
     * @param array $config An optional configuration array.
     *
     * @return string The URL.
     */
    protected function _getUrl($config = array())
    {
        $config = new Library\ObjectConfig($config);

        $config->append(array('route' => true, 'absolute' => true, 'url' => $this->getObject('request')->getUrl()));

        $url = $config->url;

        if ($config->route)
        {
            $url = $this->getObject('lib:dispatcher.router.route', array('url' => $url));
        }

        if ($config->absolute)
        {
            $url = $this->getObject('request')->getUrl()->toString(Library\HttpUrl::AUTHORITY) . $url;
        }

        return $url;
    }

    /**
     * Determines if a given resource exists.
     *
     * @param array $config An optional configuration array.
     *
     * @return bool True if it exists, false otherwise.
     */
    protected function _resourceExists($config = array())
    {
        $config = new Library\ObjectConfig($config);

        $config->append(array(
            'table'  => $this->package . '_' . Library\StringInflector::pluralize($this->name),
            'column' => $this->package . '_' . $this->name . '_' . 'id',
            'value'  => $this->row));

        $db = $this->getRow()->getTable()->getAdapter();

        $query = $this->getObject('lib:database.query.select');
        $query->columns('COUNT(*)')->table($config->table)->where($config->column . ' = :value')
        ->bind(array('value' => $config->value));

        // Need to catch exceptions here as table may not longer exist.
        try
        {
            $result = $db->select($query, Library\Database::FETCH_FIELD);
        } catch (Exception $e)
        {
            $result = 0;
        }

        return (bool) $result;
    }

    /**
     * Translator setter.
     *
     * @param TranslatorInterface $translator The activity translator.
     *
     * @return $this
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->_translator = $translator;
        return $this;
    }

    /**
     * Translator getter.
     *
     * @return TranslatorInterface The activity translator.
     */
    public function getTranslator()
    {
        if (!$this->_translator instanceof TranslatorInterface)
        {
            $this->_translator = $this->getObject($this->_translator);
        }

        return $this->_translator;
    }

    /**
     * Returns activity row column values if a matching column for the requested key is found.
     *
     * @param string $key The requested key.
     *
     * @return mixed The row column value if a matching column is found for the requested key, null otherwise.
     */
    public function __get($key)
    {
        $row = $this->getRow();
        return isset($row->{$key}) ? $row->{$key} : null;
    }
}