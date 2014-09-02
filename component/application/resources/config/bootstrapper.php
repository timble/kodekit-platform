<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

return array(

    'aliases'  => array(
        'application'                    => 'com:application.dispatcher.http',
        'translator'                     => 'com:application.translator',
        'lib:dispatcher.router.route'    => 'com:application.dispatcher.router.route',

        'lib:template.locator.component'    => 'com:application.template.locator.component',
        'lib:template.locator.file'         => 'com:application.template.locator.file',
        'com:pages.template.locator.module' => 'com:application.template.locator.module',
    )
);
