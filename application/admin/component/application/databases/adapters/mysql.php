<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Application Database MySQLi Adapter Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationDatabaseAdapterMysql extends Framework\DatabaseAdapterMysql implements Framework\ServiceInstantiatable
{
    /**
	 * The cache object
	 *
	 * @var	JCache
	 */
    protected $_cache;

	/**
	 * Constructor
	 *
	 * Prevent creating instances of this class by making the contructor private
	 *
	 * @param 	object 	An optional Framework\Config object with configuration options
	 */
	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);

		if(JFactory::getConfig()->getValue('config.caching')) {
	        $this->_cache = JFactory::getCache('database', 'output');
		}

        //Auto connect to the database
        $this->connect();
	}

	/**
     * Force creation of a singleton
     *
     * @param 	Config                  $config  An optional Config object with configuration options
     * @param 	ServiceManagerInterfac  $manager A Framework\ServiceManagerInterface object
     * @return  DatabaseTableInterface
     */
    public static function getInstance(Framework\Config $config, Framework\ServiceManagerInterface $manager)
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
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional Framework\Config object with configuration options.
     * @return  void
     */
    protected function _initialize(Framework\Config $config)
    {
        $application = $this->getService('application');

        $config->append(array(
            'options'	=> array(
                'host'		   => $application->getCfg('host'),
                'username'	   => $application->getCfg('user'),
                'password'     => $application->getCfg('password'),
                'database'	   => $application->getCfg('db'),
            ),
            'table_prefix' => $application->getCfg('dbprefix')
        ));

        parent::_initialize($config);
    }

	/**
	 * Retrieves the table schema information about the given table
	 *
	 * This function try to get the table schema from the cache. If it cannot be found
	 * the table schema will be retrieved from the database and stored in the cache.
	 *
	 * @param 	string 	A table name or a list of table names
	 * @return	Framework\DatabaseSchemaTable
	 */
	public function getTableSchema($table)
	{
	    if(!isset($this->_table_schema[$table]) && isset($this->_cache))
		{
		    $database = $this->getDatabase();

		    $identifier = md5($database.$table);

	        if (!$schema = $this->_cache->get($identifier))
	        {
	            $schema = parent::getTableSchema($table);

	            //Store the object in the cache
		   	    $this->_cache->store(serialize($schema), $identifier);
	        }
	        else $schema = unserialize($schema);

		    $this->_table_schema[$table] = $schema;
	    }

	    return parent::getTableSchema($table);
	}
}