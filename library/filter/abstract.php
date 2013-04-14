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
     * The filter chain
     *
     * @var	FilterChain
     */
    protected $_chain = null;

    /**
     * The filter errors
     *
     * @var	array
     */
    protected $_errors = array();

    /**
     * Constructor
     *
     * @param 	object	$config An optional Config object with configuration options
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        $this->_chain = $this->getService('lib:filter.chain');
    }

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
            $manager->set($config->service_identifier, $instance);
        }

        return $manager->get($config->service_identifier);
    }

    /**
     * Validate a scalar or traversable data
     *
     * NOTE: This should always be a simple yes/no question (is $data valid?), so only true or false should be returned
     *
     * @param   mixed   $data Value to be validated
     * @return  bool    True when the data is valid. False otherwise.
     */
    public function validate($data)
    {
        $result = true;

        //Run the filter chain
        if(!$this->_chain->isEmpty())
        {
            $result = $this->_chain->validate($data);

            //Get the errors
            if($result === false) {
                $this->_errors += $this->_chain->getErrors();
            }
        }

        //Validate the value
        try
        {
            if($this->_validate($data) === false) {
                $result = false;
            };
        }
        catch(FilterException $e)
        {
            $this->_errors[] = $e;
            $result = false;
        }


        return $result;
    }

    /**
     * Sanitize a scalar or traversable data
     *
     * @param   mixed   $data Value to be sanitized
     * @return  mixed   The sanitized value
     */
    public function sanitize($data)
    {
        //Run the filter chain
        if(!$this->_chain->isEmpty())
        {
            $data = $this->_chain->sanitize($data);

            //Get the errors
            if($data === false) {
                $this->_errors+= $this->_chain->getErrors();
            }
        }

        //Sanitize the value
        try
        {
            $data = $this->_sanitize($data);
        }
        catch(FilterException $e)
        {
            $this->_errors[] = $e;
            $data = false;
        }

        return $data;
    }

    /**
     * Add a filter based on priority
     *
     * @param FilterInterface 	$filter A Filter
     * @param integer	        $priority The command priority, usually between 1 (high priority) and 5 (lowest),
     *                                    default is 3. If no priority is set, the command priority will be used
     *                                    instead.
     *
     * @return FilterAbstract
     */
    public function addFilter(FilterInterface $filter, $priority = null)
    {
        $this->_chain->addFilter($filter, $priority);
        return $this;
    }

    /**
     * Get a handle for this object
     *
     * This function returns an unique identifier for the object. This id can be used as a hash key for storing objects
     * or for identifying an object
     *
     * @return string A string that is unique
     */
    public function getHandle()
    {
        return spl_object_hash( $this );
    }

    /**
     * Get a list of error that occurred during sanitize or validate
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Validate a variable
     *
     * Variable passed to this function will always be a scalar
     *
     * @param	scalar	$value Value to be validated
     * @return	bool	True when the value is valid. False otherwise.
     */
    abstract protected function _validate($value);

    /**
     * Sanitize a variable only
     *
     * Variable passed to this function will always be a scalar
     *
     * @param	scalar	$value Value to be sanitized
     * @return	mixed
     */
    abstract protected function _sanitize($value);
}