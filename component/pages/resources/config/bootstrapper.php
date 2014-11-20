<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

return array(

    'aliases'  => array(
        'com:pages.database.behavior.creatable'  => 'com:users.database.behavior.creatable',
        'com:pages.database.behavior.modifiable' => 'com:users.database.behavior.modifiable',
        'com:pages.database.behavior.lockable'   => 'com:users.database.behavior.lockable',
    ),

    'identifiers' => array(

        'template.locator.factory' => array(

            'locators' => array('com:pages.template.locator.module')

        )
    )

);