<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Page Json View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Application
 */
class ViewPageJson extends ViewHtml
{
    protected function _actionRender(Library\ViewContext $context)
    {
        return $this->content;
    }
}