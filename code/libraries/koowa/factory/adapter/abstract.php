<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Abstract Factory Adapter
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Adapter
 */
abstract class KFactoryAdapterAbstract extends KObject implements KFactoryAdapterInterface
{
	/** 
	 * The adapter type
	 * 
	 * @var string
	 */
	protected $_type = '';
	
	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct( KConfig $config = null) 
	{ 
		//If no config is passed create it
		if(!isset($config)) $config = new KConfig();
		
		parent::__construct($config);
	}
	
	/**
	 * Get the type
	 *
	 * @return string	Returns the type
	 */
	public function getType()
	{
		return $this->_type;
	}
}