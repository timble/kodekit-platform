<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

return array(

    'aliases'  => array(
        'pages'         => 'com:pages.model.composite.pages',
        'pages.menus'   => 'com:pages.model.composite.menus',
        'pages.modules' => 'com:pages.model.composite.modules',
    ),

    'identifiers' => array(

        'template.locator.factory' => array(
            'locators' => array('com:pages.template.locator.module')
        ),

        'dispatcher' => array(
            'behaviors' => array('com:pages.dispatcher.behavior.windowable')
        ),
    )
);