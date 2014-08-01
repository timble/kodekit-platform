<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Revisions;

use Nooku\Library;

/**
 * Bootstrapper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Revisions
 */
class Bootstrapper extends Library\ObjectBootstrapperComponent
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'aliases'  => array(
                'com:revisions.database.behavior.creatable' => 'com:users.database.behavior.creatable',
            ),
        ));

        parent::_initialize($config);
    }
}