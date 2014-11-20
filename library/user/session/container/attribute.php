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
 * Attribute User Session Container
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\User
 */
class UserSessionContainerAttribute extends UserSessionContainerAbstract
{
    public function load(array &$session)
    {
        if(!isset($session[$this->_namespace])) {
            $session[$this->_namespace] = array();
        }

        // Merge session data with current existing container data.
        $session[$this->_namespace] = array_merge($this->_data, $session[$this->_namespace]);

        return parent::load($session);
    }
}