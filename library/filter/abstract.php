<?php
/**
 * @package      Koowa_Filter
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link 		http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Abstract Filter
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 */
abstract class FilterAbstract extends Object implements FilterInterface, ServiceInstantiatable
{
    /**
     * Priority levels
     */
    const PRIORITY_HIGHEST = 1;
    const PRIORITY_HIGH    = 2;
    const PRIORITY_NORMAL  = 3;
    const PRIORITY_LOW     = 4;
    const PRIORITY_LOWEST  = 5;

    /**
     * The filter errors
     *
     * @var	array
     */
    protected $_errors = array();

    /**
     * Force creation of a singleton
     *
     * @param 	Config                  $config	  A Config object with configuration options
     * @param 	ServiceManagerInterface	$manager  A ServiceInterface object
     * @return FilterInterface
     */
    public static function getInstance(Config $config, ServiceManagerInterface $manager)
    {
        if (!$manager->has($config->service_identifier))
        {
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);

            if(array_key_exists(__NAMESPACE__.'\FilterTraversable', class_implements($classname, false))) {
                $instance = $manager->get('lib:filter.iterator', array('object' => $instance));
            }

            $manager->set($config->service_identifier, $instance);
        }

        return $manager->get($config->service_identifier);
    }

    /**
     * Validate a scalar or traversable value
     *
     * NOTE: This should always be a simple yes/no question (is $value valid?), so only true or false should be returned
     *
     * @param   mixed   $value Value to be validated
     * @return  bool    True when the value is valid. False otherwise.
     */
    public function validate($value)
    {
        return false;
    }

    /**
     * Sanitize a scalar or traversable value
     *
     * @param   mixed   $value Value to be sanitized
     * @return  mixed   The sanitized value
     */
    public function sanitize($value)
    {
        return $value;
    }

    /**
     * Get a list of error that occurred during sanitize or validate
     *
     * @return array
     */
    public function getErrors()
    {
        return (array) $this->_errors;
    }

    /**
     * Add an error message
     *
     * @param $message
     */
    protected function _error($message)
    {
        $this->_errors[] = $message;
        return false;
    }
}