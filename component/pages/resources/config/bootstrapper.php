<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Component\Pages;

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