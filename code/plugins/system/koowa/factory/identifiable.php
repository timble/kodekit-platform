<?php
/**
 * @version 	$Id: interface.php 1061 2009-07-20 17:00:46Z johan $
 * @category	Koowa
 * @package		Koowa_Factory
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Identifiable interface
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 */
interface KFactoryIdentifiable
{
	/**
	 * Get the identifier
	 *
	 * @return object A KFactoryIdentifier object
	 */
	public function getIdentifier();
}