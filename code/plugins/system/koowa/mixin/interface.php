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
 * Mixes a chain of command behaviour into a class
 *  
 * @author      Johan Janssens <johan@joomlatools.org>
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