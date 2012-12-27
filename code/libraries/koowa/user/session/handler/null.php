<?php
/**
 * @version		$Id$
 * @package		Koowa_Dispatcher
 * @subpackage  Session
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Null Session Handler Class
 *
 * Can be used in unit testing or in a situation where persisted sessions are not desired.
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Session
 * @see         http://www.php.net/manual/en/function.session-set-save-handler.php
 */
class KDispatcherSessionHandlerNull extends KDispatcherSessionHandlerAbstract
{

}