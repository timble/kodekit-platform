<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Menus Database Table
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Nooku\Component\Pages
 */
class DatabaseTableMenus extends Library\DatabaseTableAbstract
{
    public function  _initialize(Library\ObjectConfig $config)
    {		
        $config->append(array(
            'behaviors'  => array(
                'creatable', 'modifiable', 'lockable', 'sluggable', 'identifiable'
            )
            ));
     
        parent::_initialize($config);
    }
}