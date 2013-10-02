<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Component\Activities;
use Nooku\Library;

/**
 * Loggable Controller Behavior
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class ActivitiesControllerBehaviorLoggable extends Activities\ControllerBehaviorLoggable
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array('application' => 'site'));
        parent::_initialize($config);
    }
}