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
 * Activity Database Row Interface
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
interface DatabaseRowActivityInterface
{
    /**
     * Casts an activity row to a string.
     *
     * This string correspond to the message of the activity that the row represents.
     *
     * @param bool $html Whether the HTML (true) or plain text (false) version is returned.
     *
     * @return string The activity message string.
     */
    public function toString($html = true);

    /**
     * Activity stream data getter.
     *
     * @return array Associative array containing formatted activity stream data for the activity row.
     */
    public function getStreamData();
}