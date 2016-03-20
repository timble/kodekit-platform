<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Attribute User Session Container
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\User
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