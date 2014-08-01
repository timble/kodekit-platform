<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Articles;

use Nooku\Library;

/**
 * Bootstrapper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Articles
 */
class Bootstrapper extends Library\ObjectBootstrapperComponent
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'aliases'  => array(
                'com:articles.database.behavior.creatable'  => 'com:users.database.behavior.creatable',
                'com:articles.database.behavior.modifiable' => 'com:users.database.behavior.modifiable',
                'com:articles.database.behavior.lockable'   => 'com:users.database.behavior.lockable',
            ),
        ));

        parent::_initialize($config);
    }
}