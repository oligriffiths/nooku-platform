<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Application;

/**
 * Html Page View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationViewPageHtml extends Application\ViewPageHtml
{
    /**
     * Get the title
     *
     * @return 	string 	The title of the view
     */
    public function getTitle()
    {
        $title = '';

        //Get the parameters of the active menu item
        $page   = $this->getObject('application.pages')->getActive();
        $params = new JParameter($page->params);

        if($params->get('page_title')) {
            $title = $params->get('page_title');
        }

        return $title;
    }
}