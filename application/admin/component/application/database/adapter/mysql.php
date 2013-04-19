<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Application Database MySQLi Adapter Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ApplicationDatabaseAdapterMysql extends Library\DatabaseAdapterMysql implements Library\ObjectInstantiatable
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
	 * @param 	object 	An optional Library\Config object with configuration options
	 */
	public function __construct(Library\Config $config)
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
     * @param 	Config                   $config  An optional Config object with configuration options
     * @param 	ObjectManagerInterface  $manager A Library\ObjectManagerInterface object
     * @return  DatabaseTableInterface
     */
    public static function getInstance(Library\Config $config, Library\ObjectManagerInterface $manager)
    {
        if (!$manager->has($config->object_identifier))
        {
            $classname = $config->object_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->object_identifier, $instance);
        }

        return $manager->get($config->object_identifier);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional Library\Config object with configuration options.
     * @return  void
     */
    protected function _initialize(Library\Config $config)
    {
        $application = $this->getObject('application');

        $config->append(array(
            'options'	=> array(
                'host'		   => $application->getCfg('host'),
                'username'	   => $application->getCfg('user'),
                'password'     => $application->getCfg('password'),
                'database'	   => $application->getCfg('db'),
            )
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
	 * @return	Library\DatabaseSchemaTable
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