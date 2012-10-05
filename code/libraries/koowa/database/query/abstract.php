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
 * Abstract database query class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Query
 */
abstract class KDatabaseQueryAbstract extends KObject implements KDatabaseQueryInterface
{
    /**
     * Database connector
     *
     * @var     object
     */
    protected $_adapter;

    /**
     * Parameters to bind.
     *
     * @var array
     */
    public $params = array();

    /**
     * Constructor
     *
     * @param KConfig|null $config  An optional KConfig object with configuration options
     * @return \KObjectDecorator
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        if ($config->adapter instanceof KDatabaseAdapterInterface) {
            $this->setAdapter($config->adapter);
        }
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
            'adapter' => KService::get('koowa:database.adapter.mysqli')
        ));
    }

    /**
     * Gets the database adapter
     *
     * @return \KDatabaseAdapterInterface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     * Set the database adapter
     *
     * @param \KDatabaseAdpaterInterface A KDatabaseAdapterInterface object
     * @return \KDatabaseQueryInterface
     */
    public function setAdapter(KDatabaseAdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
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
            $this->params[$key] = $value;
        }

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

        if($this->params[$key] instanceof KDatabaseQuerySelect) {
            $replacement = '('.$this->params[$key].')';
        } else {
            $replacement = $this->getAdapter()->quoteValue($this->params[$key]);
        }

        return is_array($this->params[$key]) ? '(' . $replacement . ')' : $replacement;
    }
}
