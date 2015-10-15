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
        'pages'         => 'com:pages.model.pages',
        'pages.menus'   => 'com:pages.model.menus',
        'pages.modules' => 'com:pages.model.modules',
    ),

    'identifiers' => array(

        'template.locator.factory' => array(
            'locators' => array('com:pages.template.locator.module')
        ),

        'pages' => array(
            'decorators' => array('model.composite'),
            'state_defaults' => array(
                'enabled'       => true,
                'application'   => APPLICATION_NAME,
            )
        ),

        'pages.menus' => array(
            'decorators' => array('model.composite'),
            'state_defaults' => array(
                'enabled'       => true,
                'application'   => APPLICATION_NAME,
            )
        ),

        'pages.modules' => array(
            'decorators' => array('model.composite'),
            'state_defaults' => array(
                'enabled'  => true,
                'page'     => Pages\ModelModules::ACTIVE,
            )
        )
    )
);