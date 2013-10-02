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
 * Default Activity Database Row Strategy
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class DatabaseRowActivityStrategyDefault extends DatabaseRowActivityStrategyAbstract
{
    /**
     * @see DatabaseRowActivityStrategyAbstract::_getString()
     */
    protected function _getString()
    {
        return '{actor} {action} {object} {title}';
    }

    /**
     * @see DatabaseRowActivityStrategyInterface::getIcon()
     */
    public function getIcon()
    {
        $classes = array(
            'publish'   => 'icon-ok',
            'unpublish' => 'icon-eye-close',
            'add'       => 'icon-plus-sign',
            'edit'      => 'icon-edit',
            'delete'    => 'icon-trash',
            'archive'   => 'icon-inbox');

        // Default.
        $icon = 'icon-task';

        $action = $this->action;

        if (in_array($action, array_keys($classes)))
        {
            $icon = $classes[$action];
        }

        return $icon;
    }

    protected function _setActor(Library\ObjectConfig $config)
    {
        if ($this->actorExists())
        {
            $config->link = array('url' => $this->getActorUrl());
            $text         = $this->created_by_name;
        }
        else
        {
            $text              = $this->created_by ? 'Deleted user' : 'Guest user';
            $config->translate = true;
        }

        $config->text = $text;
    }


    protected function _setAction(Library\ObjectConfig $config)
    {
        $config->append(array(
            'text'      => $this->status,
            'translate' => true));
    }


    protected function _setObject(Library\ObjectConfig $config)
    {
        $config->append(array(
            'translate'  => true,
            'text'       => $this->name,
            'attributes' => array('class' => array('object')),
        ));
    }

    protected function _setTitle(Library\ObjectConfig $config)
    {
        $config->append(array(
            'attributes' => array(),
            'translate'  => false,
            'text'       => $this->title
        ));

        $link = $config->link;

        if (!$link->url && $this->objectExists() && ($url = $this->getObjectUrl()))
        {
            $link->url = $url;
        }

        if ($this->status == 'deleted')
        {
            $config->attributes = array('class' => array('deleted'));
        }
    }

    /**
     * @see DatabaseRowActivityStrategyInterface::setRow()
     */
    public function setRow(DatabaseRowActivity $row)
    {
        $this->_row = $row;
        return $this;
    }

    /**
     * @see DatabaseRowActivityStrategyInterface::getRow()
     */
    public function getRow()
    {
        return $this->_row;
    }

    /**
     * @see DatabaseRowActivityStrategyInterface::toString()
     */
    public function toString($html = true)
    {
        $string = $this->_getString();

        $translator = $this->getTranslator();

        $components = $translator->parse($string);

        $parameters = array();

        foreach ($components['parameters'] as $parameter)
        {
            $method = '_set' . ucfirst($parameter);

            if (method_exists($this, $method))
            {
                $config = new Library\ObjectConfig(array('link' => array()));

                call_user_func(array($this, $method), $config);

                $config->html            = $html;
                $config->label           = $parameter;
                $config->url             = $config->link->url;
                $config->link_attributes = $config->link->attributes;

                // Cleanup config object.
                unset($config->link);

                $parameters[] = $this->getObject($this->_parameter, $config->toArray());
            }
        }

        $string = $translator->translate($string, $parameters);

        return $string;
    }

    /**
     * @see DatabaseRowActivityStrategyInterface::actorExists()
     */
    public function actorExists()
    {
        return $this->_resourceExists(array('table' => 'users', 'column' => 'users_user_id', 'value' => $this->created_by));
    }

    /**
     * @see DatabaseRowActivityStrategyInterface::objectExists()
     */
    public function objectExists()
    {
        return $this->_resourceExists();
    }

    /**
     * @see DatabaseRowActivityStrategyInterface::targetExists()
     */
    public function targetExists()
    {
        // Activities don't have targets by default.
        return false;
    }

    /**
     * @see DatabaseRowActivityStrategyInterface::getActorUrl()
     */
    public function getActorUrl()
    {
        $url = null;

        if ($this->created_by)
        {
            $url = $this->_getUrl(array('url' => '?option=com_users&view=user&id=' . $this->created_by));
        }

        return $url;
    }

    /**
     * @see DatabaseRowActivityStrategyInterface::getObjectUrl()
     */
    public function getObjectUrl()
    {
        $url = null;

        if ($this->package && $this->name && $this->row)
        {
            $url = $this->_getUrl(array('url' => '?option=com_' . $this->package . '&view=' . $this->name . '&id=' . $this->row));
        }

        return $url;
    }

    /**
     * @see DatabaseRowActivityStrategyInterface::getTargetUrl()
     */
    public function getTargetUrl()
    {
        // Non-linkable as no target by default.
        return null;
    }

    /**
     * @see DatabaseRowActivityStrategyInterface::hasTarget()
     */
    public function hasTarget()
    {
        // Activities don't have targets by default.
        return false;
    }

    /**
     * @see DatabaseRowActivityStrategyInterface::getStreamData()
     */
    public function getStreamData()
    {
        $tag = 'tag:' . $this->getObject('request')->getHost();

        $data = array(
            'id'        => $tag . ',id:' . $this->uuid,
            'title'     => $this->toString(false),
            'published' => $this->getObject('lib:template.helper.date')->format(array(
                'date'   => $this->created_on,
                'format' => 'c'
            )),
            'verb'      => $this->action,
            'object'    => array(
                'id'         => $tag . ',id:' . $this->row,
                'objectType' => $this->name),
            'actor'     => array(
                'id'          => $this->created_by,
                'objectType'  => 'user',
                'displayName' => $this->created_by_name));

        if ($this->objectExists())
        {
            $data['object']['url'] = $this->getObjectUrl();
        }

        if ($this->actorExists())
        {
            $data['actor']['url'] = $this->getActorUrl();
        }

        return $data;
    }
}