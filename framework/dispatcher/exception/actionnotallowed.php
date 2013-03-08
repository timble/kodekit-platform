<?php
/**
 * @package     Koowa_Dispatcher
 * @subpackage  Exception
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Dispatcher Exception Not Allowed Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Exception
 */
class DispatcherExceptionActionNotAllowed extends HttpExceptionMethodNotAllowed implements DispatcherException {}