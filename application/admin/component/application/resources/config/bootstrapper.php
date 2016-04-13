<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

return array(

    'identifiers' => array(

        /*'dispatcher'  => array(
            'behaviors' => 'com:pages.dispatcher.behavior.accessible'
        ),*/

        'com:application.template.locator.component'  => array(
            'override_path' => APPLICATION_BASE.'/public/theme/default/templates/view'
        ),

        'com:application.template.filter.asset'         => array(
            'schemes' => array(
                'assets://application/' => '/administrator/theme/default/'
            )
        )
    )
);

