<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * User Null Session Handler
 *
 * Can be used in unit testing or in a situation where persisted sessions are not desired.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\User
 * @see     http://www.php.net/manual/en/function.session-set-save-handler.php
 */
class UserSessionHandlerNull extends UserSessionHandlerAbstract
{

}