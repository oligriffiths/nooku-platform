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
 * Contacts Package Activity Database Row Strategy
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class DatabaseRowActivityStrategyPackageContacts extends DatabaseRowActivityStrategyDefault
{
    protected function _getString()
    {
        return '{actor} {action} {object} {name}';
    }

    protected function _setName(Library\ObjectConfig $config)
    {
        $this->_setTitle($config);
    }
}