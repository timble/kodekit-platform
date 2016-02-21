<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Database Query
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Database
 */
abstract class DatabaseQueryAbstract extends Object implements DatabaseQueryInterface
{
    /**
     * Database engine
     *
     * @var DatabaseEngineInterface
     */
    protected $_engine;

    /**
     * Query parameters to bind
     *
     * @var array
     */
    protected $_parameters;

    /**
     * Constructor
     *
     * @param ObjectConfig|null $config  An optional ObjectConfig object with configuration options
     * @return ObjectDecorator
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_engine = $config->engine;
        $this->setParameters(ObjectConfig::unbox($config->parameters));
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $object An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'engine'     => 'lib:database.engine.mysql',
            'parameters'  => array()
        ));
    }

    /**
     * Bind values to a corresponding named placeholders in the query.
     *
     * @param  array $parameters Associative array of parameters.
     * @return DatabaseQueryInterface
     */
    public function bind(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $this->getParameters()->set($key, $value);
        }

        return $this;
    }

    /**
     * Set the query parameters
     *
     * @param  array $parameters
     * @return DatabaseQueryAbstract
     */
    public function setParameters(array $parameters)
    {
        $this->_parameters = $this->getObject('lib:database.query.parameters', array('parameters' => $parameters));
        return $this;
    }

    /**
     * Get the query parameters
     *
     * @return  DatabaseQueryParameters
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * Gets the database engine
     *
     * @throws	\UnexpectedValueException	If the engine doesn't implement DatabaseEngineInterface
     * @return DatabaseEngineInterface
     */
    public function getEngine()
    {
        if(!$this->_engine instanceof DatabaseEngineInterface)
        {
            $this->_engine = $this->getObject($this->_engine);

            if(!$this->_engine instanceof DatabaseEngineInterface)
            {
                throw new \UnexpectedValueException(
                    'Engine: '.get_class($this->_engine).' does not implement DatabaseEngineInterface'
                );
            }
        }

        return $this->_engine;
    }

    /**
     * Set the database engine
     *
     * @param DatabaseEngineInterface $engine
     * @return DatabaseQueryInterface
     */
    public function setEngine(DatabaseEngineInterface $engine)
    {
        $this->_engine = $engine;
        return $this;
    }

    /**
     * Replace parameters in the query string.
     *
     * @param  string $query The query string.
     * @return string The replaced string.
     */
    protected function _replaceParameters($query)
    {
        return preg_replace_callback('/(?<!\w):\w+/', array($this, '_replaceParametersCallback'), $query);
    }

    /**
     * Callback method for parameter replacement.
     *
     * @param  array  $matches Matches of preg_replace_callback.
     * @return string The replaced string.
     */
    protected function _replaceParametersCallback($matches)
    {
        $key   = substr($matches[0], 1);
        $value = $this->_parameters[$key];

        if(!$value instanceof DatabaseQuerySelect)
        {
            $value = is_object($value) ? (string) $value : $value;
            $replacement = $this->getEngine()->quoteValue($value);
        }
        else $replacement = '('.$value.')';

        return is_array($value) ? '('.$replacement.')' : $replacement;
    }

    /**
     * Get a property
     *
     * Implement a virtual 'parameters' property to return the parameters object.
     *
     * @param   string $name  The property name.
     * @return  string $value The property value.
     */
    public function __get($name)
    {
        if($name = 'parameters') {
            return $this->getParameters();
        }

        return parent::__get($name);
    }

    /**
     * Render the query to a string.
     *
     * @return  string  The query string.
     */
    final public function __toString()
    {
        return $this->toString();
    }
}
