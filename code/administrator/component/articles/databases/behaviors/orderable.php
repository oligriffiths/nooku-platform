<?php
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Orderable Database Behavior Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesDatabaseBehaviorOrderable extends KDatabaseBehaviorOrderable
{     
    public function _buildQueryWhere($query)
    {
        parent::_buildQueryWhere($query);
        
        if ($this->getMixer()->getIdentifier()->name == 'article') 
        {
            $query->where('categories_category_id = :category')
                  ->where('published >= :published')
                  ->bind(array('category' => $this->category_id, 'published' => 0));
        }
    }
}