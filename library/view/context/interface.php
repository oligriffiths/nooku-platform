<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * View Context Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\View
 */
interface ViewContextInterface extends CommandInterface
{
    /**
     * Get the view data
     *
     * @return array
     */
    public function getData();
}