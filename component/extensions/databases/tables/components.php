<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Framework;

/**
 * Components Database Table
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class ComExtensionsDatabaseTableComponents extends Framework\DatabaseTableDefault
{
    public function  _initialize(Framework\Config $config)
    {
        $config->append(array(
            'filters'  => array('params' => 'ini')
        ));
        
        parent::_initialize($config);
    }
}