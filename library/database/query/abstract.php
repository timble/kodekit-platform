<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Database Query
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
abstract class DatabaseQueryAbstract extends Object implements DatabaseQueryInterface
{
    /**
     * Database adapter
     *
     * @var     object
     */
    protected $_adapter;

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

        $this->_adapter = $config->adapter;
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
            'adapter'     => 'lib:database.adapter.mysql',
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
     * @return DatabaseAdapterInterface
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
     * Gets the database adapter
     *
     * @throws	\UnexpectedValueException	If the adapter doesn't implement DatabaseAdapterInterface
     * @return DatabaseAdapterInterface
     */
    public function getAdapter()
    {
        if(!$this->_adapter instanceof DatabaseAdapterInterface)
        {
            $this->_adapter = $this->getObject($this->_adapter);

            if(!$this->_adapter instanceof DatabaseAdapterInterface)
            {
                throw new \UnexpectedValueException(
                    'Adapter: '.get_class($this->_adapter).' does not implement DatabaseAdapterInterface'
                );
            }
        }

        return $this->_adapter;
    }

    /**
     * Set the database adapter
     *
     * @param DatabaseAdapterInterface $adapter
     * @return DatabaseQueryInterface
     */
    public function setAdapter(DatabaseAdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
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
            $replacement = $this->getAdapter()->quoteValue($value);
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
}
