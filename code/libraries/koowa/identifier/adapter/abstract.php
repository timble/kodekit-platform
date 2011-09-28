<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Identifier
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Abstract Identifier Adapter
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Identifier
 * @subpackage 	Adapter
 */
abstract class KIdentifierAdapterAbstract extends KObject implements KIdentifierAdapterInterface
{
	/** 
	 * The adapter type
	 * 
	 * @var string
	 */
	protected $_type = '';
	
	/**
	 * Loader object
	 *
	 * @var	object
	 */
	protected $_loader;
	
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
		
		//Set the loader
		$this->_loader = $config->loader;
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
         $config->append(array(
            'loader'  => '',
        ));
        
        parent::_initialize($config);
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