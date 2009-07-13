<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Mixin
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.koowa.org
 */

/**
 * Abstract mixing class, implements the KMixinInterface
 *  
 * @author      Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Mixin
 * @uses 		KObject
 */
abstract class KMixinAbstract extends KObject implements KMixinInterface
{   
	/**
     * The object doing the mixin
     *
     * @var object
     */
    protected $_mixer;
    
    /**
	 * Object constructor
	 *
	 * @param	array 	An optional associative array of configuration settings.
	 * Recognized key values include 'mixer' (this list is not meant to be comprehensive).
	 */
	public function __construct(array $options = array())
	{
		if(is_null($options['mixer'])) {
			throw new KMixinException('mixer [KObject] option is required');
		}
		
		$this->_mixer = $options['mixer'];
	}
	
	/**
     * Initializes the options for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   array   Options
     * @return  array   Options
     */
    protected function _initialize(array $options)
    {
    	$defaults = array(
            'mixer' =>  null,
        );

        return array_merge($defaults, $options);
    }
	
	/**
	 * Get the methods that are available for mixin. 
	 * 
	 * @return array An array of methods
	 */
	public function getMixinMethods()
	{
		$remove  = array('__construct', '__destruct');
        $methods = array_diff(get_class_methods($this), get_class_methods($this->_mixer), $remove);
        
        return $methods;
	}
}