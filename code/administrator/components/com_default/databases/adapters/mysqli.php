<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Default Database MySQLi Adapter
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultDatabaseAdapterMysqli extends KDatabaseAdapterMysqli implements KObjectInstantiatable
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
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
	
		if(KFactory::get('joomla:config')->getValue('config.caching')) {
	        $this->_cache = KFactory::get('joomla:cache', array('database', 'output'));
		}
	}
	
	/**
     * Force creation of a singleton
     *
     * @return KDatabaseTableDefault
     */
    public static function getInstance($config = array())
    {
        static $instance;
        
        if ($instance === NULL) 
        {
            //Create the singleton
            $classname = $config->identifier->classname;
            $instance = new $classname($config);
        }
        
        return $instance;
    }
    
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $db = KFactory::get('joomla:database');
        
		$resource = method_exists($db, 'getConnection') ? $db->getConnection() : $db->_resource;
		$prefix   = method_exists($db, 'getPrefix')     ? $db->getPrefix()     : $db->_table_prefix;
        
        $config->append(array(
    		'connection'   => $resource,
            'table_prefix' => $prefix,
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
	 * @return	KDatabaseSchemaTable
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