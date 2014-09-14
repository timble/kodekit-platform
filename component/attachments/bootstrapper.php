<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Attachments;

use Nooku\Library;

/**
 * Bootstrapper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Attachments
 */
class Bootstrapper extends Library\ObjectBootstrapperComponent
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'aliases'  => array(
                'com:attachments.database.behavior.creatable'  => 'com:users.database.behavior.creatable',
                'com:attachments.database.behavior.modifiable' => 'com:users.database.behavior.modifiable',
                'com:attachments.database.behavior.lockable'   => 'com:users.database.behavior.lockable',
            ),
        ));

        parent::_initialize($config);
    }
}