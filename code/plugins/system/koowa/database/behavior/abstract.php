<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Database
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
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
	 * The object identifier
	 *
	 * @var KIdentifierInterface
	 */
	protected $_identifier;
	
 	/**
	 * Object constructor
	 *
	 * @param	array 	An optional associative array of configuration settings.
	 * 					Recognized key values include 'mixer' (this list is not 
	 * 					meant to be comprehensive).
	 */
	public function __construct(array $options = array())
	{
        $this->_identifier = $options['identifier'];
        
		parent::__construct($options);
	}
	
	/**
	 * Command handler
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
	 * Get the identifier
	 *
	 * @return 	KIdentifierInterface
	 * @see 	KFactoryIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
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
		
		return array_diff($methods, array('getIdentifier', 'execute'));
	}
}