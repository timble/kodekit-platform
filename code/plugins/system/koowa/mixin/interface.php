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
 * Mixes a chain of command behaviour into a class
 *  
 * @author      Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Mixin
 */
interface KMixinInterface
{   
	/**
	 * Get the methods that are available for mixin. 
	 * 
	 * @return array An array of methods
	 */
	public function getMixinMethods();
}