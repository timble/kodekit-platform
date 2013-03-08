<?php
/**
 * @package     Koowa_Database
 * @subpackage  Query
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Abstract Database Query Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Query
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
    protected $_params;

    /**
     * Constructor
     *
     * @param Config|null $config  An optional Config object with configuration options
     * @return ObjectDecorator
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        $this->_adapter = $config->adapter;
        $this->_params  = $config->params;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Config $object An optional Config object with configuration options
     * @return  void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'adapter' => 'lib://nooku/database.adapter.mysql',
            'params'  => 'lib://nooku/object.array'
        ));
    }

    /**
     * Bind values to a corresponding named placeholders in the query.
     *
     * @param  array $params Associative array of parameters.
     * @return DatabaseQueryInterface
     */
    public function bind(array $params)
    {
        foreach ($params as $key => $value) {
            $this->getParams()->set($key, $value);
        }

        return $this;
    }

    /**
     * Get the query parameters
     *
     * @throws	\UnexpectedValueException	If the params doesn't implement ObjectArray
     * @return ObjectArray
     */
    public function getParams()
    {
        if(!$this->_params instanceof ObjectArray)
        {
            $this->_params = $this->getService($this->_params);

            if(!$this->_params instanceof ObjectArray)
            {
                throw new \UnexpectedValueException(
                    'Params: '.get_class($this->_params).' does not implement ObjectArray'
                );
            }
        }

        return $this->_params;
    }

    /**
     * Set the query parameters
     *
     * @param ObjectArray $params  The query parameters
     * @return DatabaseQueryAbstract
     */
    public function setParams(ObjectArray $params)
    {
        $this->_params = $params;
        return $this;
    }

    /**
     * Gets the database adapter
     *
     * @throws	\UnexpectedValueException	If the adapter doesn't implement DatabaseAdapterInterface
     * @return \DatabaseAdapterInterface
     */
    public function getAdapter()
    {
        if(!$this->_adapter instanceof DatabaseAdapterInterface)
        {
            $this->_adapter = $this->getService($this->_adapter);

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
     * @param DatabaseAdpaterInterface $adapter
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
    protected function _replaceParams($query)
    {
        return preg_replace_callback('/(?<!\w):\w+/', array($this, '_replaceParamsCallback'), $query);
    }

    /**
     * Callback method for parameter replacement.
     *
     * @param  array  $matches Matches of preg_replace_callback.
     * @return string The replaced string.
     */
    protected function _replaceParamsCallback($matches)
    {
        $key   = substr($matches[0], 1);
        $value = $this->_params[$key];

        if(!$value instanceof DatabaseQuerySelect) {
            $value = is_object($value) ? (string) $value : $value;
            $replacement = $this->getAdapter()->quoteValue($value);
        }
        else $replacement = '('.$value.')';

        return is_array($value) ? '('.$replacement.')' : $replacement;
    }

    /**
     * Get a property
     *
     * Implement a virtual 'params' property to return the params object.
     *
     * @param   string $name  The property name.
     * @return  string $value The property value.
     */
    public function __get($name)
    {
        if($name = 'params') {
            return $this->getParams();
        }

        return parent::__get($name);
    }
}
