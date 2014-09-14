<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Filter
 *
 * If the filter implements FilterTraversable it will be decorated with FilterIterator to allow iterating over the data
 * being filtered in case of an array of a Traversable object. If a filter does not implement FilterTraversable the data
 * will be passed directly to the filter.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Filter
 */
abstract class FilterAbstract extends Object implements FilterInterface, ObjectInstantiable
{
    /**
     * The filter errors
     *
     * @var	array
     */
    protected $_errors = array();

    /**
     * The filter priority
     *
     * @var integer
     */
    protected $_priority;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_priority = $config->priority;

        foreach($config as $key => $value)
        {
            if(property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_NORMAL,
        ));

        parent::_initialize($config);
    }

    /**
     * Instantiate the filter and decorate it if it implements the FilterTraversable interface.
     *
     * @param   ObjectConfig            $config   A ObjectConfig object with configuration options
     * @param   ObjectManagerInterface	$manager  A ObjectInterface object
     * @return FilterInterface
     * @see KFilterTraversable
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager)
    {
        $instance = new static($config);

        if($instance instanceof FilterTraversable) {
            $instance = $instance->decorate('lib:filter.iterator');
        }

        return $instance;
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
     * Get the priority of the filter
     *
     * @return  integer The priority level
     */
    public function getPriority()
    {
        return $this->_priority;
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