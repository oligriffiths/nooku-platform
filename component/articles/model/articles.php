<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Articles;

use Nooku\Library;

/**
 * Articles Model Class
 *
 * @author  John Bell <http://nooku.assembla.com/profile/johnbell>
 * @package Nooku\Component\Articles
 */
class ModelArticles extends Library\ModelTable
{
    public function __construct(Library\Config $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('category'         , 'slug')
            ->insert('category_recurse' , 'boolean', false)
            ->insert('published' , 'int')
            ->insert('created_by', 'int')
            ->insert('access'    , 'int')
            ->insert('trashed'   , 'int')
            ->insert('searchword', 'string');

        $this->getState()->remove('sort')->insert('sort', 'cmd', 'ordering');
    }

    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $query->columns(array(
            'category_title'         => 'categories.title',
            'thumbnail'              => 'thumbnails.thumbnail',
            'last_activity_on'       => 'IF(tbl.modified_on, tbl.modified_on, tbl.created_on)',
            'last_activity_by_name'  => 'IF(tbl.modified_on, modifier.name, creator.name)',
            'ordering_date'          => 'IF(tbl.publish_on, tbl.publish_on, tbl.created_on)'
        ));
    }

    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);

        $query->join(array('categories' => 'categories'), 'categories.categories_category_id = tbl.categories_category_id')
              ->join(array('creator'  => 'users'), 'creator.users_user_id = tbl.created_by')
              ->join(array('modifier'  => 'users'), 'modifier.users_user_id = tbl.modified_by')
              ->join(array('thumbnails'  => 'files_thumbnails'), 'thumbnails.filename = tbl.image');
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        
        $state = $this->getState();

        if (is_numeric($state->published)) {
        	$query->where('tbl.published = :published')->bind(array('published' => (int) $state->published));
        }

        if ($state->search) {
            $query->where('(tbl.title LIKE :search)')->bind(array('search' => '%' . $state->search . '%'));
        }

        if ($state->searchword) {
            $query->where('(tbl.title LIKE :search OR tbl.introtext LIKE :search OR tbl.fulltext LIKE :search)')->bind(array('search' => '%' . $state->searchword . '%'));
        }

        if(is_numeric($state->category) || $state->category)
        {
            if($state->category)
            {
            	$query->where('tbl.categories_category_id IN :categories_category_id' );
            	
	            if($state->category_recurse === true) {
	                $query->where('categories.parent_id IN :categories_category_id', 'OR');
	            }
	
	            $query->bind(array('categories_category_id' => (array) $state->category));
            }
            else $query->where('tbl.categories_category_id IS NULL');
        }

        if($state->created_by) {
            $query->where('tbl.created_by = :created_by')->bind(array('created_by' => $state->created_by));
        }

        if($this->getTable()->isRevisable() && $state->trashed) {
            $query->bind(array('deleted' => 1));
        }

        if (is_numeric($state->access)) {
            $query->where('tbl.access <= :access')->bind(array('access' => $state->access));
        }
    }

    protected function _buildQueryOrder(Library\DatabaseQuerySelect $query)
    {
        $state = $this->getState();

        $direction = strtoupper($state->direction);

        if ($state->sort == 'ordering')
        {
            $query->order('category_title', 'ASC')
                  ->order('ordering', $direction);
        }
        else
        {
            $query->order($state->sort, $direction)
                  ->order('category_title', 'ASC')
                  ->order('ordering', 'ASC');
        }
    }
}