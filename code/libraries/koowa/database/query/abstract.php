<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Query
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract database query class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage  Query
 */
abstract class KDatabaseQueryAbstract extends KObject
{
    /**
	 * Database connector
	 *
	 * @var		object
	 */
	protected $_adapter;
	
	/**
	 * Object constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		if(!isset($config)) $config = new KConfig();
		
        parent::__construct($config);
        
        if ($config->adapter instanceof KDatabaseAdapterInterface) {
		    $this->setAdapter($config->adapter);
        }
	}


    /**
     * Initializes the options for the object
     *
     * @param 	object 	An optional KConfig object with configuration options.
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'adapter' => KService::get('koowa:database.adapter.mysqli') 
    	));
    }
    
    /**
     * Gets the database adapter for this particular KDatabaseQuery object.
     *
     * @return KDatabaseAdapterInterface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }
        
	/**
     * Set the database adapter for this particular KDatabaseQuery object.
     *
     * @param object A KDatabaseAdapterInterface object
     * @return KDatabaseQuery
     */
    public function setAdapter(KDatabaseAdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }
}