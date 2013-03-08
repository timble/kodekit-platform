<?php
/**
 * @package     Koowa_Http
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Route Class
 *
 * @author        Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Router
 */
class DispatcherRouterRoute extends HttpUrl
{
    /**
     * Convert the url or part of it to a string
     *
     * Using scheme://user:pass@host/path?query#fragment';
     *
     * @param integer $parts A bitmask of binary or'ed HTTP_URL constants; FULL is the default
     * @return  string
     */
    public function toString($parts = self::FULL)
    {
        if(isset($this->query['option'])) {
            $this->getService('application')->getRouter()->build($this);
        }

        return parent::toString($parts);
    }
}