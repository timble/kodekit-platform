<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Attribute User Session Container
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
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