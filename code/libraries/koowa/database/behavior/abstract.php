<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Database
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Database Chekcable Behavior 
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
abstract class KDatabaseBehaviorAbstract extends KMixinAbstract implements KDatabaseBehaviorInterface
{
	/**
	 * The behavior identifier
	 *
	 * @var KIdentifierInterface
	 */
	protected $_identifier;
	
	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct( KConfig $config = null) 
	{ 
		$this->_identifier = $config->identifier;
		parent::__construct($config);
	}
	
	/**
	 * Get the object identifier
	 * 
	 * @return	KIdentifier	
	 * @see 	KObjectIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}
	
	/**
	 * Command handler
	 * 
	 * This function transmlated the command name to a command handler function of 
	 * the format '_beforeTable[Command]' or '_afterTable[Command]. Command handler
	 * functions should be declared protected.
	 * 
	 * @param 	string  	The command name
	 * @param 	object   	The command context
	 * @return 	boolean		Can return both true or false.  
	 */
	final public function execute( $name, KCommandContext $context) 
	{
		$parts = explode('.', $name);	
		$method = '_'.lcfirst(KInflector::implode($parts));
		
		if(method_exists($this, $method)) {
			return $this->$method($context);
		}
		
		return true;
	}
	
	/**
	 * Get an object handle
	 * 
	 * This function only returns a valid handle if one or more command handler 
	 * functions are defined. A commend handler function needs to follow the 
     * following format : '_afterTable[Event]' or '_beforeTable[Event]' to be 
     * recognised.
	 * 
	 * @return string A string that is unique, or NULL
	 * @see execute()
	 */
	public function getHandle()
	{
		$methods = get_class_methods(get_class($this));
		
		foreach($methods as $method) 
		{
			if(substr($method, 0, 7) == '_before' || substr($method, 0, 6) == '_after') {
				return parent::getHandle();	
			}
		}
		
		return null;
	}
	
	/**
	 * Get the methods that are available for mixin based 
	 * 
	 * This function also dynamically adds a function of format is[Behavior] 
	 * to allow client code to check if the behavior is callable. 
	 * 
	 * @param object The mixer requesting the mixable methods. 
	 * @return array An array of methods
	 */
	public function getMixableMethods(KObject $mixer = null)
	{
		$methods   = parent::getMixableMethods($mixer);
		$methods[] = 'is'.ucfirst($this->_identifier->name);
			
		return array_diff($methods, array('execute'));
	}
}