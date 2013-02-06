<?php
/**
 * @version     $Id$
 * @package     Koowa_Database
 * @subpackage  Query
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Abstract Database Query Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Query
 */
abstract class KDatabaseQueryAbstract extends KObject implements KDatabaseQueryInterface
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
     * @param KConfig|null $config  An optional KConfig object with configuration options
     * @return \KObjectDecorator
     */
    public function __construct(KConfig $config)
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
     * @param   KConfig $object An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'adapter' => 'koowa:database.adapter.mysql',
            'params'  => 'koowa:object.array'
        ));
    }

    /**
     * Bind values to a corresponding named placeholders in the query.
     *
     * @param  array $params Associative array of parameters.
     * @return \KDatabaseQueryInterface
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
     * @throws	\UnexpectedValueException	If the params doesn't implement KObjectArray
     * @return KObjectArray
     */
    public function getParams()
    {
        if(!$this->_params instanceof KObjectArray)
        {
            $this->_params = $this->getService($this->_params);

            if(!$this->_params instanceof KObjectArray)
            {
                throw new \UnexpectedValueException(
                    'Params: '.get_class($this->_params).' does not implement KObjectArray'
                );
            }
        }

        return $this->_params;
    }

    /**
     * Set the query parameters
     *
     * @param KObjectArray $params  The query parameters
     * @return KDatabaseQueryAbstract
     */
    public function setParams(KObjectArray $params)
    {
        $this->_params = $params;
        return $this;
    }

    /**
     * Gets the database adapter
     *
     * @throws	\UnexpectedValueException	If the adapter doesn't implement KDatabaseAdapterInterface
     * @return \KDatabaseAdapterInterface
     */
    public function getAdapter()
    {
        if(!$this->_adapter instanceof KDatabaseAdapterInterface)
        {
            $this->_adapter = $this->getService($this->_adapter);

            if(!$this->_adapter instanceof KDatabaseAdapterInterface)
            {
                throw new \UnexpectedValueException(
                    'Adapter: '.get_class($this->_adapter).' does not implement KDatabaseAdapterInterface'
                );
            }
        }

        return $this->_adapter;
    }

    /**
     * Set the database adapter
     *
     * @param \KDatabaseAdpaterInterface $adapter
     * @return \KDatabaseQueryInterface
     */
    public function setAdapter(KDatabaseAdapterInterface $adapter)
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
        $key = substr($matches[0], 1);

        if($this->_params[$key] instanceof KDatabaseQuerySelect) {
            $replacement = '('.$this->_params[$key].')';
        } else {
            $replacement = $this->getAdapter()->quoteValue($this->_params[$key]);
        }

        return is_array($this->_params[$key]) ? '(' . $replacement . ')' : $replacement;
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
