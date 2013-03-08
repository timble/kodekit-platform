<?php
/**
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Framework;

/**
 * Filter Factory
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 */
class FilterFactory extends Object implements ServiceInstantiatable
{
	/**
     * Force creation of a singleton
     *
     * @param 	Config                  $config	  A Config object with configuration options
     * @param 	ServiceManagerInterface	$manager  A ServiceInterface object
     * @return FilterFactory
     */
    public static function getInstance(Config $config, ServiceManagerInterface $manager)
    {
        if (!$manager->has($config->service_identifier))
        {
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->service_identifier, $instance);
        }

        return $manager->get($config->service_identifier);
    }

	/**
	 * Factory method for FilterInterface classes.
	 *
	 * @param	string 	Filter indentifier
	 * @param 	object 	An optional Config object with configuration options
	 * @return FilterAbstract
	 */
	public function instantiate($identifier, $config = array())
	{
		//Get the filter(s) we need to create
		$filters = (array) $identifier;

		//Create the filter chain
		$filter = array_shift($filters);
		$filter = $this->_createFilter($filter, $config);

		foreach($filters as $name) {
			$filter->addFilter(self::_createFilter($name, $config));
		}

		return $filter;
	}

	/**
	 * Create a filter based on it's name
	 *
	 * If the filter is not an identifier this function will create it directly instead of going through
     * the Service identification process.
	 *
	 * @param 	string	Filter identifier
	 * @throws	\InvalidArgumentException	When the filter could not be found
     * @throws	\UnexpectedValueException	When the filter does not implement FilterInterface
	 * @return  FilterInterface
	 */
	protected function _createFilter($filter, $config)
	{
		try
		{
			if(is_string($filter) && strpos($filter, '.') === false ) {
				$filter = 'com:base.filter.'.trim($filter);
			}

			$filter = $this->getService($filter, $config);

		} catch(KServiceServiceException $e) {
			throw new \InvalidArgumentException('Invalid filter: '.$filter);
		}

	    //Check the filter interface
		if(!($filter instanceof FilterInterface)) {
			throw new \UnexpectedValueException('Filter:'.get_class($filter).' does not implement FilterInterface');
		}

		return $filter;
	}
}