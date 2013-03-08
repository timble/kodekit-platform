<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage  Response
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Dispatcher Response Transport Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Response
 */
interface DispatcherResponseTransportInterface
{
    /**
     * Send the response
     *
     * @return DispatcherResponseTransportInterface
     */
    public function send();
}