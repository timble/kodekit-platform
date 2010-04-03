<?php
/**
 * @version 	$Id: interface.php 1061 2009-07-20 17:00:46Z johan $
 * @category	Koowa
 * @package		Koowa_Factory
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Identifiable interface
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 */
interface KObjectIdentifiable
{
	/**
	 * Get the object identifier
	 * 
	 * @return	KIdentifier	
	 */
	public function getIdentifier();
}