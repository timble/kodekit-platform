<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Session
 * @subpackage  Handler
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.koowa.org
 */

/**
 * File Session Handler (default PHP handler)
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Session
 * @subpackage  Handler
 */
class KSessionHandlerNone extends KSessionHandlerAbstract
{
	/**
	 * Constructor
	 *
	 * @param array Optional parameters
	 */
	public function __construct( array $options = array() )
	{
		//Don't register the session handler, let people hanlde it instead
	}
}
