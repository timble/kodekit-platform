<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Extensions;

use Nooku\Library;

/**
 * Components Database Table
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class DatabaseTableComponents extends Library\DatabaseTableDefault
{
    public function  _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'filters'  => array('params' => 'ini')
        ));
        
        parent::_initialize($config);
    }
}