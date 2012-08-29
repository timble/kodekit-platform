<?php
/**
 * @version     $Id$
 * @package     Koowa_Http
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Route Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Router
 */
class KDispatcherRouterRoute extends KHttpUrl
{
    /**
     * Return a string representation of this url.
     *
     * @see    get()
     * @return string
     */
    public function __toString()
    {
        return JRoute::_($this->getUrl(self::FULL));
    }
}