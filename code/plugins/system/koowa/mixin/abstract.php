<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Mixin
 * @copyright   Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
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
    
	public function __construct($mixer)
	{
		$this->_mixer = $mixer;
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