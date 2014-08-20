<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

return array(

    'aliases'  => array(
        'application.languages' => 'com:application.model.entity.languages',
        'application.pages'     => 'com:application.model.entity.pages',
        'application.modules'   => 'com:application.model.entity.modules',
    ),

    'identifiers' => array(

        'com:application.template.locator.component'  => array(
            'theme_path' => Nooku::getInstance()->getBasePath().'/public/theme/bootstrap'
        ),

        'com:application.template.filter.asset'  => array(
            'schemes' => array('/assets/application/' => '/theme/bootstrap/')
        ),
    )
);
