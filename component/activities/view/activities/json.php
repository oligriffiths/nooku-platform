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
 * Activities JSON View Class
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 * @see 	http://activitystrea.ms/specs/json/1.0/
 */
class ViewActivitiesJson extends Library\ViewJson
{
    /**
     * Get the list data
     *
     * @return array The array with data to be encoded to json
     */
    protected function _getRowset()
    {
        //Get the model
        $model = $this->getModel();

        //Get the route
        $route = $this->getRoute();

        //Get the model state
        $state = $model->getState();

        //Get the model paginator
        $paginator = $model->getPaginator();

        $vars = array();
        foreach ($state->toArray() as $var)
        {
            if (!$var->unique) {
                $vars[] = $var->name;
            }
        }

        $data = array(
            'href' => (string)$route->setQuery($state->getValues(), true),
            'url' => array(
                'type' => 'application/json',
                'template' => (string)$route->toString(HttpUrl::BASE) . '?{&' . implode(',', $vars) . '}',
            ),
            'offset' => (int)$paginator->offset,
            'limit' => (int)$paginator->limit,
            'total' => 0,
            'items' => array(),
            'queries' => array()
        );

        if ($list = $model->getRowset())
        {
            $vars = array();
            foreach ($state->toArray() as $var)
            {
                if ($var->unique)
                {
                    $vars[] = $var->name;
                    $vars = array_merge($vars, $var->required);
                }
            }

            $name = StringInflector::singularize($this->getName());

            $items = array();
            foreach ($list as $item)
            {
                $id = $item->getIdentityColumn();

                $items[] = array(
                    'href' => (string)$this->getRoute('view=' . $name . '&id=' . $item->{$id}),
                    'url' => array(
                        'type' => 'application/json',
                        'template' => (string)$this->getRoute('view=' . $name) . '?{&' . implode(',', $vars) . '}',
                    ),
                    'data' => ($this->getLayout() == 'stream') ? $item->getStreamData() : $item->toArray()
                );
            }

            $queries = array();
            foreach (array('first', 'prev', 'next', 'last') as $offset)
            {
                $page = $paginator->pages->{$offset};
                if ($page->active) {
                    $queries[] = array(
                        'rel' => $page->rel,
                        'href' => (string)$this->getRoute('limit=' . $page->limit . '&offset=' . $page->offset)
                    );
                }
            }

            $data = array_merge($data, array(
                'total' => $paginator->total,
                'items' => $items,
                'queries' => $queries
            ));
        }

        return $data;
    }
}
