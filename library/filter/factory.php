<?php
/**
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Library;

/**
 * Filter Factory
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 */
class FilterFactory extends Object implements ObjectInstantiatable
{
	/**
     * Force creation of a singleton
     *
     * @param 	Config                  $config	  A Config object with configuration options
     * @param 	ObjectManagerInterface	$manager  A ObjectInterface object
     * @return FilterFactory
     */
    public static function getInstance(Config $config, ObjectManagerInterface $manager)
    {
        if (!$manager->has($config->object_identifier))
        {
            $classname = $config->object_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->object_identifier, $instance);
        }

        return $manager->get($config->object_identifier);
    }

	/**
	 * Factory method for FilterInterface classes.
     *
     * Method accepts an array of filter names, or filter service identifiers and will create a chained filter
     * using a FIFO approach.
	 *
	 * @param	string|array $identifier Filter identifier(s)
	 * @param 	object 	     $config     An optional Config object with configuration options
	 * @return  FilterInterface
	 */
	public function getFilter($identifier, $config = array())
	{
		//Get the filter(s) we need to create
		$filters = (array) $identifier;

        //Create a filter chain
        if(count($filters) > 1)
        {
            $filter = $this->getObject('lib:filter.chain');

            foreach($filters as $name)
            {
                $instance = $this->_instantiate($name, $config);
                $filter->addFilter($instance);
            }
        }
        else $filter = $this->_instantiate($filters[0], $config);

		return $filter;
	}

	/**
	 * Create a filter based on it's name
	 *
	 * If the filter is not an identifier this function will create it directly instead of going through the Object
     * identification process.
	 *
	 * @param 	string	$filter Filter identifier
     * @param   array   $config An array of configuration options.
	 * @throws	\InvalidArgumentException	When the filter could not be found
     * @throws	\UnexpectedValueException	When the filter does not implement FilterInterface
	 * @return  FilterInterface
	 */
	protected function _instantiate($filter, $config)
	{
		try
		{
			if(is_string($filter) && strpos($filter, '.') === false ) {
				$filter = 'lib:filter.'.trim($filter);
			}

			$filter = $this->getObject($filter, $config);

		} catch(ObjectException $e) {
			throw new \InvalidArgumentException('Invalid filter: '.$filter);
		}

	    //Check the filter interface
		if(!($filter instanceof FilterInterface)) {
			throw new \UnexpectedValueException('Filter:'.get_class($filter).' does not implement FilterInterface');
		}

		return $filter;
	}
}