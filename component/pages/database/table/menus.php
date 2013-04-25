<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Menus Database Table
 *
 * @author  Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package Nooku\Component\Pages
 */
class DatabaseTableMenus extends Library\DatabaseTableDefault
{
    public function  _initialize(Library\Config $config)
    {		
        $config->append(array(
            'behaviors'  => array(
                'creatable', 'modifiable', 'lockable', 'sluggable'
            )
            ));
     
        parent::_initialize($config);
    }
}