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
 * Users Activity Database Row Strategy
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class DatabaseRowActivityStrategyUsers extends DatabaseRowActivityStrategyDefault
{
    public function getIcon()
    {
        $icons  = array('logged in' => 'icon-user', 'logged out' => 'icon-off');

        $status = $this->status;

        if (in_array($status, array_keys($icons)))
        {
            $icon = $icons[$status];
        }
        else
        {
            $icon = parent::getIcon();
        }

        return $icon;
    }

    protected function _getString()
    {
        $string = '{actor} {action} ';

        switch ($this->name)
        {
            case 'session':
                $string .= '{application}';
                break;
            case 'user':
                if ($this->_isActivation() || $this->_isEditOwn())
                {
                    $string .= 'own {type} {object}';
                }
                elseif ($this->_isRegistration())
                {
                    $string .= '{application}';
                }
                else
                {
                    $string .= '{object} {name}';
                }
                break;
            default:
            case 'role':
            case 'group':
                $string .= '{type} {object} {title}';
                break;
        }

        return $string;
    }

    /**
     * Tells if the current activity is an account activation.
     *
     * @return bool True if it is, false otherwise.
     */
    protected function _isActivation()
    {
        return (bool) ($this->name == 'user' && $this->action == 'edit' && $this->application == 'site' && $this->created_by == 0);
    }

    /**
     * Tells is the current activity is a user registration.
     *
     * @return bool True if it is, false otherwise.
     */
    protected function _isRegistration()
    {
        return (bool) ($this->name == 'user' && $this->action == 'add' && $this->application == 'site');
    }

    /**
     * Tells if the current activity is an own edit.
     *
     * @return bool True if it is, false otherwise.
     */
    protected function _isEditOwn()
    {
        return (bool) ($this->name == 'user' && $this->action == 'edit' && $this->row == $this->created_by);
    }

    protected function _setApplication(Library\ObjectConfig $config)
    {
        $config->append(array('text' => $this->application, 'translate' => true));
    }

    protected function _setAction(Library\ObjectConfig $config)
    {
        if ($this->_isRegistration())
        {
            $config->append(array('text' => 'registered'));
        }
        elseif ($this->_isActivation())
        {
            $config->append(array('text' => 'activated'));
        }

        parent::_setAction($config);
    }

    protected function _setObject(Library\ObjectConfig $config)
    {
        if ($this->_isActivation())
        {
            $config->append(array('text' => 'account'));
        }
        elseif ($this->_isEditOwn())
        {
            $config->append(array('text' => 'profile'));
        }

        parent::_setObject($config);
    }

    protected function _setActor(Library\ObjectConfig $config)
    {
        if ($this->_isRegistration() || $this->_isActivation())
        {
            // The actor (user) becomes the resource itself.
            $this->_setName($config);
        }
        else
        {
            parent::_setActor($config);
        }
    }

    protected function _setName(Library\ObjectConfig $config)
    {
        $this->_setTitle($config);
    }

    protected function _setType(Library\ObjectConfig $config)
    {
        $config->append(array('text' => 'user', 'translate' => true));
    }

    public function objectExists()
    {
        $config = array();

        switch ($this->name)
        {
            case 'group':
                $config['table'] = 'users_groups';
                break;
            case 'roles':
                $config['table'] = 'users_roles';
                break;
        }

        return $this->_resourceExists($config);
    }
}