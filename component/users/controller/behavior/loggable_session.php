<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Component\Activities;
use Nooku\Library;

/**
 * Session Controller Loggable Behavior.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku\Component\Users
 * @subpackage Users
 */
class ControllerBehaviorLoggable_session extends Activities\ControllerBehaviorLoggable
{
    protected function _getActivityData(Library\ObjectConfig $config)
    {
        $data = parent::_getActivityData($config);

        if ($config->event == 'after.delete')
        {
            // Grab user ID from session data.
            $user = $this->getObject('com:users.model.users')->email($config->row->email)->getRow();

            if (!$user->isNew())
            {
                $data['created_by'] = $user->id;
            }
        }

        return $data;
    }
}